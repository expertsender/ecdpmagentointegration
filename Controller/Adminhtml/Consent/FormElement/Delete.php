<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Controller\Adminhtml\Consent\FormElement;

use ExpertSender\Ecdp\Api\ConsentFormElementRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    public const ADMIN_RESOURCE = 'ExpertSender_Ecdp::consent_form_element_delete';

    /**
     * @var \ExpertSender\Ecdp\Api\ConsentFormElementRepositoryInterface
     */
    protected $consentFormElementRepository;

    /**
     * @param \ExpertSender\Ecdp\Api\ConsentFormElementRepositoryInterface $consentFormElementRepository
     */
    public function __construct(
        ConsentFormElementRepositoryInterface $consentFormElementRepository,
        Context $context
    ) {
        parent::__construct($context);
        $this->consentFormElementRepository = $consentFormElementRepository;
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
                $this->consentFormElementRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('Consent Form Element has been deleted.'));
            } catch (\Exception $ex) {
                $this->messageManager->addErrorMessage($ex->getMessage());
            }

            return $resultRedirect;
        }

        $this->messageManager->addErrorMessage(__('Consent Form Element no longer exists.'));
        return $resultRedirect;
    }
}
