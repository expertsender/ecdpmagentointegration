<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Controller\Adminhtml\FieldMapping;

use Endora\ExpertSenderCdp\Api\FieldMappingRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    public const ADMIN_RESOURCE = 'Endora_ExpertSenderCdp::field_mapping_delete';

    /**
     * @var \Endora\ExpertSenderCdp\Api\FieldMappingRepositoryInterface
     */
    protected $fieldMappingRepository;

    /**
     * @param \Endora\ExpertSenderCdp\Api\FieldMappingRepositoryInterface $fieldMappingRepository
     */
    public function __construct(FieldMappingRepositoryInterface $fieldMappingRepository, Context $context)
    {
        parent::__construct($context);
        $this->fieldMappingRepository = $fieldMappingRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create()->setPath('*/*/');
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $this->fieldMappingRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('Attribute mapping has been deleted.'));
            } catch (\Exception $ex) {
                $this->messageManager->addErrorMessage($ex->getMessage());
            }

            return $resultRedirect;
        }

        $this->messageManager->addErrorMessage(__('Attribute mapping no longer exists.'));
        return $resultRedirect;
    }
}
