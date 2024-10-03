<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Helper;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Store\Model\StoreManagerInterface;

class ControllerHelper
{
    private const SCOPE_STORE_PARAM = 'store';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $redirectFactory;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
     * @return bool|\Magento\Framework\Controller\Result\Redirect
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        RedirectFactory $redirectFactory
    ) {
        $this->storeManager = $storeManager;
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool|\Magento\Framework\Controller\Result\Redirect
     */
    public function validateScopedRequest(RequestInterface $request)
    {
        if (null === $request->getParam(self::SCOPE_STORE_PARAM)) {
            $params = [
                self::SCOPE_STORE_PARAM => $this->storeManager->getDefaultStoreView()->getId()
            ];

            foreach ($request->getParams() as $key => $value) {
                $params[$key] = $value;
            }

            return $this->redirectFactory->create()->setPath('*/*/*', $params);
        }

        return true;
    }
}
