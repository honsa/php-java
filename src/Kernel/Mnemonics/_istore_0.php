<?php
namespace PHPJava\Kernel\Mnemonics;

final class _istore_0 implements OperationInterface
{
    use \PHPJava\Kernel\Core\Accumulator;
    use \PHPJava\Kernel\Core\ConstantPool;

    public function execute(): void
    {
        $this->setLocalStorage(0, $this->popFromOperandStack());
    }
}
