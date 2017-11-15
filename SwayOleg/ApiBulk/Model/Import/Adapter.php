<?php

namespace SwayOleg\ApiBulk\Model\Import;

use \Magento\ImportExport\Model\Import\AbstractSource;
use \Magento\Framework\App\ObjectManager;

/**
 * Class Adapter
 * @package SwayOleg\ApiBulk\Model\Import
 */
class Adapter extends \Magento\ImportExport\Model\Import\Adapter {

    /**
     * @var array
     */
    protected $adapters;

    /**
     * Adapter constructor.
     * @param array $adapters
     */
    public function __construct(array $adapters) {
        $this->adapters = $adapters;
    }


    /**
     * Adapter factory. Checks for availability, loads and create instance of import adapter object.
     *
     * @param string $type Adapter type ('csv', 'xml', 'api' etc.)
     * @param mixed $data Adapter data
     * @param \Magento\Framework\Filesystem\Directory\Write|null $directory
     * @param string|null $source
     * @param mixed $options OPTIONAL Adapter constructor options
     *
     * @return AbstractSource
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function fromFactory($type, $data, $directory = null, $source = null, $options = null)
    {
        if(isset($this->adapters[$type])) {
            $adapterClass = $this->adapters[$type];
            if (!class_exists($adapterClass)) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Adapter for source type $1 doesn\'t exists', $type)
                );
            }
            $adapter = new $adapterClass($data, []);

            if (!$adapter instanceof AbstractSource) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Adapter must be an instance of \Magento\ImportExport\Model\Import\AbstractSource')
                );
            }
            return $adapter;
        }
        return parent::factory($type, $directory, $source, $options);
    }

    /**
     * Create adapter instance for specified source file.
     *
     * @param string $source Source file path or type like api.
     * @param mixed $data Adapter data
     * @param \Magento\Framework\Filesystem\Directory\Write|null $directory
     * @param mixed $options OPTIONAL Adapter constructor options
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \SwayOleg\ApiBulk\Model\Import\Adapter|AbstractSource
     */
    public static function findAdapterFor($source, $data, $directory = null, $options = null)
    {
        /** @var \SwayOleg\ApiBulk\Model\Import\Adapter $instance */
        $instance = ObjectManager::getInstance()->get(self::class);
        return $instance->fromFactory('api', $data, $directory, $source, $options);
    }

}