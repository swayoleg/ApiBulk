<?php

namespace SwayOleg\ApiBulk\Model;

use SwayOleg\ApiBulk\Api\ProductInterface;
use SwayOleg\ApiBulk\Model\Import\Adapter;
use SwayOleg\ApiBulk\Model\Import\ImportObserver;

use \Magento\Framework\Registry;
use \Magento\ImportExport\Model\Import as MagentoImport;
use \Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingError;
use \Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;

class Product implements ProductInterface {

    /**
     * @var \Magento\ImportExport\Model\Import
     */
    protected $importModel;

    /** @var array */
    protected $report = [];

    /** @var Import\Source\Api  */
    protected $sourceModel;

    /** @var Registry  */
    public $registry;

    public function __construct(
        MagentoImport $importModel,
        Registry $registry
    ) {
        $this->importModel = $importModel;
        $this->registry = $registry;
    }

    /**
     * @inheritdoc
     */
    public function saveProducts($data = []) {
        $this->importModel->setData(array_merge($this->_getDefaultDataset(), $data['options']));
        $source = $this->getSource($data);
        $validateResults = $this->importModel->validateSource($source);
        $this->importModel->importSource();
        $this->createReport();
        $this->handleInvalidate();
        return [$this->getReport()];
    }

    /** Get default import options
     * @return array
     */
    private function _getDefaultDataset() {
        return [
            'entity' => 'catalog_product',
            'behavior' => 'append',
            MagentoImport::FIELD_NAME_VALIDATION_STRATEGY => ProcessingErrorAggregatorInterface::VALIDATION_STRATEGY_SKIP_ERRORS,
            'allowed_error_count' => '10',
            '_import_field_separator' => ',',
            '_import_multiple_value_separator' => ','
        ];
    }

    /**
     * Creating API adapter for import
     *
     * @param array $data
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \SwayOleg\ApiBulk\Model\Import\Adapter|\Magento\ImportExport\Model\Import\AbstractSource
     */
    protected function getSource($data) {
        return Adapter::findAdapterFor('api', $data['products'], null);
    }

    /** Creates report if needed and return it */
    protected function getReport() {
         if (!$this->report) {
             $this->createReport();
         }
         return $this->report;
    }

    /**
     * Creates report for response
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function createReport() {
        $errorAggregator = $this->importModel->getErrorAggregator();
        if ($errorAggregator->hasToBeTerminated()) {
            if ($errorAggregator->hasFatalExceptions()) {
                foreach ($errorAggregator->getAllErrors() as $error) {
                    if ($error->getErrorLevel() == ProcessingError::ERROR_LEVEL_CRITICAL ) {
                        $error =  $error->getErrorCode() . ': ' .$error->getErrorMessage() . ' - ' . $error->getErrorDescription();
                        $this->report['errors'][] = $error;
                    }
                }
            } else {
                $this->report['errors'][] = __('Maximum error count has been reached or system error is occurred!');
            }
        } else {
            $this->report['messages'][] = __('Import successfully done');
        }
        if ($reportFromRegistry = $this->registry->registry(ImportObserver::REG_KEY)) {
            $this->report = array_merge($this->report, $reportFromRegistry);
        }

    }

    /** Invalidates indexes if no import errors
     * @return void
     */
    protected function handleInvalidate() {
        $report = $this->getReport();
        if (!isset($report['errors']) || !$report['errors']) {
            $this->importModel->invalidateIndex();
        }
    }
}