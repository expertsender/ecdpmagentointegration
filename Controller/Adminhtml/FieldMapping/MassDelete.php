<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Controller\Adminhtml\FieldMapping;

use ExpertSender\Ecdp\Api\FieldMappingRepositoryInterface;
use ExpertSender\Ecdp\Model\ResourceModel\FieldMapping\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    public const ADMIN_RESOURCE = 'ExpertSender_Ecdp::field_mapping_delete';

    /**
     * @var \ExpertSender\Ecdp\Model\ResourceModel\FieldMapping\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \ExpertSender\Ecdp\Api\FieldMappingRepositoryInterface
     */
    protected $fieldMappingRepository;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \ExpertSender\Ecdp\Model\ResourceModel\FieldMapping\CollectionFactory $collectionFactory
     * @param \ExpertSender\Ecdp\Api\FieldMappingRepositoryInterface $fieldMappingRepository
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        FieldMappingRepositoryInterface $fieldMappingRepository,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->fieldMappingRepository = $fieldMappingRepository;
        $this->filter = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $deleted = 0;

        foreach ($collection->getItems() as $fieldMapping) {
            $this->fieldMappingRepository->delete($fieldMapping);
            $deleted++;
        }

        if ($deleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) has been deleted.', $deleted)
            );
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE)
            && strtolower($this->getRequest()->getMethod()) === 'post';
    }
}
