<?php
namespace PHPJava\Kernel\Attributes;

use PHPJava\Kernel\Structures\_LocalVariableTable;

final class LocalVariableTableAttribute implements AttributeInterface
{
    use \PHPJava\Kernel\Core\BinaryReader;
    use \PHPJava\Kernel\Core\ConstantPool;
    use \PHPJava\Kernel\Core\AttributeReference;
    use \PHPJava\Kernel\Core\DebugTool;

    /**
     * @var int
     */
    private $localVariableTableLength;

    /**
     * @var _LocalVariableTable[]
     */
    private $localVariableTables = [];

    public function execute(): void
    {
        $this->localVariableTableLength = $this->readUnsignedShort();
        for ($i = 0; $i < $this->localVariableTableLength; $i++) {
            $localVariableTable = new _LocalVariableTable($this->reader);
            $localVariableTable->execute();
            $this->localVariableTables[] = $localVariableTable;
        }
    }
}
