<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\ViewModel;

use Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface;
use Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface;
use Endora\ExpertSenderCdp\Api\Data\TaskInterface;
use Endora\ExpertSenderCdp\Api\TaskRepositoryInterface;
use Endora\ExpertSenderCdp\Helper\ConsentFormElementHelper;
use Endora\ExpertSenderCdp\Model\Api\Dto\Customer\Consent;
use Endora\ExpertSenderCdp\Model\FormsConfig;
use Endora\ExpertSenderCdp\Model\Consent\FormElement\Form;
use Endora\ExpertSenderCdp\Service\CustomerService;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Psr\Log\LoggerInterface;

class CustomerConsents implements ArgumentInterface
{
    /**
     * @var string
     */
    protected $form;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Endora\ExpertSenderCdp\Model\Api\Dto\Customer\Consent[]
     */
    protected $customerConsents;

    /**
     * @var \Endora\ExpertSenderCdp\Model\FormsConfig
     */
    protected $formsConfig;

    /**
     * @var \Endora\ExpertSenderCdp\Helper\ConsentFormElementHelper $helper
     */
    protected $helper;

    /**
     * @var \Endora\ExpertSenderCdp\Service\CustomerService
     */
    protected $customerService;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Endora\ExpertSenderCdp\Api\TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilderFactory
     */
    protected $searchCriteriaBuilderFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Api\Data\TaskInterface[]
     */
    protected $customerTasks;

    /**
     * @var \Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface
     */
    protected $consentRepository;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Endora\ExpertSenderCdp\Model\FormsConfig $formsConfig
     * @param \Endora\ExpertSenderCdp\Helper\ConsentFormElementHelper $helper
     * @param \Endora\ExpertSenderCdp\Service\CustomerService $customerService
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Endora\ExpertSenderCdp\Api\TaskRepositoryInterface $taskRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param \Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface $consentRepository
     * @param string $form
     */
    public function __construct(
        Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        FormsConfig $formsConfig,
        ConsentFormElementHelper $helper,
        CustomerService $customerService,
        LoggerInterface $logger,
        TaskRepositoryInterface $taskRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        ConsentRepositoryInterface $consentRepository,
        string $form = ''
    ) {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->formsConfig = $formsConfig;
        $this->helper = $helper;
        $this->customerService = $customerService;
        $this->logger = $logger;
        $this->taskRepository = $taskRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->consentRepository = $consentRepository;
        $this->form = $form;
    }

    /**
     * @return \Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface[]
     */
    public function getFormElements()
    {
        return $this->helper->getFormElements($this->form)
            ->getItems();
    }

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface $formElement
     * @return string
     */
    public function getElementId(ConsentFormElementInterface $formElement)
    {
        return ConsentFormElementInterface::INPUT_NAME . '_' . implode(',', $formElement->getConsentIds());
    }

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface $formElement
     * @return bool
     */
    public function isAgreed(ConsentFormElementInterface $formElement)
    {
        if (Form::PROFILE_EDIT_FORM !== $this->form) {
            return false;
        }

        foreach ($formElement->getConsentIds() as $consentId) {
            $consentModel = $this->consentRepository->get((int) $consentId);
            $ecdpConsentId = $consentModel->getEcdpId();
            $consentTaskStatus = $this->getConsentTaskStatus($ecdpConsentId);

            if (null !== $consentTaskStatus && Consent::VALUE_FALSE === $consentTaskStatus) {
                return false;
            }

            $assignedConsent = array_filter($this->getCustomerConsents(), function ($consent) use ($ecdpConsentId) {
                /** @var \Endora\ExpertSenderCdp\Model\Api\Dto\Customer\Consent $consent */
                return ((int) $ecdpConsentId === $consent->getId() && Consent::VALUE_TRUE === $consent->getValue());
            });

            if (empty($assignedConsent)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function getTextBeforeConsents()
    {
        if (!in_array($this->form, [Form::NEWSLETTER_FORM, FORM::PROFILE_EDIT_FORM])) {
            return $this->formsConfig->getTextBeforeConsents();
        }
        
        return '';
    }

    /**
     * @return \Endora\ExpertSenderCdp\Model\Api\Dto\Customer\Consent[]
     */
    public function getCustomerConsents()
    {
        if (!isset($this->customerConsents)) {
            $customerData = $this->customerSession->getCustomerData();

            if (!empty($customerData->getEmail())) {
                try {
                    $customerDto = $this->customerService->getCustomer(
                        $customerData->getEmail(),
                        $customerData->getStoreId()
                    );
                    $this->customerConsents = $customerDto->getConsentsData()->getConsents();
                } catch (\Exception $ex) {
                    $this->logger->error($ex->getMessage());
                    $this->customerConsents = [];
                }
            }
        }

        return $this->customerConsents;
    }

    /**
     * @return \Endora\ExpertSenderCdp\Api\Data\TaskInterface[]
     */
    public function getCustomerTasks()
    {
        if (!isset($this->customerTasks)) {
            $searchCriteria = $this->searchCriteriaBuilderFactory->create()
                ->addFilter(TaskInterface::OBJECT_ID, $this->customerSession->getCustomerId())
                ->addFilter(
                    TaskInterface::TASK,
                    [TaskInterface::TASK_CUSTOMER_ADD, TaskInterface::TASK_CUSTOMER_UPDATE],
                    'in'
                )->create();
            
            $this->customerTasks = $this->taskRepository->getList($searchCriteria)->getItems();
        }

        return $this->customerTasks;
    }

    /**
     * @param int $consentId
     * @return string|null
     */
    public function getConsentTaskStatus(int $consentId)
    {
        foreach ($this->getCustomerTasks() as $task) {
            $customData = $task->getCustomData();

            if (null !== $customData && isset($customData['consentsData'])
                && isset($customData['consentsData']['consents'])
            ) {
                foreach ($customData['consentsData']['consents'] as $id => $value) {
                    if ($id === $consentId) {
                        return $value;
                    }
                }
            }
        }

        return null;
    }
}
