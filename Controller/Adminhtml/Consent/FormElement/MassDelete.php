<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Controller\Adminhtml\Consent\FormElement;

use Endora\ExpertSenderCdp\Api\ConsentFormElementRepositoryInterface;
use Endora\ExpertSenderCdp\Model\ResourceModel\Consent\FormElement\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    public const ADMIN_RESOURCE = 'Endora_ExpertSenderCdp::consent_form_element_delete';

    /**
     * @var \Endora\ExpertSenderCdp\Model\ResourceModel\Consent\FormElement\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Api\ConsentFormElementRepositoryInterface
     */
    protected $consentFormElementRepository;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Endora\ExpertSenderCdp\Model\ResourceModel\Consent\FormElement\CollectionFactory $collectionFactory
     * @param \Endora\ExpertSenderCdp\Api\ConsentFormElementRepositoryInterface $consentFormElementRepository
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        ConsentFormElementRepositoryInterface $consentFormElementRepository,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->consentFormElementRepository = $consentFormElementRepository;
        $this->filter = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $deleted = 0;

        foreach ($collection->getItems() as $consentFormElement) {
            $this->consentFormElementRepository->delete($consentFormElement);
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
