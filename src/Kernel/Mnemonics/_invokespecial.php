<?php
namespace PHPJava\Kernel\Mnemonics;

use PHPJava\Core\JavaClassInterface;
use PHPJava\Exceptions\UnableToCatchException;
use PHPJava\Kernel\Attributes\CodeAttribute;
use PHPJava\Kernel\Internal\InstanceDeferredLoader;
use PHPJava\Kernel\Structures\_ExceptionTable;
use PHPJava\Utilities\AttributionResolver;
use PHPJava\Utilities\ClassResolver;
use PHPJava\Utilities\Formatter;

final class _invokespecial implements OperationInterface
{
    use \PHPJava\Kernel\Core\Accumulator;
    use \PHPJava\Kernel\Core\ConstantPool;
    use \PHPJava\Kernel\Core\DependencyInjector;

    public function execute(): void
    {
        $cpInfo = $this->getConstantPool();
        $cp = $cpInfo[$this->readUnsignedShort()];
        $nameAndTypeIndex = $cpInfo[$cp->getNameAndTypeIndex()];
        $className = $cpInfo[$cpInfo[$cp->getClassIndex()]->getClassIndex()]->getString();
        $methodName = $cpInfo[$nameAndTypeIndex->getNameIndex()]->getString();
        $signature = $cpInfo[$nameAndTypeIndex->getDescriptorIndex()]->getString();
        $parsedSignature = Formatter::parseSignature($signature);

        // POP with right-to-left (objectref + arguments)
        $arguments = array_fill(0, $parsedSignature['arguments_count'], null);
        for ($i = count($arguments) - 1; $i >= 0; $i--) {
            $arguments[$i] = $this->popFromOperandStack();
        }

        /**
         * @var InstanceDeferredLoader $objectref
         */
        $objectref = $this->popFromOperandStack();

        /**
         * @var JavaClassInterface $newObject
         */
        $newObject = null;
        try {
            $methodName = $cpInfo[$nameAndTypeIndex->getNameIndex()]->getString();

            if ($objectref->getClassName() !== $className) {
                // If $objectref is not match $className, then change current class (I have no confidence).
                // See also: https://docs.oracle.com/javase/specs/jvms/se11/html/jvms-6.html#jvms-6.5.invokespecial

                // FIXME: if $objectref has superclass, then refer superclass from $objectref
                // NOTE: This implementation is a ** first aid **.
                [$resourceType, $classObject] = $objectref
                    ->getOptions('class_resolver')
                    ->resolve(
                        $className,
                        $this->javaClass
                    );

                switch ($resourceType) {
                    case ClassResolver::RESOLVED_TYPE_PACKAGES:
                        $newObject = new $classObject($methodName, ...$arguments);
                        break;
                    case ClassResolver::RESOLVED_TYPE_CLASS:
                        $newObject = $classObject($methodName, ...$arguments);
                        break;
                }
            } elseif ($objectref instanceof InstanceDeferredLoader) {
                $newObject = $objectref->instantiate($methodName, ...$arguments);
            } else {
                /**
                 * @var JavaClassInterface $objectref
                 */
                $newObject = $objectref($methodName, ...$arguments);
            }

            // NOTE: PHP has a problem which a reference object cannot replace to an object.
            $this->replaceReferredObject($objectref, $newObject);
        } catch (\Exception $e) {
            /**
             * @var CodeAttribute $codeAttribute
             */
            $codeAttribute = AttributionResolver::resolve(
                $this->getAttributes(),
                CodeAttribute::class
            );

            $expectedClass = Formatter::convertPHPNamespacesToJava(get_class($e));

            foreach ($codeAttribute->getExceptionTables() as $exception) {
                /**
                 * @var _ExceptionTable $exception
                 */
                $catchClass = Formatter::convertPHPNamespacesToJava($cpInfo[$cpInfo[$exception->getCatchType()]->getClassIndex()]->getString());
                if ($catchClass === $expectedClass &&
                    $exception->getStartPc() <= $this->getProgramCounter() &&
                    $exception->getEndPc() >= $this->getProgramCounter()
                ) {
                    $this->setOffset($exception->getHandlerPc());
                    return;
                }
            }

            throw new UnableToCatchException(
                $expectedClass . ': ' . $e->getMessage(),
                0,
                $e
            );
        }
    }
}
