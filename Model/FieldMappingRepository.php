<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model;

use Endora\ExpertSenderCdp\Api\Data\FieldMappingInterface;
use Endora\ExpertSenderCdp\Api\Data\FieldMappingInterfaceFactory;
use Endora\ExpertSenderCdp\Api\FieldMappingRepositoryInterface;
use Endora\ExpertSenderCdp\Api\FieldMappingSearchResultInterfaceFactory;
use Endora\ExpertSenderCdp\Model\FieldMapping\Entity;
use Endora\ExpertSenderCdp\Model\ResourceModel\FieldMapping as FieldMappingResource;
use Endora\ExpertSenderCdp\Model\ResourceModel\FieldMapping\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class FieldMappingRepository implements FieldMappingRepositoryInterface
{
    /**
     * @var \Endora\ExpertSenderCdp\Api\Data\FieldMappingInterfaceFactory
     */
    protected $fieldMappingFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Model\ResourceModel\FieldMapping
     */
    protected $resource;

    /**
     * @var \Endora\ExpertSenderCdp\Api\FieldMappingSearchResultInterfaceFactory
     */
    protected $fieldMappingSearchResultFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Model\ResourceModel\FieldMapping\CollectionFactory
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
     * @param \Endora\ExpertSenderCdp\Api\Data\FieldMappingInterfaceFactory $fieldMappingFactory
     * @param \Endora\ExpertSenderCdp\Model\ResourceModel\FieldMapping $resource
     * @param \Endora\ExpertSenderCdp\Api\FieldMappingSearchResultInterfaceFactory $fieldMappingSearchResultFactory
     * @param \Endora\ExpertSenderCdp\Model\ResourceModel\FieldMapping\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessor $collectionProcessor
     */
    public function __construct(
        FieldMappingInterfaceFactory $fieldMappingFactory,
        FieldMappingResource $resource,
        FieldMappingSearchResultInterfaceFactory $fieldMappingSearchResultFactory,
        CollectionFactory $collectionFactory,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        CollectionProcessor $collectionProcessor
    ) {
        $this->fieldMappingFactory = $fieldMappingFactory;
        $this->resource = $resource;
        $this->fieldMappingSearchResultFactory = $fieldMappingSearchResultFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $id)
    {
        $fieldMapping = $this->fieldMappingFactory->create();
        $fieldMapping->load($id);

        if (!$fieldMapping->getId()) {
            throw new NoSuchEntityException(__('Field mapping with ID "%1" does not exist.', $id));
        }

        return $fieldMapping;
    }

    /**
     * {@inheritdoc}
     */
    public function save(FieldMappingInterface $fieldMapping)
    {
        try {
            $id = $fieldMapping->getId();

            if ($id) {
                $this->get($id);
            }

            $this->resource->save($fieldMapping);
        } catch (AlreadyExistsException $ex) {
            throw $ex;
        } catch (NoSuchEntityException $ex) {
            throw $ex;
        } catch (LocalizedException $ex) {
            throw new CouldNotSaveException(__($ex->getMessage()));
        }

        return $fieldMapping;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(FieldMappingInterface $fieldMapping)
    {
        try {
            $this->resource->delete($fieldMapping);
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
        $searchResults = $this->fieldMappingSearchResultFactory->create();
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
    public function getByEntityType(int $entity, int $storeId)
    {
        $searchCriteria = $this->searchCriteriaBuilderFactory->create()
            ->addFilter(FieldMappingInterface::ENTITY, $entity)
            ->addFilter(FieldMappingInterface::STORE, $storeId)
            ->create();

        return $this->getList($searchCriteria);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderFieldMappings(int $storeId)
    {
        return $this->getByEntityType(Entity::ORDER_ENTITY, $storeId);
    }

    /**
     * {@inheritdoc}
     */
    public function getProductFieldMappings(int $storeId)
    {
        return $this->getByEntityType(Entity::PRODUCT_ENTITY, $storeId);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerFieldMappings(int $storeId)
    {
        return $this->getByEntityType(Entity::CUSTOMER_ENTITY, $storeId);
    }
}
