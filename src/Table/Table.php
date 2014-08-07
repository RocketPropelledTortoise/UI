<?php
/**
 * Generate a table
 */

namespace Rocket\UI\Table;

class Table
{
    /**
     * Holds all content rows
     *
     * @var array
     */
    protected $rows;

    /**
     * Holds all header cells
     *
     * @var array
     */
    protected $header;

    /**
     * Attributes for the table
     *
     * @var array
     */
    protected $attributes;

    /**
     * Table's caption
     *
     * @var string
     */
    protected $caption;

    /**
     * Prepare a table
     *
     * @param array|null $header
     * @param array|null $rows
     * @param array $attributes
     * @param string|null $caption
     */
    public function __construct($header = null, $rows = null, $attributes = array(), $caption = null)
    {
        $this->setHeader($header);
        $this->setRows($rows);
        $this->setAttributes($attributes);
        $this->setCaption($caption);
    }

    /**
     * Set the header
     *
     * @param array $header
     */
    public function setHeader(array $header)
    {
        $this->header = $header;
    }

    /**
     * Set the rows
     *
     * @param array $content
     */
    public function setRows(array $content)
    {
        $this->rows = $content;
    }

    /**
     * Set the attributes
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Set the caption
     *
     * @param $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * Render the whole table
     *
     * @return string
     */
    public function render()
    {

        $has_header = count($this->header);
        $has_rows = count($this->rows);

        $this->prepareTableClasses($has_header);

        $output = '<table' . $this->attributes($this->attributes) . ">\n";

        if (isset($this->caption)) {
            $output .= '<caption>' . $this->caption . "</caption>\n";
        }

        // Format the table header:
        if ($has_header) {
            $output .= $this->themeTableHead($has_rows);
        }

        $footer_rows = '';

        // Format the table rows:
        if ($has_rows) {
            $output .= "<tbody>\n";
            foreach ($this->rows as $row) {
                $rendered = $this->themeTableRow($row);
                if (array_key_exists('footer', $row) && $row['footer'] == true) {
                    $footer_rows .= $rendered;
                } else {
                    $output .= $rendered;
                }
            }
            $output .= "</tbody>\n";
        }

        if ($footer_rows != '') {
            $output .= "<tfoot>\n$footer_rows</tfoot>\n";
        }

        $output .= "</table>\n";
        return $output;
    }

    /**
     * Prepare the classes for the table
     *
     * @param $has_header
     */
    private function prepareTableClasses($has_header)
    {
        $default_classes = array('table');

        if (!empty($this->attributes['class'])) {
            $items = explode(' ', $this->attributes['class']);
            $default_classes = array_merge($default_classes, $items);
        }

        //if no class starts with "table-" will add striped tables
        if (empty($this->attributes['class']) || !$this->is_in_array("/^table-/", $default_classes)) {
            $default_classes[] = 'table-striped';
        }

        // Add sticky headers, if applicable.
        if ($has_header) {
            $default_classes[] = 'sticky-enabled';
        }

        $this->attributes['class'] = implode(' ', $default_classes);
    }

    /**
     * Render the header
     *
     * @param bool $has_rows
     * @return string
     */
    private function themeTableHead($has_rows)
    {
        $output = $this->themeTableRow($this->header, true);

        // HTML requires that the thead tag has tr tags in it followed by tbody
        // tags. Using ternary operator to check and see if we have any rows.
        return $has_rows ? "<thead>\n{$output}</thead>" : $output;
    }

    /**
     * Render a complete row
     *
     * @param array $row
     * @param bool $header do we render th ?
     * @return string
     */
    private function themeTableRow($row, $header = false)
    {
        $attributes = array();
        $row_content = '';

        $cells = $row;

        // Check if we're dealing with a simple or complex row
        if (isset($row['data'])) {
            unset($row['footer']);
            foreach ($row as $key => $value) {
                if ($key == 'data') {
                    $cells = $value;
                } else {
                    $attributes[$key] = $value;
                }
            }
        }

        if (count($cells)) {
            // Build row
            $row_content .= '<tr' . $this->attributes($attributes) . '>';
            foreach ($cells as $cell) {
                $row_content .= $this->themeTableCell($cell, $header);
            }
            $row_content .= "</tr>\n";
        }

        return $row_content;
    }

    /**
     * Theme a single cell
     *
     * @param array $cell
     * @param string $header
     * @return string
     */
    private function themeTableCell($cell, $header = null)
    {
        $attributes = '';

        if (is_array($cell)) {
            $data = isset($cell['data']) ? $cell['data'] : '';
            $header |= isset($cell['header']);
            unset($cell['data']);
            unset($cell['header']);
            $attributes = $this->attributes($cell);
        } else {
            $data = $cell;
        }

        if ($header) {
            $output = "<th$attributes>$data</th>";
        } else {
            $output = "<td$attributes>$data</td>";
        }

        return $output;
    }

    /**
     * Return a themed table.
     *
     * @param $header array
     *   An array containing the table headers. Each element of the array can be
     *   either a localized string or an associative array with the following keys:
     *   - "data": The localized title of the table column.
     *   - "field": The database field represented in the table column (required if
     *     user is to be able to sort on this column).
     *   - "sort": A default sort order for this column ("asc" or "desc").
     *   - Any HTML attributes, such as "colspan", to apply to the column header cell.
     * @param $rows array
     *   An array of table rows. Every row is an array of cells, or an associative
     *   array with the following keys:
     *   - "data": an array of cells
     *   - Any HTML attributes, such as "class", to apply to the table row.
     *
     *   Each cell can be either a string or an associative array with the following keys:
     *   - "data": The string to display in the table cell.
     *   - "header": Indicates this cell is a header.
     *   - Any HTML attributes, such as "colspan", to apply to the table cell.
     *
     *   Here's an example for $rows:
     *   <code>
     *   $rows = array(
     *     // Simple row
     *     array(
     *       'Cell 1', 'Cell 2', 'Cell 3'
     *     ),
     *     // Row with attributes on the row and some of its cells.
     *     array(
     *       'data' => array('Cell 1', array('data' => 'Cell 2', 'colspan' => 2)), 'class' => 'funky'
     *     )
     *   );
     *   </code>
     *
     * @param $attributes array     An array of HTML attributes to apply to the table tag.
     * @param $caption string       A localized string to use for the <caption> tag.
     * @return string               An HTML string representing the table.
     */
    public static function quick($header, $rows, $attributes = array(), $caption = null)
    {
        $table = new Table($header, $rows, $attributes, $caption);
        return $table->render();
    }

    /**
     * is in array ? with a regex as needle
     *
     * @param string $pattern
     * @param array $subjectArray
     * @return bool
     */
    protected function is_in_array($pattern, array $subjectArray, &$allMatches = array(), $flags = null, $offset = null)
    {
        foreach($subjectArray as $subject) {
            if (preg_match($pattern, $subject, $matches, $flags, $offset)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * Taken form Illuminate/html, copied as it has far too much dependencies (13 !) and we only need these 10 lines
     *
     * @param array $attributes
     * @return string
     */
    protected function attributes($attributes)
    {
        $html = array();

        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        foreach ((array)$attributes as $key => $value) {
            //if (is_numeric($key)) { //should not happen
            //    $key = $value;
            //}

            if (!is_null($value)) {
                $html[] = $key . '="' . e($value) . '"';
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }
}
