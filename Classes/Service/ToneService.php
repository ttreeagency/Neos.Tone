<?php
namespace Ttree\Neos\Tone\Service;

/*
 * This file is part of the Ttree.Neos.Tone package.
 *
 * (c) Build with love by ttree agency - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use GuzzleHttp\Exception\ConnectException;
use Html2Text\Html2Text;
use Ttree\Watson\Tone\Client;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cache\Frontend\StringFrontend;
use TYPO3\Flow\Log\SystemLoggerInterface;

/**
 * Tone Analysis Service
 *
 * @Flow\Scope("singleton")
 * @api
 */
class ToneService
{
    /**
     * @var array
     * @Flow\InjectConfiguration(path="api")
     */
    protected $configuration;

    /**
     * @var SystemLoggerInterface
     * @Flow\Inject
     */
    protected $logger;

    /**
     * @var StringFrontend
     * @Flow\Inject
     */
    protected $cache;

    /**
     * @param string $value
     * @return array
     */
    public function analyze($value)
    {
        try {
            $html = new Html2Text($value);
            $text = $html->getText();
            if (trim($text) === '') {
                return null;
            }
            $cacheKey = md5(__CLASS__ . __METHOD__ . $text);
            if ($this->cache->has($cacheKey)) {
                $response = $this->cache->get($cacheKey);
            } else {
                $client = new Client($this->configuration['username'], $this->configuration['password']);
                $response = $client->analyze($text);
                $this->cache->set($cacheKey, $response);
            }
            return json_decode($response, true);
        } catch (ConnectException $exception) {
            $this->logger->logException($exception);
            return null;
        }
    }
}
