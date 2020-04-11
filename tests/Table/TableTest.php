<?php

use PHPUnit\Framework\TestCase;
use Rocket\UI\Table\Table;

class TableTest extends TestCase
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
        return [
            [
                '<td>test</td>',
                [
                    'test',
                ],
            ],
            [
                '<th>test</th>',
                [
                    'test',
                    true,
                ],
            ],
            [
                '<td class="testclass">test</td>',
                [
                    ['data' => 'test', 'class' => 'testclass'],
                ],
            ],
            [
                '<td class="testclass" colspan="2">test</td>',
                [
                    ['data' => 'test', 'class' => 'testclass', 'colspan' => 2],
                ],
            ],
        ];
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
        return [
            [
                '<tr><td>cell1</td><td>cell2</td></tr>' . "\n",
                [
                    ['cell1', 'cell2'],
                ],
            ],
            [
                '<tr><td>cell1</td><td>cell2</td><td>cell3</td></tr>' . "\n",
                [
                    ['cell1', 'cell2', 'cell3'],
                ],
            ],
            [
                '<tr><td>cell1</td><td>cell2</td><td class="testclass">test</td></tr>' . "\n",
                [
                    ['cell1', 'cell2', ['data' => 'test', 'class' => 'testclass']],
                ],
            ],
            [
                '<tr class="testclass"><td>cell1</td><td>cell2</td><td>cell3</td></tr>' . "\n",
                [
                    [
                        'data' => ['cell1', 'cell2', 'cell3'],
                        'class' => 'testclass',
                    ],
                ],
            ],
        ];
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
        return [
            [
                "<thead>\n<tr><th>cell1</th><th>cell2</th></tr>\n</thead>",
                true,
                ['cell1', 'cell2'],
            ],
            [
                '<tr><th>cell1</th><th>cell2</th><th>cell3</th></tr>' . "\n",
                false,
                ['cell1', 'cell2', 'cell3'],
            ],
            [
                "<thead>\n<tr><th>cell1</th><th>cell2</th><th class=\"testclass\">test</th></tr>\n</thead>",
                true,
                ['cell1', 'cell2', ['data' => 'test', 'class' => 'testclass']],
            ],
            [
                "<thead>\n<tr class=\"testclass\"><th>cell1</th><th>cell2</th><th>cell3</th></tr>\n</thead>",
                true,
                [
                    'data' => ['cell1', 'cell2', 'cell3'],
                    'class' => 'testclass',
                ],
            ],
        ];
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
        return [
            [
                'table table-striped sticky-enabled',
                true,
                null,
            ],
            [
                'table table-striped',
                false,
                null,
            ],
            [
                'table table-nostyle sticky-enabled',
                true,
                'table-nostyle',
            ],
            [
                'table testclass table-striped sticky-enabled',
                true,
                'testclass',
            ],
        ];
    }

    /**
     * @dataProvider attributesData
     */
    public function testprepareTableClasses($expected, $has_header, $attributes)
    {
        //allow method calling
        $method = self::getMethod('prepareTableClasses');

        //init table
        $table = new Table([], [], ($attributes ? ['class' => $attributes] : []));

        $method->invokeArgs($table, [$has_header]);

        //get resolved attributes
        $attributes = self::getValue($table, 'attributes');

        $this->assertEquals($expected, $attributes['class']);
    }

    public function quickData()
    {
        return [
            [
                '<table class="table table-striped">' . "\n" . '</table>' . "\n",
                [[], []],
            ],
            [
                '<table class="table table-striped">' . "\n" . '<caption>a caption</caption>' . "\n" . '</table>' . "\n",
                [[], [], [], 'a caption'],
            ],
            [
                '<table class="table table-striped sticky-enabled">' . "\n" .
                '<tr><th>head1</th><th>head2</th></tr>' . "\n" .
                '</table>' . "\n",
                [['head1', 'head2'], []],
            ],
            [
                '<table class="table table-striped sticky-enabled">' . "\n" .
                '<thead>' . "\n" .
                '<tr><th>head1</th><th>head2</th></tr>' . "\n" .
                '</thead><tbody>' . "\n" .
                '<tr><td>r1c1</td><td>r1c2</td></tr>' . "\n" .
                '<tr><td>r2c1</td><td>r2c2</td></tr>' . "\n" .
                '</tbody>' . "\n" .
                '</table>' . "\n",
                [['head1', 'head2'], [['r1c1', 'r1c2'], ['r2c1', 'r2c2']]],
            ],
            [
                '<table class="table table-striped sticky-enabled">' . "\n" .
                '<thead>' . "\n" .
                '<tr><th>head1</th><th>head2</th></tr>' . "\n" .
                '</thead><tbody>' . "\n" .
                '<tr><td>r1c1</td><td>r1c2</td></tr>' . "\n" .
                '</tbody>' . "\n" .
                '<tfoot>' . "\n" .
                '<tr><td>r2c1</td><td>r2c2</td></tr>' . "\n" .
                '</tfoot>' . "\n" .
                '</table>' . "\n",
                [['head1', 'head2'], [['r1c1', 'r1c2'], ['footer' => true, 'data' => ['r2c1', 'r2c2']]]],
            ],
        ];
    }

    /**
     * @dataProvider quickData
     */
    public function testQuickTable($expected, $table)
    {
        $this->assertEquals($expected, call_user_func_array(['\\Rocket\\UI\\Table\\Table', 'quick'], $table));
    }
}
