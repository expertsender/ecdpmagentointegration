<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Ui\Component\Consent\Form\Column\EcdpId;

use Endora\ExpertSenderCdp\Service\SettingsService;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Psr\Log\LoggerInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Endora\ExpertSenderCdp\Service\SettingsService
     */
    protected $settingsService;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @param \Endora\ExpertSenderCdp\Service\SettingsService $settingsService
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        SettingsService $settingsService,
        LoggerInterface $logger,
        RequestInterface $request
    ) {
        $this->settingsService = $settingsService;
        $this->logger = $logger;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (empty($this->options)) {
            try {
                $options = $this->settingsService->getCustomerConsentsOptions(
                    (int) $this->request->getParam('store')
                );

                $this->options = array_map(function ($consent) {
                    $consent['value'] = $consent['value'] . '_' . $consent['label'];
                    return $consent;
                }, $options);
            } catch (\Exception $ex) {
                $this->logger->error($ex->getMessage());
                $this->options = [];
            }
        }

        return $this->options;
    }
}