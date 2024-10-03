<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model;

use Endora\ExpertSenderCdp\Api\ConsentFormElementRepositoryInterface;
use Endora\ExpertSenderCdp\Api\Data\ConsentInterface;
use Endora\ExpertSenderCdp\Api\Data\ConsentInterfaceFactory;
use Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface;
use Endora\ExpertSenderCdp\Api\ConsentSearchResultInterfaceFactory;
use Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface;
use Endora\ExpertSenderCdp\Model\ResourceModel\Consent as ConsentResource;
use Endora\ExpertSenderCdp\Model\ResourceModel\Consent\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class ConsentRepository implements ConsentRepositoryInterface
{
    /**
     * @var \Endora\ExpertSenderCdp\Api\Data\ConsentInterface
     */
    protected $consentFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Model\ResourceModel\Consent
     */
    protected $resource;

    /**
     * @var \Endora\ExpertSenderCdp\Api\ConsentSearchResultInterfaceFactory
     */
    protected $consentSearchResultFactory;

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
     * @var \Endora\ExpertSenderCdp\Api\ConsentFormElementRepositoryInterface
     */
    protected $consentFormElementRepository;

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\ConsentInterfaceFactory $consentFactory
     * @param \Endora\ExpertSenderCdp\Model\ResourceModel\Consent $resource
     * @param \Endora\ExpertSenderCdp\Api\ConsentSearchResultInterfaceFactory $consentSearchResultFactory
     * @param \Endora\ExpertSenderCdp\Model\ResourceModel\Consent\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessor $collectionProcessor
     * @param \Endora\ExpertSenderCdp\Api\ConsentFormElementRepositoryInterface $consentFormElementRepository
     */
    public function __construct(
        ConsentInterfaceFactory $consentFactory,
        ConsentResource $resource,
        ConsentSearchResultInterfaceFactory $consentSearchResultFactory,
        CollectionFactory $collectionFactory,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        CollectionProcessor $collectionProcessor,
        ConsentFormElementRepositoryInterface $consentFormElementRepository
    ) {
        $this->consentFactory = $consentFactory;
        $this->resource = $resource;
        $this->consentSearchResultFactory = $consentSearchResultFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->consentFormElementRepository = $consentFormElementRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $id)
    {
        $consent = $this->consentFactory->create();
        $consent->load($id);

        if (!$consent->getId()) {
            throw new NoSuchEntityException(__('Consent with ID "%1" does not exist.', $id));
        }

        return $consent;
    }

    /**
     * {@inheritdoc}
     */
    public function save(ConsentInterface $consent)
    {
        try {
            $id = $consent->getId();

            if ($id) {
                $this->get($id);
            }

            $this->resource->save($consent);
        } catch (AlreadyExistsException $ex) {
            throw $ex;
        } catch (NoSuchEntityException $ex) {
            throw $ex;
        } catch (LocalizedException $ex) {
            throw new CouldNotSaveException(__($ex->getMessage()));
        }

        return $consent;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ConsentInterface $consent)
    {
        try {
            $searchCriteria = $this->searchCriteriaBuilderFactory->create()
                ->addFilter(ConsentFormElementInterface::CONSENT_IDS, $consent->getId(), 'finset')
                ->create();
            
            $consentFormElements = $this->consentFormElementRepository->getList($searchCriteria)->getItems();

            foreach ($consentFormElements as $formElement) {
                $consentIds = $formElement->getConsentIds();

                if (count($consentIds) <= 1) {
                    $this->consentFormElementRepository->delete($formElement);
                } else {
                    foreach (array_keys($consentIds, $consent->getId(), true) as $key) {
                        unset($consentIds[$key]);
                    }

                    $formElement->setConsentIds($consentIds);
                    $this->consentFormElementRepository->save($formElement);
                }
            }

            $this->resource->delete($consent);
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
        $searchResults = $this->consentSearchResultFactory->create();
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
    public function getAll()
    {
        $searchCriteria = $this->searchCriteriaBuilderFactory->create()->create();

        return $this->getList($searchCriteria);
    }
}
