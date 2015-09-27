<?php

/**
 * Javascript class
 */
namespace Rocket\UI\Script;

/**
 * Javascript class
 *
 * Store all javascript needed for the page to output it in a single block at the end of the page
 */
class JS
{
    /**
     * Holds all javascript content
     *
     * @var array {
     *     @var array $file All files that need to be included
     *     @var array $script All raw scripts
     *     @var array $ready All scripts that need to be wrapped in "document.ready()"
     *     @var array $settings The settings for the javascript page
     * }
     */
    protected $queue;

    /**
     * Prepare the queue
     */
    public function __construct($queue)
    {
        $this->queue = $queue + ['file' => [], 'script' => [], 'ready' => [], 'setting' => []];
    }

    /**
     * Add a file
     *
     * @param string $content
     * @return $this
     */
    public function file($content)
    {
        $this->queue['file'][] = $content;

        return $this;
    }

    /**
     * Add a raw script
     *
     * @param string $content
     * @return $this
     */
    public function script($content)
    {
        $this->queue['script'][] = $content;

        return $this;
    }

    /**
     * Add a script to wrap inside `document.ready()`
     *
     * @param string $content
     * @return $this
     */
    public function ready($content)
    {
        $this->queue['ready'][] = $content;

        return $this;
    }

    /**
     * Add settings to the page
     *
     * @param array $content
     * @return $this
     */
    public function setting(array $content)
    {
        $this->queue['setting'] = array_replace_recursive($this->queue['setting'], $content);

        return $this;
    }

    /**
     * Outputs the scripts
     *
     * @return string
     */
    public function render()
    {
        $output = '';

        //files
        if (count($this->queue['file'])) {
            $output .= $this->renderFiles();
        }

        $script_tag = $this->renderScriptTag();

        if (!empty($script_tag)) {
            $output .= '<script type="text/javascript">';
            $output .= "var APP = APP || {'settings': {}, 'behaviors':{}, 'locale':{}, 'utilities':{}};";
            $output .= $this->renderScriptTag();
            $output .= '</script>';
        }

        return $output;
    }

    protected function renderScriptTag()
    {
        $output = '';
        if (count($this->queue['setting'])) {
            $output .= $this->renderSettings();
        }

        //standard script
        if (count($this->queue['script'])) {
            $output .= $this->renderScript();
        }

        //jquery document.ready
        if (count($this->queue['ready'])) {
            $output .= $this->renderReady();
        }

        return $output;
    }

    protected function renderFiles()
    {
        $output = '';
        foreach ($this->queue['file'] as $item) {
            $output .= '<script type="text/javascript" src="' . $item . '"></script>';
        }

        return $output;
    }

    protected function renderScript()
    {
        return implode("\n", $this->queue['script']);
    }

    protected function renderReady()
    {
        return "\n" . 'jQuery(document).ready(function($) {' . implode("\n//---\n", $this->queue['ready']) . '});';
    }

    protected function renderSettings()
    {
        $settings = $this->resolveSettings();

        return 'jQuery.extend(APP.settings, ' . json_encode($settings) . ');';
    }

    /**
     * Walk the settings array to output them as a pure array
     *
     * @return string
     */
    protected function resolveSettings()
    {
        return $this->queue['setting'];
    }
}
