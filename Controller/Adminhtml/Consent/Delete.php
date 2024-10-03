<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Controller\Adminhtml\Consent;

use Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    public const ADMIN_RESOURCE = 'Endora_ExpertSenderCdp::consent_delete';

    /**
     * @var \Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface
     */
    protected $consentRepository;

    /**
     * @param \Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface $consentRepository
     */
    public function __construct(ConsentRepositoryInterface $consentRepository, Context $context)
    {
        parent::__construct($context);
        $this->consentRepository = $consentRepository;
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
                $this->consentRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('Consent has been deleted.'));
            } catch (\Exception $ex) {
                $this->messageManager->addErrorMessage($ex->getMessage());
            }

            return $resultRedirect;
        }

        $this->messageManager->addErrorMessage(__('Consent no longer exists.'));
        return $resultRedirect;
    }
}
