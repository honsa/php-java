<?php
namespace PHPJava\Kernel\Structures;

class _Integer implements StructureInterface
{
    use \PHPJava\Kernel\Core\BinaryReader;
    use \PHPJava\Kernel\Core\ConstantPool;
    use \PHPJava\Kernel\Core\DebugTool;

    /**
     * @var int
     */
    private $bytes;

    public function execute(): void
    {
        $this->bytes = $this->readInt();
    }

    public function getBytes(): int
    {
        return $this->bytes;
    }
}
