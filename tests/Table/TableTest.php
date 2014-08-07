<?php
/**
 * Created by IntelliJ IDEA.
 * User: sgoetz
 * Date: 10.09.13
 * Time: 11:08
 * To change this template use File | Settings | File Templates.
 */

use \Rocket\UI\Table\Table;

class TableTest extends PHPUnit_Framework_TestCase
{
    protected static function getMethod($name)
    {
        $class = new ReflectionClass('\\Rocket\\UI\\Table\\Table');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected static function getValue($object, $attribute)
    {
        $class = new ReflectionClass('\\Rocket\\UI\\Table\\Table');
        $property = $class->getProperty($attribute);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    public function cellData()
    {
        return array(
            array(
                '<td>test</td>',
                array(
                    'test'
                )
            ),
            array(
                '<th>test</th>',
                array(
                    'test',
                    true
                )
            ),
            array(
                '<td class="testclass">test</td>',
                array(
                    ['data' => 'test', 'class' => 'testclass']
                )
            ),
            array(
                '<td class="testclass" colspan="2">test</td>',
                array(
                    ['data' => 'test', 'class' => 'testclass', 'colspan' => 2]
                )
            ),
        );
    }

    /**
     * @dataProvider cellData
     */
    public function testThemeTableCell($expected, $data)
    {
        $method = self::getMethod('themeTableCell');

        $table = new Table([], []);

        $this->assertEquals($expected, $method->invokeArgs($table, $data));
    }

    public function rowData()
    {
        return array(
            array(
                '<tr><td>cell1</td><td>cell2</td></tr>' . "\n",
                array(
                    ['cell1', 'cell2']
                )
            ),
            array(
                '<tr><td>cell1</td><td>cell2</td><td>cell3</td></tr>' . "\n",
                array(
                    ['cell1', 'cell2', 'cell3']
                )
            ),
            array(
                '<tr><td>cell1</td><td>cell2</td><td class="testclass">test</td></tr>' . "\n",
                array(
                    ['cell1', 'cell2', ['data' => 'test', 'class' => 'testclass']]
                )
            ),
            array(
                '<tr class="testclass"><td>cell1</td><td>cell2</td><td>cell3</td></tr>' . "\n",
                array(
                    [
                        'data' => ['cell1', 'cell2', 'cell3'],
                        'class' => 'testclass'
                    ]
                )
            ),
        );
    }

    /**
     * @dataProvider rowData
     */
    public function testThemeTableRow($expected, $data)
    {
        $method = self::getMethod('themeTableRow');

        $table = new Table([], []);

        $this->assertEquals($expected, $method->invokeArgs($table, $data));
    }

    public function headData()
    {
        return array(
            array(
                "<thead>\n<tr><th>cell1</th><th>cell2</th></tr>\n</thead>",
                true,
                ['cell1', 'cell2']
            ),
            array(
                '<tr><th>cell1</th><th>cell2</th><th>cell3</th></tr>' . "\n",
                false,
                ['cell1', 'cell2', 'cell3']
            ),
            array(
                "<thead>\n<tr><th>cell1</th><th>cell2</th><th class=\"testclass\">test</th></tr>\n</thead>",
                true,
                ['cell1', 'cell2', ['data' => 'test', 'class' => 'testclass']]
            ),
            array(
                "<thead>\n<tr class=\"testclass\"><th>cell1</th><th>cell2</th><th>cell3</th></tr>\n</thead>",
                true,
                [
                    'data' => ['cell1', 'cell2', 'cell3'],
                    'class' => 'testclass'
                ]
            ),
        );
    }

    /**
     * @dataProvider headData
     */
    public function testThemeTableHead($expected, $has_rows, $header)
    {
        $method = self::getMethod('themeTableHead');

        $table = new Table($header, []);

        $this->assertEquals($expected, $method->invokeArgs($table, [$has_rows]));
    }

    public function attributesData()
    {
        return array(
            array(
                "table table-striped sticky-enabled",
                true,
                null
            ),
            array(
                "table table-striped",
                false,
                null
            ),
            array(
                "table table-nostyle sticky-enabled",
                true,
                "table-nostyle"
            ),
            array(
                "table testclass table-striped sticky-enabled",
                true,
                "testclass"
            ),
        );
    }

    /**
     * @dataProvider attributesData
     */
    public function testprepareTableClasses($expected, $has_header, $attributes)
    {
        //allow method calling
        $method = self::getMethod('prepareTableClasses');

        //init table
        $table = new Table([], [], ($attributes? ['class' => $attributes] : []));

        $method->invokeArgs($table, [$has_header]);

        //get resolved attributes
        $attributes = self::getValue($table, 'attributes');

        $this->assertEquals($expected, $attributes['class']);
    }

    public function quickData()
    {
        return array(
            array(
                '<table class="table table-striped">' . "\n" . '</table>'. "\n",
                [[], []]
            ),
            array(
                '<table class="table table-striped">' . "\n" . '<caption>a caption</caption>' . "\n" . '</table>'. "\n",
                [[], [], [], 'a caption']
            ),
            array(
                '<table class="table table-striped sticky-enabled">' . "\n" .
                '<tr><th>head1</th><th>head2</th></tr>' . "\n" .
                '</table>'. "\n",
                [['head1', 'head2'], []]
            ),
            array(
                '<table class="table table-striped sticky-enabled">' . "\n" .
                '<thead>'. "\n" .
                '<tr><th>head1</th><th>head2</th></tr>' . "\n" .
                '</thead><tbody>'. "\n" .
                '<tr><td>r1c1</td><td>r1c2</td></tr>' . "\n" .
                '<tr><td>r2c1</td><td>r2c2</td></tr>' . "\n" .
                '</tbody>'. "\n" .
                '</table>'. "\n",
                [['head1', 'head2'], [['r1c1', 'r1c2'], ['r2c1', 'r2c2']]]
            ),
            array(
                '<table class="table table-striped sticky-enabled">' . "\n" .
                '<thead>'. "\n" .
                '<tr><th>head1</th><th>head2</th></tr>' . "\n" .
                '</thead><tbody>'. "\n" .
                '<tr><td>r1c1</td><td>r1c2</td></tr>' . "\n" .
				'</tbody>'. "\n" .
                '<tfoot>'. "\n" .
                '<tr><td>r2c1</td><td>r2c2</td></tr>'. "\n" .
                '</tfoot>' . "\n" .                
                '</table>'. "\n",
                [['head1', 'head2'], [['r1c1', 'r1c2'], ['footer' => true, 'data' => ['r2c1', 'r2c2']]]]
            ),
        );
    }

    /**
     * @dataProvider quickData
     */
    public function testQuickTable($expected, $table)
    {
        $this->assertEquals($expected, call_user_func_array(['\\Rocket\\UI\\Table\\Table', 'quick'], $table));
    }
}
