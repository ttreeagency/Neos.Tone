<?php
namespace Ttree\Neos\Tone\Eel\Helper;

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
use Ttree\Neos\Tone\Service\ToneService;
use Ttree\Watson\Tone\Client;
use TYPO3\Eel\ProtectedContextAwareInterface;
use TYPO3\Flow\Annotations as Flow;

/**
 * String helpers for Eel contexts
 */
class ToneHelper implements ProtectedContextAwareInterface
{

    /**
     * @var ToneService
     * @Flow\Inject
     */
    protected $toneService;

    /**
     * @param string $value
     * @return string
     */
    public function analyze($value)
    {
        try {
            return json_encode($this->toneService->analyze($value));
        } catch (ConnectException $exception) {
            return null;
        }
    }

    /**
     * All methods are considered safe
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
