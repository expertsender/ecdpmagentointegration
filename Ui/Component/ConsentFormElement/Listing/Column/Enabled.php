<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Ui\Component\ConsentFormElement\Listing\Column;

use Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Enabled extends Column
{
    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[ConsentFormElementInterface::ENABLED] = $item[ConsentFormElementInterface::ENABLED] ?
                    __('Yes') : __('No');
            }
        }

        return $dataSource;
    }
}
