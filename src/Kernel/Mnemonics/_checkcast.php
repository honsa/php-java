<?php
namespace PHPJava\Kernel\Mnemonics;

use PHPJava\Packages\java\lang\ClassCastException;
use PHPJava\Utilities\Formatter;
use PHPJava\Utilities\TypeResolver;

final class _checkcast implements OperationInterface
{
    use \PHPJava\Kernel\Core\Accumulator;
    use \PHPJava\Kernel\Core\ConstantPool;

    /**
     * @throws ClassCastException
     */
    public function execute(): void
    {
        $cp = $this->getConstantPool();
        $index = $this->readUnsignedShort();
        $objectref = $this->popFromOperandStack();

        if ($objectref === null) {
            return;
        }

        $castTo = $cp[$cp[$index]->getClassIndex()]->getString();

        $fromObjectClass = Formatter::convertPHPNamespacesToJava(get_class($objectref));

        [$classes, $interfaces] = TypeResolver::getExtendedClasses(
            'L' . str_replace('/', '.', $castTo)
        )[0] ?? [[], []];

        if (in_array($fromObjectClass, $classes, true)) {
            return;
        }

        throw new ClassCastException(
            'class \\' . get_class($objectref) . ' cannot be cast to class ' . Formatter::convertJavaNamespaceToPHP($castTo)[1]
        );
    }
}
