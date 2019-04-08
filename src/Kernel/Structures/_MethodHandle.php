<?php
namespace PHPJava\Kernel\Structures;

use PHPJava\Exceptions\NotImplementedException;
use PHPJava\Utilities\BinaryTool;

class _MethodHandle implements StructureInterface
{
    use \PHPJava\Kernel\Core\BinaryReader;
    use \PHPJava\Kernel\Core\ConstantPool;
    use \PHPJava\Kernel\Core\DebugTool;

    private $referenceKind = 0;
    private $referenceIndex = 0;

    public function execute(): void
    {
        $this->referenceKind = $this->readUnsignedByte();
        $this->referenceIndex = $this->readUnsignedShort();
    }
}
