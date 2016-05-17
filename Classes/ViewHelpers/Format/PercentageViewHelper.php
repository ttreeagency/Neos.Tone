<?php
namespace Ttree\Neos\Tone\ViewHelpers\Format;

/*
 * This file is part of the Ttree.Neos.Tone package.
 *
 * (c) Build with love by ttree agency - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\I18n\Cldr\Reader\NumbersReader;
use TYPO3\Flow\I18n\Exception as I18nException;
use TYPO3\Flow\I18n\Formatter\NumberFormatter;
use TYPO3\Fluid\Core\ViewHelper\AbstractLocaleAwareViewHelper;
use TYPO3\Fluid\Core\ViewHelper\Exception as ViewHelperException;

/**
 * Convert metric returned by Watson to CSS percentage
 * @api
 */
class PercentageViewHelper extends AbstractLocaleAwareViewHelper
{
    /**
     * @Flow\Inject
     * @var NumberFormatter
     */
    protected $numberFormatter;

    /**
     * @return string The formatted number
     * @api
     */
    public function render()
    {
        $metric = $this->renderChildren();
        return $metric * 100 . '%';
    }
}
