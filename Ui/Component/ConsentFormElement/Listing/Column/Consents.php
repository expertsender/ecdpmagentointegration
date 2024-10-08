<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Ui\Component\ConsentFormElement\Listing\Column;

use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface;
use ExpertSender\Ecdp\Model\ResourceModel\Consent\Collection;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Consents extends Column
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param \ExpertSender\Ecdp\Model\ResourceModel\Consent\Collection $consentCollection
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        Collection $consentCollection,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->options = $consentCollection->toOptionHash();
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $consents = [];

                foreach (explode(',', $item[ConsentFormElementInterface::CONSENT_IDS]) as $consentId) {
                    $consents[] = $this->options[(int) $consentId];
                }

                $item[ConsentFormElementInterface::CONSENT_IDS] = implode(', ', $consents);
            }
        }

        return $dataSource;
    }
}
