<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model;

use ExpertSender\Ecdp\Api\ConsentFormElementRepositoryInterface;
use ExpertSender\Ecdp\Api\Data\ConsentInterface;
use ExpertSender\Ecdp\Api\Data\ConsentInterfaceFactory;
use ExpertSender\Ecdp\Api\ConsentRepositoryInterface;
use ExpertSender\Ecdp\Api\ConsentSearchResultInterfaceFactory;
use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface;
use ExpertSender\Ecdp\Model\ResourceModel\Consent as ConsentResource;
use ExpertSender\Ecdp\Model\ResourceModel\Consent\CollectionFactory;
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
     * @var \ExpertSender\Ecdp\Api\Data\ConsentInterface
     */
    protected $consentFactory;

    /**
     * @var \ExpertSender\Ecdp\Model\ResourceModel\Consent
     */
    protected $resource;

    /**
     * @var \ExpertSender\Ecdp\Api\ConsentSearchResultInterfaceFactory
     */
    protected $consentSearchResultFactory;

    /**
     * @var \ExpertSender\Ecdp\Model\ResourceModel\FieldMapping\CollectionFactory
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
     * @var \ExpertSender\Ecdp\Api\ConsentFormElementRepositoryInterface
     */
    protected $consentFormElementRepository;

    /**
     * @param \ExpertSender\Ecdp\Api\Data\ConsentInterfaceFactory $consentFactory
     * @param \ExpertSender\Ecdp\Model\ResourceModel\Consent $resource
     * @param \ExpertSender\Ecdp\Api\ConsentSearchResultInterfaceFactory $consentSearchResultFactory
     * @param \ExpertSender\Ecdp\Model\ResourceModel\Consent\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessor $collectionProcessor
     * @param \ExpertSender\Ecdp\Api\ConsentFormElementRepositoryInterface $consentFormElementRepository
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
