<?php

namespace SwayOleg\ApiBulk\Model\Import;

use \Magento\Framework\Registry;
use  Magento\Framework\Event\ObserverInterface;
use \Magento\CatalogImportExport\Model\Import\Product as ImportProduct;

class ImportObserver implements ObserverInterface{

    CONST REG_KEY = 'api_bulk_report';

    /** @var Registry  */
    public $registry;


    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
    }

    /** Subscribed on catalog_product_import_finish_before event
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        /** @var ImportProduct $adapter*/
        $adapter = $observer->getAdapter();
        $report['created_items'] = $adapter->getCreatedItemsCount();
        $report['updated_items'] = $adapter->getUpdatedItemsCount();
        $report['processed_entities'] = $adapter->getProcessedEntitiesCount();
        $report['processed_rows'] = $adapter->getProcessedRowsCount();
        if ($this->registry->registry(self::REG_KEY)) {
            $this->registry->unregister(self::REG_KEY);
        }
        $this->registry->register(self::REG_KEY, $report);

    }
}