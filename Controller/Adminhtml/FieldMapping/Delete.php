<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Controller\Adminhtml\FieldMapping;

use ExpertSender\Ecdp\Api\FieldMappingRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    public const ADMIN_RESOURCE = 'ExpertSender_Ecdp::field_mapping_delete';

    /**
     * @var \ExpertSender\Ecdp\Api\FieldMappingRepositoryInterface
     */
    protected $fieldMappingRepository;

    /**
     * @param \ExpertSender\Ecdp\Api\FieldMappingRepositoryInterface $fieldMappingRepository
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
