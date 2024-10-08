<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\ViewModel;

use ExpertSender\Ecdp\Api\ConsentRepositoryInterface;
use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface;
use ExpertSender\Ecdp\Api\Data\TaskInterface;
use ExpertSender\Ecdp\Api\TaskRepositoryInterface;
use ExpertSender\Ecdp\Helper\ConsentFormElementHelper;
use ExpertSender\Ecdp\Model\Api\Dto\Customer\Consent;
use ExpertSender\Ecdp\Model\FormsConfig;
use ExpertSender\Ecdp\Model\Consent\FormElement\Form;
use ExpertSender\Ecdp\Service\CustomerService;
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
     * @var \ExpertSender\Ecdp\Model\Api\Dto\Customer\Consent[]
     */
    protected $customerConsents;

    /**
     * @var \ExpertSender\Ecdp\Model\FormsConfig
     */
    protected $formsConfig;

    /**
     * @var \ExpertSender\Ecdp\Helper\ConsentFormElementHelper $helper
     */
    protected $helper;

    /**
     * @var \ExpertSender\Ecdp\Service\CustomerService
     */
    protected $customerService;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \ExpertSender\Ecdp\Api\TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilderFactory
     */
    protected $searchCriteriaBuilderFactory;

    /**
     * @var \ExpertSender\Ecdp\Api\Data\TaskInterface[]
     */
    protected $customerTasks;

    /**
     * @var \ExpertSender\Ecdp\Api\ConsentRepositoryInterface
     */
    protected $consentRepository;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \ExpertSender\Ecdp\Model\FormsConfig $formsConfig
     * @param \ExpertSender\Ecdp\Helper\ConsentFormElementHelper $helper
     * @param \ExpertSender\Ecdp\Service\CustomerService $customerService
     * @param \Psr\Log\LoggerInterface $logger
     * @param \ExpertSender\Ecdp\Api\TaskRepositoryInterface $taskRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param \ExpertSender\Ecdp\Api\ConsentRepositoryInterface $consentRepository
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
     * @return \ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface[]
     */
    public function getFormElements()
    {
        return $this->helper->getFormElements($this->form)
            ->getItems();
    }

    /**
     * @param \ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface $formElement
     * @return string
     */
    public function getElementId(ConsentFormElementInterface $formElement)
    {
        return ConsentFormElementInterface::INPUT_NAME . '_' . implode(',', $formElement->getConsentIds());
    }

    /**
     * @param \ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface $formElement
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
                /** @var \ExpertSender\Ecdp\Model\Api\Dto\Customer\Consent $consent */
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
     * @return \ExpertSender\Ecdp\Model\Api\Dto\Customer\Consent[]
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
     * @return \ExpertSender\Ecdp\Api\Data\TaskInterface[]
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
