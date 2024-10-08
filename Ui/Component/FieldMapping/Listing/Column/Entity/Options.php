<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Ui\Component\FieldMapping\Listing\Column\Entity;

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
    protected $entity;

    /**
     * @param \ExpertSender\Ecdp\Model\FieldMapping\Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (empty($this->options)) {
            $this->options = $this->entity->toOptionArray();
        }

        return $this->options;
    }
}
