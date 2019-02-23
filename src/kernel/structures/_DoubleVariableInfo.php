<?php
namespace PHPJava\Kernel\Structures;

use \PHPJava\Exceptions\NotImplementedException;
use \PHPJava\Kernel\Utilities\BinaryTool;

class _DoubleVariableInfo implements StructureInterface
{
    use \PHPJava\Kernel\Core\BinaryReader;

    private $Tag = null;
    public function execute(): void
    {
        $this->Tag = $this->readUnsignedByte();
    }
}
