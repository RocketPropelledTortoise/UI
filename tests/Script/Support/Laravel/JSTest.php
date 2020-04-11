<?php

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use PHPUnit\Framework\TestCase;

class LaravelJSTest extends TestCase
{
    protected function assertJSRender($content)
    {
        $this->assertEquals($content, $this->js->render());
    }

    public static $BEGIN = "<script type=\"text/javascript\">var APP = APP || {'settings': {}, 'behaviors':{}, 'locale':{}, 'utilities':{}};";
    public static $END = '</script>';

    public function setUp(): void
    {
        parent::setUp();

        $this->js = new \Rocket\UI\Script\Support\Laravel5\JS([]);
    }

    public function settingsData()
    {
        $stdClass = new \stdClass();
        $stdClass->name = 'application_name';

        $arrayable = new ArrayableStub();

        $jsonable = new JsonableStub();

        return [
            [
                '{"website":"www.onigoetz.ch"}',
                ['website' => 'www.onigoetz.ch'],
            ],
            [
                '{"application":{"name":"application_name"}}',
                ['application' => $stdClass],
            ],
            [
                '{"application":["foo","bar"]}',
                ['application' => $arrayable],
            ],
            [
                '{"json":"foo"}',
                ['json' => $jsonable],
            ],
        ];
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
        return ['foo', 'bar'];
    }
}
