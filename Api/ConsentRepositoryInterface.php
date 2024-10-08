<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Api;

use ExpertSender\Ecdp\Api\Data\ConsentInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface ConsentRepositoryInterface
{
    /**
     * @param int $id
     * @return \ExpertSender\Ecdp\Api\Data\ConsentInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id);

    /**
     * @param \ExpertSender\Ecdp\Api\Data\ConsentInterface $consent
     * @return \ExpertSender\Ecdp\Api\Data\ConsentInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(ConsentInterface $consent);

    /**
     * @param \ExpertSender\Ecdp\Api\Data\ConsentInterface $consent
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(ConsentInterface $consent);

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $id);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface
     * @return \ExpertSender\Ecdp\Api\ConsentSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @return \ExpertSender\Ecdp\Api\ConsentSearchResultInterface
     */
    public function getAll();
}
