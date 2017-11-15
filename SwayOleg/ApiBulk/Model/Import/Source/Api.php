<?php

namespace SwayOleg\ApiBulk\Model\Import\Source;

/**
 * Class Api
 * @package SwayOleg\ApiBulk\Model\Import\Source
 */
class Api extends \Magento\ImportExport\Model\Import\AbstractSource {


    /** @var  \ArrayObject  */
    protected $arrayDataObject;
    /** @var \ArrayIterator  */
    protected $arrayDataObjectIterator;

    /**
     * Get data array and detect column names
     * There must be column names in the array keys
     *
     * @param mixed $arrayData
     * @param array $colNames
     * @throws \LogicException
     */
    public function __construct(
        $arrayData,
        $colNames = []
    ) {
        $this->arrayDataObject = new \ArrayObject( $arrayData );
        $this->arrayDataObjectIterator = $this->arrayDataObject->getIterator();
        parent::__construct($this->getColNames());
    }

    public function getColNames()
    {
        $this->_colNames = array_keys($this->arrayDataObjectIterator->current());
        return $this->_colNames;
    }

    /**
     * Get next item
     *
     * @return array|bool
     */
    protected function _getNextRow()
    {
        $this->arrayDataObjectIterator->next();
        return $this->arrayDataObjectIterator->current() ?? [];
    }


    /**
     * Rewind the \Iterator to the first item (\Iterator interface)
     *
     * @return void
     */
    public function rewind()
    {
        $this->arrayDataObjectIterator->rewind();
        parent::rewind();
    }

}