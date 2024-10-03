<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Api;

use Endora\ExpertSenderCdp\Api\Data\ConsentInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface ConsentRepositoryInterface
{
    /**
     * @param int $id
     * @return \Endora\ExpertSenderCdp\Api\Data\ConsentInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id);

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\ConsentInterface $consent
     * @return \Endora\ExpertSenderCdp\Api\Data\ConsentInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(ConsentInterface $consent);

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\ConsentInterface $consent
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
     * @return \Endora\ExpertSenderCdp\Api\ConsentSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @return \Endora\ExpertSenderCdp\Api\ConsentSearchResultInterface
     */
    public function getAll();
}
