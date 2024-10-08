<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Ui\Component\ConsentFormElement\Form\Column\Consent;

use ExpertSender\Ecdp\Model\ResourceModel\Consent\Collection;
use Magento\Framework\App\RequestInterface;
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
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @param \ExpertSender\Ecdp\Model\ResourceModel\Consent\Collection $consentCollection
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        Collection $consentCollection,
        RequestInterface $request
    ) {
        $this->consentCollection = $consentCollection;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (empty($this->options)) {
            $this->options = $this->consentCollection->getOptionArray(
                (int) $this->request->getParam('store')
            );
        }

        return $this->options;
    }
}
