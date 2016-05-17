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

/**
 * Tone Analysis Service
 *
 * @Flow\Scope("singleton")
 * @api
 */
class ToneService
{
    /**
     * @Flow\InjectConfiguration(path="api")
     * @var array
     */
    protected $configuration;

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
            $client = new Client($this->configuration['username'], $this->configuration['password']);
            return json_decode($client->analyze($text), true);
        } catch (ConnectException $exception) {
            return null;
        }
    }
}
