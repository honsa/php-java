<?php
namespace PHPJava\Kernel\Mnemonics;

use PHPJava\Kernel\Types\_Long;

final class _lload_2 implements OperationInterface
{
    use \PHPJava\Kernel\Core\Accumulator;
    use \PHPJava\Kernel\Core\ConstantPool;

    public function execute(): void
    {
        $this->pushToOperandStack(
            _Long::get(
                $this->getLocalStorage(2)
            )
        );
    }
}
