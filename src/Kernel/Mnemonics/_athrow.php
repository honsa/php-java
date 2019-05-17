<?php
namespace PHPJava\Kernel\Mnemonics;

use PHPJava\Exceptions\UnableToCatchException;
use PHPJava\Kernel\Attributes\CodeAttribute;
use PHPJava\Kernel\Structures\_ExceptionTable;
use PHPJava\Utilities\AttributionResolver;
use PHPJava\Utilities\Formatter;

final class _athrow implements OperationInterface
{
    use \PHPJava\Kernel\Core\Accumulator;
    use \PHPJava\Kernel\Core\ConstantPool;

    /**
     * @throws UnableToCatchException
     */
    public function execute(): void
    {
        $cpInfo = $this->getConstantPool();
        $objectref = $this->popFromOperandStack();

        $className = str_replace('\\', '/', get_class($objectref));

        /**
         * @var CodeAttribute $codeAttribute
         */
        $codeAttribute = AttributionResolver::resolve(
            $this->getAttributes(),
            CodeAttribute::class
        );

        $className = Formatter::convertPHPNamespacesToJava($className);
        foreach ($codeAttribute->getExceptionTables() as $exception) {
            /**
             * @var _ExceptionTable $exception
             */
            $catchClass = Formatter::convertPHPNamespacesToJava($cpInfo[$cpInfo[$exception->getCatchType()]->getClassIndex()]->getString());
            if ($catchClass === $className &&
                $exception->getStartPc() <= $this->getProgramCounter() &&
                $exception->getEndPc() >= $this->getProgramCounter()
            ) {
                $this->setOffset($exception->getHandlerPc());
                return;
            }
        }

        throw new UnableToCatchException(
            "Unable to catch {$className} exception. " .
            'PHPJava has stopped operations. ' .
            'You may be running broken Java class. '
        );
    }
}
