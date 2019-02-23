<?php
namespace PHPJava\Kernel\Structures;

use \PHPJava\Exceptions\NotImplementedException;
use \PHPJava\Kernel\Utilities\BinaryTool;

class _LocalVariableTable implements StructureInterface
{
    use \PHPJava\Kernel\Core\BinaryReader;

    private $StartPc = 0;
    private $Length = 0;
    private $NameIndex = 0;
    private $DescriptorIndex = 0;
    private $Index = 0;
    public function execute(): void
    {
        $this->StartPc = $this->readUnsignedShort();
        $this->Length = $this->readUnsignedShort();
        $this->NameIndex = $this->readUnsignedShort();
        $this->DescriptorIndex = $this->readUnsignedShort();
        $this->Index = $this->readUnsignedShort();
    }
}
