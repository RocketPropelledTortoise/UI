<?php
namespace Rocket\UI\Forms\Fields;

/**
 * Manage file fields
 */

/**
 * Creates a file field
 *
 * @author Stéphane Goetz
 */
class File extends Field
{
    /**
     * Extends the type
     * @param string $id
     * @param array $data
     */
    public function __construct($id, $data = [])
    {
        $this->type = 'file';

        parent::__construct($id, $data);
    }

    /**
     * Creates a field with special tags
     */
    protected function renderInner()
    {
        $this->input_attributes['type'] = 'text';
        $this->input_attributes['id'] = $this->id . '__fake';
        $this->input_attributes['value'] = '';
        $wrong_one = '<input' . $this->renderAttributes($this->input_attributes) . ' />';

        $this->input_attributes['type'] = 'file';
        $this->input_attributes['class'][] = 'file';
        $this->input_attributes['id'] = $this->id;
        $real_file = '<input' . $this->renderAttributes($this->input_attributes) . ' />';

        $this->result .= '<span class="fileinputs">';
        $this->result .= $real_file;
        $this->result .= '<span class="fakefile">';
        $this->result .= $wrong_one;
        $this->result .= '<input type="button"' . ' value="Browse" class="button_button" />';
        $this->result .= '</span>';
        $this->result .= '</span>';
    }

    /**
     * Renders the scripts
     */
    protected function renderScript()
    {
        $this->getJS()->ready(
            "$('#" . $this->id . "')
                .bind('change', function() {
                    $('#" . $this->id . "__fake').val($('#" . $this->id . "').val());
                })
                .bind('mouseover', function() {
                    $('#" . $this->id . "__fake').addClass('hover');
                })
                .bind('mouseout', function() {
                    $('#" . $this->id . "__fake').val($('#" . $this->id . "').val()).removeClass('hover');
                });
            $('#" . $this->id . "__fake').bind('focus', function(){
                $('#" . $this->id . "').click();
                $('#" . $this->id . "__fake').blur();
            });"
        );
    }
}
