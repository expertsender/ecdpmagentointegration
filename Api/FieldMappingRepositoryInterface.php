<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Api;

use ExpertSender\Ecdp\Api\Data\FieldMappingInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface FieldMappingRepositoryInterface
{
    /**
     * @param int $id
     * @return \ExpertSender\Ecdp\Api\Data\FieldMappingInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id);

    /**
     * @param \ExpertSender\Ecdp\Api\Data\FieldMappingInterface $fieldMapping
     * @return \ExpertSender\Ecdp\Api\Data\FieldMappingInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(FieldMappingInterface $fieldMapping);

    /**
     * @param \ExpertSender\Ecdp\Api\Data\FieldMappingInterface $fieldMapping
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(FieldMappingInterface $fieldMapping);

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $id);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface
     * @return \ExpertSender\Ecdp\Api\FieldMappingSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param int $entity
     * @param int $storeId
     * @return \ExpertSender\Ecdp\Api\FieldMappingSearchResultInterface
     */
    public function getByEntityType(int $entity, int $storeId);

    /**
     * @param int $storeId
     * @return \ExpertSender\Ecdp\Api\FieldMappingSearchResultInterface
     */
    public function getOrderFieldMappings(int $storeId);

    /**
     * @param int $storeId
     * @return \ExpertSender\Ecdp\Api\FieldMappingSearchResultInterface
     */
    public function getProductFieldMappings(int $storeId);

    /**
     * @param int $storeId
     * @return \ExpertSender\Ecdp\Api\FieldMappingSearchResultInterface
     */
    public function getCustomerFieldMappings(int $storeId);
}
