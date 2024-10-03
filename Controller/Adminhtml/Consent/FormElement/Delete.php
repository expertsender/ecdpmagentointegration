<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Controller\Adminhtml\Consent\FormElement;

use Endora\ExpertSenderCdp\Api\ConsentFormElementRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    public const ADMIN_RESOURCE = 'Endora_ExpertSenderCdp::consent_form_element_delete';

    /**
     * @var \Endora\ExpertSenderCdp\Api\ConsentFormElementRepositoryInterface
     */
    protected $consentFormElementRepository;

    /**
     * @param \Endora\ExpertSenderCdp\Api\ConsentFormElementRepositoryInterface $consentFormElementRepository
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
