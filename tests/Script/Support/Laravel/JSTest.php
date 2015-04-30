<?php

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class LaravelJSTest extends PHPUnit_Framework_TestCase
{
    protected function assertJSRender($content)
    {
        $this->assertEquals($content, $this->js->render());
    }

    public static $BEGIN = "<script type=\"text/javascript\">var APP = APP || {'settings': {}, 'behaviors':{}, 'locale':{}, 'utilities':{}};";
    public static $END = "</script>";

    public function setUp()
    {
        parent::setUp();

        $this->js = new \Rocket\UI\Script\Support\Laravel5\JS(array());
    }

    public function settingsData()
    {
        $stdClass = new \stdClass();
        $stdClass->name = "application_name";

        $arrayable = new ArrayableStub();

        $jsonable = new JsonableStub();

        return array(
            array(
                '{"website":"www.onigoetz.ch"}',
                array('website' => 'www.onigoetz.ch')
            ),
            array(
                '{"application":{"name":"application_name"}}',
                array('application' => $stdClass)
            ),
            array(
                '{"application":["foo","bar"]}',
                array('application' => $arrayable)
            ),
            array(
                '{"json":"foo"}',
                array('json' => $jsonable)
            )
        );
    }

    /**
     * @dataProvider settingsData
     */
    public function testSettings($expected, $data)
    {
        $this->js->setting($data);

        $this->assertJSRender(self::$BEGIN . 'jQuery.extend(APP.settings, ' . $expected . ');' . self::$END);
    }
}

class JsonableStub implements Jsonable
{
    public function toJson($options = 0)
    {
        return 'foo';
    }
}

class ArrayableStub implements Arrayable
{
    public function toArray()
    {
        return array('foo', 'bar');
    }
}
