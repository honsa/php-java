<?php
namespace PHPJava\Kernel\Mnemonics;

final class _pop implements OperationInterface
{
    use \PHPJava\Kernel\Core\Accumulator;
    use \PHPJava\Kernel\Core\ConstantPool;

    public function execute(): void
    {
        $this->popStack();
    }
}
