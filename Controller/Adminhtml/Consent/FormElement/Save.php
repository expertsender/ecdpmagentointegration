<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Controller\Adminhtml\Consent\FormElement;

use ExpertSender\Ecdp\Api\ConsentFormElementRepositoryInterface;
use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface;
use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterfaceFactory;
use ExpertSender\Ecdp\Model\Consent\FormElement\Form;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    public const ADMIN_RESOURCE = 'ExpertSender_Ecdp::consent_form_element_edit';

    /**
     * @var \ExpertSender\Ecdp\Api\Data\ConsentFormElementInterfaceFactory
     */
    protected $consentFormElementFactory;

    /**
     * @var \ExpertSender\Ecdp\Api\ConsentFormElementRepositoryInterface
     */
    protected $consentFormElementRepository;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @param \ExpertSender\Ecdp\Api\Data\ConsentFormElementInterfaceFactory $consentFormElementFactory
     * @param \ExpertSender\Ecdp\Api\ConsentFormElementRepositoryInterface $consentFormElementRepository
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     */
    public function __construct(
        Context $context,
        ConsentFormElementInterfaceFactory $consentFormElementFactory,
        ConsentFormElementRepositoryInterface $consentFormElementRepository,
        TypeListInterface $cacheTypeList
    ) {
        parent::__construct($context);
        $this->consentFormElementFactory = $consentFormElementFactory;
        $this->consentFormElementRepository = $consentFormElementRepository;
        $this->cacheTypeList = $cacheTypeList;
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

            if ($id) {
                try {
                    $consentFormElement = $this->consentFormElementRepository->get($id);
                } catch (NoSuchEntityException $ex) {
                    $this->messageManager->addErrorMessage(__('Consent Form Element no longer exists.'));

                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $consentFormElement = $this->consentFormElementFactory->create();
            }

            if (empty($data[ConsentFormElementInterface::SORT_ORDER])) {
                $data[ConsentFormElementInterface::SORT_ORDER] = null;
            }

            $consentFormElement->setData($data);

            try {
                $this->consentFormElementRepository->save($consentFormElement);
                if (Form::NEWSLETTER_FORM === $consentFormElement->getForm()) {
                    $this->cacheTypeList->cleanType(\Magento\PageCache\Model\Cache\Type::TYPE_IDENTIFIER);
                }
                $this->messageManager->addSuccessMessage(__('Consent Form Element has been saved.'));

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $ex) {
                $this->messageManager->addErrorMessage($ex->getMessage());
            } catch (\Exception $ex) {
                $this->messageManager->addExceptionMessage(
                    $ex,
                    __('Something went wrong while saving Consent Form Element.')
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
