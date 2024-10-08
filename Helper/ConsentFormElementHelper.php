<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Helper;

use ExpertSender\Ecdp\Api\ConsentFormElementRepositoryInterface;
use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Store\Model\StoreManagerInterface;

class ConsentFormElementHelper
{
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilderFactory
     */
    protected $searchCriteriaBuilderFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Api\SortOrderBuilder
     */
    protected $sortOrderBuilder;

    /**
     * @var \ExpertSender\Ecdp\Api\ConsentFormElementRepositoryInterface
     */
    protected $formElementRepository;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder
     * @param \ExpertSender\Ecdp\Api\ConsentFormElementRepositoryInterface $formElementRepository
     */
    public function __construct(
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        StoreManagerInterface $storeManager,
        SortOrderBuilder $sortOrderBuilder,
        ConsentFormElementRepositoryInterface $formElementRepository
    ) {
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->storeManager = $storeManager;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->formElementRepository = $formElementRepository;
    }

    public function getFormElements(string $form)
    {
        $sortOrder = $this->sortOrderBuilder->setDirection('ASC')
        ->setField(ConsentFormElementInterface::SORT_ORDER)
            ->create();

        $searchCriteria = $this->searchCriteriaBuilderFactory->create()
            ->addFilter(ConsentFormElementInterface::FORM, $form)
            ->addFilter(
                ConsentFormElementInterface::STORE,
                (int) $this->storeManager->getStore()->getId()
            )->addFilter(ConsentFormElementInterface::ENABLED, 1)
            ->setSortOrders([$sortOrder])
            ->create();

        return $this->formElementRepository->getList($searchCriteria);
    }
}
