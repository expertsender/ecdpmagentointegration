<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Block;

use ExpertSender\Ecdp\Model\Config;

class TrackingScript extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \ExpertSender\Ecdp\Model\Config
     */
    protected $config;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \ExpertSender\Ecdp\Model\Config $config
     * @param array
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Config $config,
        array $data = []
    ) {
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * @return string|null
     */
    public function getTrackingScript()
    {
        return $this->config->getTrackTrafficScript();
    }

    /**
     * @return bool
     */
    public function getIsTrackingEnabled()
    {
        return $this->config->getTrackTrafficEnabled();
    }
}
