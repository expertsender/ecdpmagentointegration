<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Controller\Adminhtml\Consent;

use Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface;
use Endora\ExpertSenderCdp\Model\ResourceModel\Consent\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    public const ADMIN_RESOURCE = 'Endora_ExpertSenderCdp::consent_delete';

    /**
     * @var \Endora\ExpertSenderCdp\Model\ResourceModel\Consent\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface
     */
    protected $consentRepository;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Endora\ExpertSenderCdp\Model\ResourceModel\Consent\CollectionFactory $collectionFactory
     * @param \Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface $consentRepository
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        ConsentRepositoryInterface $consentRepository,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->consentRepository = $consentRepository;
        $this->filter = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $deleted = 0;

        foreach ($collection->getItems() as $consent) {
            $this->consentRepository->delete($consent);
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
