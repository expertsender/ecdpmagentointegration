<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Plugin;

use ExpertSender\Ecdp\Model\FormsConfig;
use Magento\Newsletter\Block\Subscribe;

class RemoveDefaultNewsletterBlock
{
    /**
     * @var \ExpertSender\Ecdp\Model\FormsConfig
     */
    protected $formsConfig;

    /**
     * @param \ExpertSender\Ecdp\Model\Config $formsConfig
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
