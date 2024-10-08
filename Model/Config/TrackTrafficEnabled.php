<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\Config;

use Magento\Framework\App\Config\Value;

class TrackTrafficEnabled extends Value
{
    /**
     * {@inheritdoc}
     */
    public function afterSave()
    {
        if ($this->isValueChanged()) {
            $this->cacheTypeList->cleanType(\Magento\PageCache\Model\Cache\Type::TYPE_IDENTIFIER);
        }
        
        return parent::afterSave();
    }
}
