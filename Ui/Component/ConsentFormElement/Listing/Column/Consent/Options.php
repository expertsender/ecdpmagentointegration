<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Ui\Component\ConsentFormElement\Listing\Column\Consent;

use ExpertSender\Ecdp\Model\ResourceModel\Consent\Collection;
use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \ExpertSender\Ecdp\Model\ResourceModel\Consent\Collection
     */
    protected $consentCollection;

    /**
     * @param \ExpertSender\Ecdp\Model\ResourceModel\Consent\Collection $consentCollection
     */
    public function __construct(Collection $consentCollection)
    {
        $this->consentCollection = $consentCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (empty($this->options)) {
            $this->options = $this->consentCollection->toOptionArray();
        }

        return $this->options;
    }
}
