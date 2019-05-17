<?php
namespace PHPJava\Core\JVM;

use PHPJava\Core\Stream\Reader\ReaderInterface;
use PHPJava\Exceptions\ReadOnlyException;
use PHPJava\Utilities\DebugTool;

class InterfacePool implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @var int[]
     */
    private $entries = [];

    /**
     * @var ReaderInterface
     */
    private $reader;

    public function __construct(
        ReaderInterface $reader,
        int $entries,
        ConstantPool $constantPool,
        DebugTool $debugTool
    ) {
        $this->reader = $reader;
        for ($i = 0; $i < $entries; $i++) {
            $this->entries[$i] = $reader->getBinaryReader()->readUnsignedShort();
        }
    }

    /**
     * @return int[]
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->entries[$offset]);
    }

    /**
     * @param int $offset
     * @return int
     */
    public function offsetGet($offset)
    {
        return $this->entries[$offset];
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->entries);
    }

    /**
     * @throws ReadOnlyException
     */
    public function offsetSet($offset, $value)
    {
        throw new ReadOnlyException('You cannot rewrite datum. The Interface Pool is read-only.');
    }

    /**
     * @throws ReadOnlyException
     */
    public function offsetUnset($offset)
    {
        throw new ReadOnlyException('You cannot rewrite datum. The Interface Pool is read-only.');
    }

    /**
     * @return \ArrayIterator<int>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->entries);
    }
}
