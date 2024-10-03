<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Controller\Adminhtml\FieldMapping;

use Endora\ExpertSenderCdp\Api\Data\FieldMappingInterfaceFactory;
use Endora\ExpertSenderCdp\Api\FieldMappingRepositoryInterface;
use Endora\ExpertSenderCdp\Model\FieldMapping\Entity;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    public const ADMIN_RESOURCE = 'Endora_ExpertSenderCdp::field_mapping_edit';

    /**
     * @var \Endora\ExpertSenderCdp\Api\Data\FieldMappingInterfaceFactory
     */
    protected $fieldMappingFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Api\FieldMappingRepositoryInterface
     */
    protected $fieldMappingRepository;

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\FieldMappingInterfaceFactory $fieldMappingFactory
     * @param \Endora\ExpertSenderCdp\Api\FieldMappingRepositoryInterface $fieldMappingRepository
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        Context $context,
        FieldMappingInterfaceFactory $fieldMappingFactory,
        FieldMappingRepositoryInterface $fieldMappingRepository
    ) {
        parent::__construct($context);
        $this->fieldMappingFactory = $fieldMappingFactory;
        $this->fieldMappingRepository = $fieldMappingRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $data = $this->processEcdpField($data);

        if ($data) {
            $id = $this->getRequest()->getParam('id');

            if ($id) {
                try {
                    $fieldMapping = $this->fieldMappingRepository->get($id);
                } catch (NoSuchEntityException $ex) {
                    $this->messageManager->addErrorMessage(__('Attribute mapping no longer exists.'));

                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $fieldMapping = $this->fieldMappingFactory->create();
            }

            /** @var \Endora\ExpertSenderCdp\Api\Data\FieldMappingInterface $fieldMapping */
            $fieldMapping->setData($data);

            try {
                $this->fieldMappingRepository->save($fieldMapping);
                $this->messageManager->addSuccessMessage(__('Attribute mapping has been saved.'));

                return $resultRedirect->setPath('*/*/');
            } catch (AlreadyExistsException $ex) {
                $this->messageManager->addErrorMessage(
                    __(
                        'Field mapping with ECDP attribute "%1" already exists for store ID "%2"',
                        $fieldMapping->getEcdpField(),
                        $fieldMapping->getStore()
                    )
                );
            } catch (LocalizedException $ex) {
                $this->messageManager->addErrorMessage($ex->getMessage());
            } catch (\Exception $ex) {
                $this->messageManager->addExceptionMessage(
                    $ex,
                    __('Something went wrong while saving attribute mapping.')
                );
            }

            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE)
            && strtolower($this->getRequest()->getMethod()) === 'post';
    }

    /**
     * @param array $data
     * @return array
     */
    protected function processEcdpField(array $data)
    {
        $entity = (int) $data['entity'];

        if (Entity::CUSTOMER_ENTITY === $entity) {
            $data['ecdp_field'] = $data['ecdp_customer_field'];
        } elseif (Entity::PRODUCT_ENTITY === $entity) {
            $data['ecdp_field'] = $data['ecdp_product_field'];
        } elseif (Entity::ORDER_ENTITY === $entity) {
            $data['ecdp_field'] = $data['ecdp_order_field'];
        } else {
            $data['ecdp_field'] = null;
        }

        return $data;
    }
}
