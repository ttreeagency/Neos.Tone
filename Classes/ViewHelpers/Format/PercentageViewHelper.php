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

use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Exception as I18nException;
use Neos\Flow\I18n\Formatter\NumberFormatter;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractLocaleAwareViewHelper;
use Neos\FluidAdaptor\Core\ViewHelper\Exception as ViewHelperException;

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
