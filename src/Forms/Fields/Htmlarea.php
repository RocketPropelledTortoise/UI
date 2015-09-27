<?php
namespace Rocket\UI\Forms\Fields;

/**
 * Manages an HTMLArea
 */

/**
 * Creates an HTMLArea with Form_element
 *
 * @author Stéphane Goetz
 */
class Htmlarea extends Textarea
{
    /**
     * {@inheritdoc}
     */
    protected function getDefaults()
    {
        return parent::getDefaults() + [
            'htmlarea' => [
                'style' => 'advanced',
                'images' => true,
                'css' => true,
            ],
        ];
    }

    /**
     * Renders the scripts used for the htmlarea
     */
    protected function renderScript()
    {
        $controls = [
            'simple' => [
                'orderedlist',
                'unorderedlist',
                '|',
                'leftalign',
                'blockjustify',
                '|',
                'unformat',
                '|',
                'undo',
                'redo',
                'n',
                'bold',
                'italic',
                'underline',
                '|',
                'link',
                'unlink',
                '|',
                'cut',
                'copy',
                'paste',
            ],
            'advanced' => [
                'subscript',
                'superscript',
                '|',
                'orderedlist',
                'unorderedlist',
                '|',
                'outdent',
                'indent',
                '|',
                'leftalign',
                'centeralign',
                'rightalign',
                'blockjustify',
                '|',
                'unformat',
                '|',
                'undo',
                'redo',
                'n',
                'bold',
                'italic',
                'underline',
                'strikethrough',
                '|',
                'size',
                'style',
                '|',
                (($this->params['htmlarea']['images']) ? 'image' : ''),
                'hr',
                'link',
                'unlink',
                '|',
                'cut',
                'copy',
                'paste',
            ],
        ];

        $config = [
            'id' => $this->id,
            'controls' => $controls[$this->params['htmlarea']['style']],
            'footer' => false,
            'fonts' => ['Verdana', 'Arial', 'Georgia', 'Trebuchet MS'],
            'xhtml' => true,
            'images' => (bool) $this->params['htmlarea']['images'],
            'bodyid' => 'editor',
        ];

        if ($this->params['htmlarea']['css']) {
            $config['cssfile'] = \URL::to('css/minimal.css');
        }

        $registered_id = 'edit_form_elm_' . $this->id;

        $this->getJS()->ready(
            '$(document).one("after_content", function() {' .
            '   new TINY.editor.edit(\'' . $registered_id . '\',' . json_encode_with_functions($config) . '); ' .
            '   $(\'#' . $this->id . '\').closest("form").submit(function() {' . $registered_id . '.post(); });
            });'
        );
    }

    /**
     * Render the file as a textarea and not input
     */
    protected function renderInner()
    {
        $val = $this->input_attributes['value'];
        unset($this->input_attributes['value']);

        $attributes = $this->renderAttributes($this->input_attributes);

        $this->result .= "<textarea $attributes>$val</textarea>";
    }
}
