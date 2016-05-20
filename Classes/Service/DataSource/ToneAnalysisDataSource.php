<?php
namespace Ttree\Neos\Tone\Service\DataSource;

/*
 * This file is part of the Ttree.Neos.Tone package.
 *
 * (c) Build with love by ttree agency - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Ttree\Neos\Tone\Service\ToneService;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Neos\Service\DataSource\AbstractDataSource;
use TYPO3\Neos\View\TypoScriptView;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * Tone Analysis DataSource
 */
class ToneAnalysisDataSource extends AbstractDataSource
{
    /**
     * @var string
     */
    static protected $identifier = 'ToneAnalysis';

    /**
     * @var ToneService
     * @Flow\Inject
     */
    protected $toneService;

    /**
     * Get tone analysis data
     *
     * {@inheritdoc}
     */
    public function getData(NodeInterface $node = null, array $arguments)
    {
        if (!isset($arguments['typoscriptPath'])) {
            throw new \InvalidArgumentException('Missing "typoscriptPath" argument', 1463406598);
        }
        
        $typoscriptPath = rtrim(explode('__meta', $arguments['typoscriptPath'])[0], '/');
        $content = $this->renderContent($node, $typoscriptPath);
        $result = $this->toneService->analyze($content);

        $toneCategories = $result['document_tone']['tone_categories'];

        return [
            'data' => [
                $this->getAnalysis($toneCategories[0], 'emotion'),
                $this->getAnalysis($toneCategories[1], 'language'),
                $this->getAnalysis($toneCategories[2], 'social')
            ]
        ];
    }

    /**
     * @param NodeInterface $node
     * @param string $typoscriptPath
     * @return string
     * @throws \Exception
     */
    protected function renderContent(NodeInterface $node, $typoscriptPath)
    {
        $view = new TypoScriptView();
        $view->setControllerContext($this->controllerContext);
        $view->assign('value', $node);
        $view->setTypoScriptPath($typoscriptPath);
        return $view->render();
    }

    /**
     * @param array $data
     * @param string $label
     * @return array
     */
    protected function getAnalysis(array $data, $label)
    {
        $tones = $this->mapTones($data['tones']);
        return $stats = [
            'label' => 'summary.' . $label,
            'resume' => [
                'mainTone' => $this->getMainToneLabel($tones)
            ],
            'tones' => $tones
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    protected function mapTones(array $data)
    {
        $tones = [];
        foreach ($data as $tone) {
            $tones[$tone['tone_id']] = [
                'label' => 'tone.' . $tone['tone_id'],
                'value' => number_format($tone['score'], 3)
            ];
        }
        return array_values($tones);
    }

    /**
     * @param array $tones
     * @return string
     */
    protected function getMainToneLabel(array $tones)
    {
        $tones = array_reverse($tones);
        return reset($tones)['label'];

    }
}
