<?php
namespace PHPJava\Kernel\Variables;

class ObjectVariableInfo implements VariableInfoInterface
{
    use \PHPJava\Kernel\Core\BinaryReader;
    use \PHPJava\Kernel\Core\ConstantPool;

    /**
     * @var int
     */
    private $tag;

    /**
     * @var int
     */
    private $cpoolIndex;

    public function execute(): void
    {
        $this->tag = $this->readUnsignedByte();
        $this->cpoolIndex = $this->readUnsignedShort();
    }
}
