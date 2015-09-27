<?php
namespace Rocket\UI\Forms\Fields;

/**
 * Manage date fields
 */

/**
 * Creates a date field with Form_element
 *
 * @author Stéphane Goetz
 */
class Date extends Field
{
    /**
     * Adds some specific attributes to be able to use the date picker automatically
     */
    protected function inputAttributes()
    {
        parent::inputAttributes();

        $this->input_attributes['type'] = 'text';
        $this->input_attributes['class'][] = 'date';

        if (!array_key_exists('options', $this->params)) {
            $this->params['options'] = [];
        }

        $this->params['options'] += [
            'datepicker' => true,
            'formatSubmit' => 'dd/mm/yyyy',
            'format' => 'dd/mm/yyyy',
            'firstDay' => 1,
            'selectMonths' => true,
            'selectYears' => true,
        ];

        if (!$this->params['options']['datepicker']) {
            $this->params['validate'] = false;
            $this->params['multifield'] = true;
        }
    }

    protected function labelAttributes()
    {
        parent::labelAttributes();
        unset($this->label_attributes['for']);
    }

    /**
     * Adds the script logic to the picker
     */
    protected function renderScript()
    {
        if (!$this->params['options']['datepicker']) {
            $this->getJS()->ready("
            $('#{$this->id}_day, #{$this->id}_month, #{$this->id}_year').change(function(){
                var day = $('#{$this->id}_day').val() || '1';
                var month = $('#{$this->id}_month').val() || '1';
                var year = $('#{$this->id}_year').val() || new Date().getFullYear();

                $('#{$this->id}').val(day + '/' + month + '/' + year);
            });

            $('#{$this->id}').change(function(){
                var cal = $(this).val().split('/');

                $('#{$this->id}_day').val(cal[0]);
                $('#{$this->id}_month').val(cal[1]);
                $('#{$this->id}_year').val(cal[2]);
            });
            ");

            return;
        }

        $this->getJS()->ready('$( "#' . $this->id . '").pickadate(' . json_encode($this->params['options']) . ');');
    }

    /**
     * Render the inner field
     */
    protected function renderInner()
    {
        if ($this->params['options']['datepicker']) {
            return parent::renderInner();
        }

        $attr = $this->input_attributes;
        $date = explode('/', $attr['value']) + [1 => '', 2 => ''];

        $field_main = array_merge($attr, ['type' => 'hidden']);
        $this->result .= '<input' . $this->renderAttributes($field_main) . ' />';

        $this->result .= '<div class=row><div class=col-xs-3>';

        //day
        $field_date = array_merge($attr, ['id' => $attr['id'] . '_day', 'name' => $attr['id'] . '_day', 'placeholder' => 'Day']);
        $field_date['value'] = $date[0];
        $this->result .= '<input' . $this->renderAttributes($field_date) . ' />';

        $this->result .= '</div><div class=col-xs-5>';

        //month
        $field_date = array_merge($attr, ['id' => $attr['id'] . '_month', 'name' => $attr['id'] . '_month']);
        unset($field_date['value']);
        $this->result .= '<select' . $this->renderAttributes($field_date) . '>';
        foreach (range(1, 12) as $month) {
            $checked = ($month == $date[1]) ? ' selected=selected' : '';
            $this->result .= "<option value='$month'$checked>" . strftime('%B', mktime(0, 0, 0, $month)) . '</option>';
        }

        $this->result .= '</select>';

        $this->result .= '</div><div class=col-xs-4>';

        //year
        $field_time = array_merge($attr, ['id' => $attr['id'] . '_year', 'name' => $attr['id'] . '_year', 'placeholder' => 'Year']);
        $field_time['value'] = $date[2];
        $this->result .= '<input' . $this->renderAttributes($field_time) . ' />';

        $this->result .= '</div></div>';
    }
}
