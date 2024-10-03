<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Plugin;

use Endora\ExpertSenderCdp\Model\FormsConfig;
use Magento\Newsletter\Block\Subscribe;

class RemoveDefaultNewsletterBlock
{
    /**
     * @var \Endora\ExpertSenderCdp\Model\FormsConfig
     */
    protected $formsConfig;

    /**
     * @param \Endora\ExpertSenderCdp\Model\Config $formsConfig
     */
    public function __construct(FormsConfig $formsConfig)
    {
        $this->formsConfig = $formsConfig;
    }

    /**
     * @param \Magento\Newsletter\Block\Subscribe $subject
     * @param string $result
     */
    public function afterToHtml(Subscribe $subject, string $result)
    {
        if ($this->formsConfig->useCustomNewsletterForm()) {
            return '';
        }

        return $result;
    }
}
