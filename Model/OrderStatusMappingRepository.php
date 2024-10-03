<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model;

use Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterface;
use Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterfaceFactory;
use Endora\ExpertSenderCdp\Api\OrderStatusMappingRepositoryInterface;
use Endora\ExpertSenderCdp\Api\OrderStatusMappingSearchResultInterfaceFactory;
use Endora\ExpertSenderCdp\Model\ResourceModel\OrderStatusMapping as OrderStatusMappingResource;
use Endora\ExpertSenderCdp\Model\ResourceModel\OrderStatusMapping\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class OrderStatusMappingRepository implements OrderStatusMappingRepositoryInterface
{
    /**
     * @var \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterface
     */
    protected $orderStatusMappingFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Model\ResourceModel\OrderStatusMapping
     */
    protected $resource;

    /**
     * @var \Endora\ExpertSenderCdp\Api\OrderStatusMappingSearchResultInterfaceFactory
     */
    protected $orderStatusMappingSearchResultFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Model\ResourceModel\OrderStatusMapping\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilderFactory
     */
    protected $searchCriteriaBuilderFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessor
     */
    protected $collectionProcessor;

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterfaceFactory $orderStatusMappingFactory
     * @param \Endora\ExpertSenderCdp\Model\ResourceModel\OrderStatusMapping $resource
     * @param OrderStatusMappingSearchResultInterfaceFactory $orderStatusMappingSearchResultFactory
     * @param \Endora\ExpertSenderCdp\Model\ResourceModel\OrderStatusMapping\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessor $collectionProcessor
     */
    public function __construct(
        OrderStatusMappingInterfaceFactory $orderStatusMappingFactory,
        OrderStatusMappingResource $resource,
        OrderStatusMappingSearchResultInterfaceFactory $orderStatusMappingSearchResultFactory,
        CollectionFactory $collectionFactory,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        CollectionProcessor $collectionProcessor
    ) {
        $this->orderStatusMappingFactory = $orderStatusMappingFactory;
        $this->resource = $resource;
        $this->orderStatusMappingSearchResultFactory = $orderStatusMappingSearchResultFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $id)
    {
        $orderStatusMapping = $this->orderStatusMappingFactory->create();
        $orderStatusMapping->load($id);

        if (!$orderStatusMapping->getId()) {
            throw new NoSuchEntityException(__('Order Status Mapping with ID "%1" does not exist.', $id));
        }

        return $orderStatusMapping;
    }

    /**
     * {@inheritdoc}
     */
    public function save(OrderStatusMappingInterface $orderStatusMapping)
    {
        try {
            $id = $orderStatusMapping->getId();

            if ($id) {
                $this->get($id);
            }

            $this->resource->save($orderStatusMapping);
        } catch (AlreadyExistsException $ex) {
            throw $ex;
        } catch (NoSuchEntityException $ex) {
            throw $ex;
        } catch (LocalizedException $ex) {
            throw new CouldNotSaveException(__($ex->getMessage()));
        }

        return $orderStatusMapping;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(OrderStatusMappingInterface $orderStatusMapping)
    {
        try {
            $this->resource->delete($orderStatusMapping);
        } catch (\Exception $ex) {
            throw new CouldNotDeleteException(__($ex->getMessage()));
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById(int $id)
    {
        return $this->delete($this->get($id));
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->orderStatusMappingSearchResultFactory->create();
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function getByMagentoStatus(string $orderStatus, int $store)
    {
        $searchCriteria = $this->searchCriteriaBuilderFactory->create()
            ->addFilter(OrderStatusMappingInterface::MAGENTO_ORDER_STATUSES, $orderStatus, 'finset')
            ->addFilter(OrderStatusMappingInterface::STORE, $store)
            ->create();

        $statuses = $this->getList($searchCriteria)->getItems();

        return array_shift($statuses);
    }

    /**
     * {@inheritdoc}
     */
    public function getByEcdpStatus(string $ecdpStatus, int $store)
    {
        $searchCriteria = $this->searchCriteriaBuilderFactory->create()
            ->addFilter(OrderStatusMappingInterface::ECDP_ORDER_STATUS, $ecdpStatus)
            ->addFilter(OrderStatusMappingInterface::STORE, $store)
            ->create();

        $statuses = $this->getList($searchCriteria)->getItems();

        return array_shift($statuses);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $searchCriteria = $this->searchCriteriaBuilderFactory->create()->create();

        return $this->getList($searchCriteria);
    }
}
