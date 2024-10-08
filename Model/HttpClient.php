<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model;

use ExpertSender\Ecdp\Exception\EESException;
use ExpertSender\Ecdp\Model\Api\ApiResponse;
use \Magento\Framework\HTTP\Client\Curl;
use \ExpertSender\Ecdp\Model\Config;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

/**
 * @property \CurlHandle $_ch
 */
class HttpClient extends Curl
{
    /**
     * @var \ExpertSender\Ecdp\Model\Config
     */
    protected $config;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $serializer;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var int
     */
    protected $sslVersion;

    /**
     * @param \ExpertSender\Ecdp\Model\Config $config
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Psr\Log\LoggerInterface $logger
     * @param int|null $sslVersion
     */
    public function __construct(
        Config $config,
        SerializerInterface $serializer,
        LoggerInterface $logger,
        ?int $sslVersion = null
    ) {
        $this->sslVersion = $sslVersion;
        $this->config = $config;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param int $storeId
     * @param array $headers
     * @return \ExpertSender\Ecdp\Model\Api\ApiResponse
     * @throws \ExpertSender\Ecdp\Exception\EESException
     */
    public function makePost(string $endpoint, array $data, int $storeId, array $headers = [])
    {
        $data = $this->serializer->serialize($data);
        $headers['Content-Length'] = strlen($data);
        $this->prepareHeaders($storeId, $headers);

        try {
            $this->makeRequest('POST', $endpoint, $data);
        } catch (\Exception $ex) {
            throw new EESException(__('Post request exception: %1', $ex->getMessage()));
        }

        return new ApiResponse($this->getStatus(), $this->getBody());
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param int $storeId
     * @param array $headers
     * @return \ExpertSender\Ecdp\Model\Api\ApiResponse
     * @throws \ExpertSender\Ecdp\Exception\EESException
     */
    public function makePut(string $endpoint, array $data, int $storeId, array $headers = [])
    {
        $data = $this->serializer->serialize($data);
        $headers['Content-Length'] = strlen($data);
        $this->prepareHeaders($storeId, $headers);

        try {
            $this->makeRequest('PUT', $endpoint, $data);
        } catch (\Exception $ex) {
            throw new EESException(__('Put request exception: %1', $ex->getMessage()));
        }

        return new ApiResponse($this->getStatus(), $this->getBody());
    }

    /**
     * @param string $endpoint
     * @param int $storeId
     * @param array $headers
     * @return \ExpertSender\Ecdp\Model\Api\ApiResponse
     * @throws \ExpertSender\Ecdp\Exception\EESException
     */
    public function makeGet(string $endpoint, int $storeId, array $headers = [])
    {
        $this->prepareHeaders($storeId, $headers);

        try {
            $this->makeRequest('GET', $endpoint);
        } catch (\Exception $ex) {
            throw new EESException(__('Get request exception: %1', $ex->getMessage()));
        }

        return new ApiResponse($this->getStatus(), $this->getBody());
    }

    /**
     * @param int $storeId
     * @param array $headers
     * @return void
     */
    protected function prepareHeaders(int $storeId, array $headers = [])
    {
        $defaultHeaders = [
            'Content-Type' => 'application/json',
            'x-api-key' => $this->config->getApiKey($storeId)
        ];

        $headers = array_merge($defaultHeaders, $headers);

        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function parseHeaders($ch, $data)
    {
        if ($this->_headerCount == 0) {
            $line = explode(" ", trim($data), 3);
            if (count($line) < 2) {
                $this->doError("Invalid response line returned from server: " . $data);
            }
            $this->_responseStatus = (int)$line[1];
        } else {
            $name = $value = '';
            $out = explode(": ", trim($data), 2);
            if (count($out) == 2) {
                $name = $out[0];
                $value = $out[1];
            }

            if (strlen($name)) {
                if ("Set-Cookie" == $name) {
                    if (!isset($this->_responseHeaders[$name])) {
                        $this->_responseHeaders[$name] = [];
                    }
                    $this->_responseHeaders[$name][] = $value;
                } else {
                    $this->_responseHeaders[$name] = $value;
                }
            }
        }
        $this->_headerCount++;

        return strlen($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function makeRequest($method, $uri, $params = [])
    {
        $this->logger->debug(
            'ExpertSender API Request:',
            [
                'method' => $method,
                'uri' => $uri,
                'params' => is_array($params) ? $params : $this->serializer->unserialize($params),
            ]
        );

        $this->_ch = curl_init();
        $this->curlOption(CURLOPT_URL, $uri);

        if ('POST' === $method) {
            $this->curlOption(CURLOPT_POST, 1);
            $this->curlOption(CURLOPT_POSTFIELDS, is_array($params) ? http_build_query($params) : $params);
        } elseif ('GET' === $method) {
            $this->curlOption(CURLOPT_HTTPGET, 1);
        } else {
            $this->curlOption(CURLOPT_CUSTOMREQUEST, $method);

            if ('PUT' === $method) {
                $this->curlOption(CURLOPT_POSTFIELDS, is_array($params) ? http_build_query($params) : $params);
            }
        }

        if (count($this->_headers)) {
            $heads = [];
            foreach ($this->_headers as $k => $v) {
                $heads[] = $k . ': ' . $v;
            }
            $this->curlOption(CURLOPT_HTTPHEADER, $heads);
        }

        if (count($this->_cookies)) {
            $cookies = [];
            foreach ($this->_cookies as $k => $v) {
                $cookies[] = "{$k}={$v}";
            }
            $this->curlOption(CURLOPT_COOKIE, implode(";", $cookies));
        }

        if ($this->_timeout) {
            $this->curlOption(CURLOPT_TIMEOUT, $this->_timeout);
        }

        if ($this->_port != 80) {
            $this->curlOption(CURLOPT_PORT, $this->_port);
        }

        $this->curlOption(CURLOPT_RETURNTRANSFER, 1);
        $this->curlOption(CURLOPT_HEADERFUNCTION, [$this, 'parseHeaders']);
        if ($this->sslVersion !== null) {
            $this->curlOption(CURLOPT_SSLVERSION, $this->sslVersion);
        }

        if (count($this->_curlUserOptions)) {
            foreach ($this->_curlUserOptions as $k => $v) {
                $this->curlOption($k, $v);
            }
        }

        $this->curlOption(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_NONE);

        $this->_headerCount = 0;
        $this->_responseHeaders = [];
        $this->_responseBody = curl_exec($this->_ch);

        $err = curl_errno($this->_ch);
        if ($err) {
            $this->doError(curl_error($this->_ch));
        }
        curl_close($this->_ch);

        $this->logger->debug(
            'ExpertSender API Response:',
            [
                'responseCode' => $this->getStatus(),
                'response' => $this->getBody()
            ]
        );
    }
}
