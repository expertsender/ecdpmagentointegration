<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Ui\Component\FieldMapping\Form\Column\Entity;

use ExpertSender\Ecdp\Model\FieldMapping\Entity;
use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \ExpertSender\Ecdp\Model\FieldMapping\Entity
     */
    protected $fieldMappingEntity;

    /**
     * @param \ExpertSender\Ecdp\Model\FieldMapping\Entity $fieldMappingEntity
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
