<?php
namespace TYPO3\CMS\Fluid\ViewHelpers\Format;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Renders a string by passing it to a TYPO3 parseFunc.
 * You can either specify a path to the TypoScript setting or set the parseFunc options directly.
 * By default lib.parseFunc_RTE is used to parse the string.
 *
 * == Examples ==
 *
 * <code title="Default parameters">
 * <f:format.html>foo <b>bar</b>. Some <LINK 1>link</LINK>.</f:format.html>
 * </code>
 * <output>
 * <p class="bodytext">foo <b>bar</b>. Some <a href="index.php?id=1" >link</a>.</p>
 * (depending on your TYPO3 setup)
 * </output>
 *
 * <code title="Custom parseFunc">
 * <f:format.html parseFuncTSPath="lib.parseFunc">foo <b>bar</b>. Some <LINK 1>link</LINK>.</f:format.html>
 * </code>
 * <output>
 * foo <b>bar</b>. Some <a href="index.php?id=1" >link</a>.
 * </output>
 *
 * <code title="Inline notation">
 * {someText -> f:format.html(parseFuncTSPath: 'lib.parseFunc')}
 * </code>
 * <output>
 * foo <b>bar</b>. Some <a href="index.php?id=1" >link</a>.
 * </output>
 *
 * @see https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Parsefunc/
 */
class HtmlViewHelper extends AbstractViewHelper
{
    /**
     * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController contains a backup of the current $GLOBALS['TSFE'] if used in BE mode
     */
    protected static $tsfeBackup;

    /**
     * Disable escaping of child nodes' output
     *
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * Plain HTML should be returned, no output escaping allowed
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @param string $parseFuncTSPath path to TypoScript parseFunc setup.
     * @return string the parsed string.
     */
    public function render($parseFuncTSPath = 'lib.parseFunc_RTE')
    {
        return static::renderStatic(
            array(
                'parseFuncTSPath' => $parseFuncTSPath,
            ),
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    /**
     * @param array $arguments
     * @param callable $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $parseFuncTSPath = $arguments['parseFuncTSPath'];
        if (TYPO3_MODE === 'BE') {
            GeneralUtility::deprecationLog('The usage of the viewhelper "f:format.html" in the backend context is discouraged, and will stop working with CMS 9. '
                    . 'Use the viewhelper "f:format.raw" instead.');
            self::simulateFrontendEnvironment();
        }
        $value = $renderChildrenClosure();
        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $content = $contentObject->parseFunc($value, array(), '< ' . $parseFuncTSPath);
        if (TYPO3_MODE === 'BE') {
            self::resetFrontendEnvironment();
        }
        return $content;
    }

    /**
     * Copies the specified parseFunc configuration to $GLOBALS['TSFE']->tmpl->setup in Backend mode
     * This somewhat hacky work around is currently needed because the parseFunc() function of \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer relies on those variables to be set
     *
     * @return void
     */
    protected static function simulateFrontendEnvironment()
    {
        self::$tsfeBackup = isset($GLOBALS['TSFE']) ? $GLOBALS['TSFE'] : null;
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->tmpl = new \stdClass();
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $configurationManager = $objectManager->get(ConfigurationManagerInterface::class);
        $GLOBALS['TSFE']->tmpl->setup = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
    }

    /**
     * Resets $GLOBALS['TSFE'] if it was previously changed by simulateFrontendEnvironment()
     *
     * @return void
     * @see simulateFrontendEnvironment()
     */
    protected static function resetFrontendEnvironment()
    {
        $GLOBALS['TSFE'] = self::$tsfeBackup;
    }
}
