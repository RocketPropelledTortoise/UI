<?php

class FunctionTest extends PHPUnit_Framework_TestCase
{
    public function jsonEncodeData()
    {
        return array(
            array(
                '{"callback":function(){ return "hey"; }}',
                array('callback' => 'function(){ return "hey"; }')
            ),
            array(
                '{"multi_level_array":{"callback":function(){ return "hey"; }}}',
                array('multi_level_array' => array('callback' => 'function(){ return "hey"; }'))
            ),
            array(
                'function(){ return "hey"; }',
                'function(){ return "hey"; }'
            ),
            array(
                '"un test\""',
                'un test"'
            )
        );
    }

    /**
     * @dataProvider jsonEncodeData
     */
    public function testJsonEncode($expected, $data)
    {
        $this->assertEquals($expected, json_encode_with_functions($data));
    }
}
