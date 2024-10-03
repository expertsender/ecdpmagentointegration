<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Exception;

use Magento\Framework\Exception\LocalizedException;

class EESException extends LocalizedException
{
    protected const MESSAGE_PREFIX = 'Endora ExpertSender | ';

    /**
     * {@inheritdoc}
     */
    public function getRawMessage()
    {
        return self::MESSAGE_PREFIX . $this->phrase->getText();
    }
}
