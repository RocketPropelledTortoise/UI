<?php

class FunctionTest extends PHPUnit_Framework_TestCase
{
    public function jsonEncodeData()
    {
        return [
            [
                '{"callback":function(){ return "hey"; }}',
                ['callback' => 'function(){ return "hey"; }'],
            ],
            [
                '{"multi_level_array":{"callback":function(){ return "hey"; }}}',
                ['multi_level_array' => ['callback' => 'function(){ return "hey"; }']],
            ],
            [
                'function(){ return "hey"; }',
                'function(){ return "hey"; }',
            ],
            [
                '"un test\""',
                'un test"',
            ],
        ];
    }

    /**
     * @dataProvider jsonEncodeData
     */
    public function testJsonEncode($expected, $data)
    {
        $this->assertEquals($expected, json_encode_with_functions($data));
    }
}
