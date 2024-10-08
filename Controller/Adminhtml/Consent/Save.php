<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Controller\Adminhtml\Consent;

use ExpertSender\Ecdp\Api\ConsentRepositoryInterface;
use ExpertSender\Ecdp\Api\Data\ConsentInterface;
use ExpertSender\Ecdp\Api\Data\ConsentInterfaceFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    public const ADMIN_RESOURCE = 'ExpertSender_Ecdp::consent_edit';

    /**
     * @var \ExpertSender\Ecdp\Api\Data\ConsentInterfaceFactory
     */
    protected $consentFactory;

    /**
     * @var \ExpertSender\Ecdp\Api\ConsentRepositoryInterface
     */
    protected $consentRepository;

    /**
     * @param \ExpertSender\Ecdp\Api\Data\ConsentInterfaceFactory $consentFactory
     * @param \ExpertSender\Ecdp\Api\ConsentRepositoryInterface $consentRepository
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        Context $context,
        ConsentInterfaceFactory $consentFactory,
        ConsentRepositoryInterface $consentRepository
    ) {
        parent::__construct($context);
        $this->consentFactory = $consentFactory;
        $this->consentRepository = $consentRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('id');
            list($ecdpId, $ecdpLabel) = explode('_', $data[ConsentInterface::ECDP_ID], 2);
            $data[ConsentInterface::ECDP_ID] = $ecdpId;
            $data[ConsentInterface::ECDP_LABEL] = $ecdpLabel;

            if ($id) {
                try {
                    $consent = $this->consentRepository->get($id);
                } catch (NoSuchEntityException $ex) {
                    $this->messageManager->addErrorMessage(__('Consent no longer exists.'));

                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $consent = $this->consentFactory->create();
            }

            $consent->setData($data);

            try {
                $this->consentRepository->save($consent);
                $this->messageManager->addSuccessMessage(__('Consent has been saved.'));

                return $resultRedirect->setPath('*/*/');
            } catch (AlreadyExistsException $ex) {
                $this->messageManager->addErrorMessage(
                    __('Consent with this name or ExpertSender CDP ID already exists.')
                );
            } catch (LocalizedException $ex) {
                $this->messageManager->addErrorMessage($ex->getMessage());
            } catch (\Exception $ex) {
                $this->messageManager->addExceptionMessage(
                    $ex,
                    __('Something went wrong while saving consent.')
                );
            }

            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }

        return $resultRedirect->setPath('*/*/');
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
