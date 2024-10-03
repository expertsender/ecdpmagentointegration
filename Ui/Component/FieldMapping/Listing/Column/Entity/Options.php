<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Ui\Component\FieldMapping\Listing\Column\Entity;

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
    protected $entity;

    /**
     * @param \Endora\ExpertSenderCdp\Model\FieldMapping\Entity $entity
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
