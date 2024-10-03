<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Ui\Component\FieldMapping\Form\Column\Entity;

use Endora\ExpertSenderCdp\Model\FieldMapping\Entity;
use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Endora\ExpertSenderCdp\Model\FieldMapping\Entity
     */
    protected $fieldMappingEntity;

    /**
     * @param \Endora\ExpertSenderCdp\Model\FieldMapping\Entity $fieldMappingEntity
     */
    public function __construct(Entity $fieldMappingEntity)
    {
        $this->fieldMappingEntity = $fieldMappingEntity;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (empty($this->options)) {
            $this->options = $this->fieldMappingEntity->toOptionArray();
        }

        return $this->options;
    }
}
