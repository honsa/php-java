<?php
namespace PHPJava\Kernel\Mnemonics;

use PHPJava\Kernel\Types\_Int;

final class _iconst_1 implements OperationInterface
{
    use \PHPJava\Kernel\Core\Accumulator;
    use \PHPJava\Kernel\Core\ConstantPool;

    public function execute(): void
    {
        $this->pushToOperandStack(_Int::get(1));
    }
}
