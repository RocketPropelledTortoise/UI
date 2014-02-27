<?php

class JSTest extends PHPUnit_Framework_TestCase
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

        $this->js = new \Rocket\UI\Script\JS(array());
    }

    public function testFile()
    {
        $this->js->file('/app/js_file.js');

        $this->assertJSRender('<script type="text/javascript" src="/app/js_file.js"></script>');
    }

    public function settingsData()
    {
        $arrayable = new \stdClass();
        $arrayable->name = "application_name";

        return array(
            array(
                '{"website":"www.onigoetz.ch"}',
                array('website' => 'www.onigoetz.ch')
            ),
            array(
                '{"application":{"name":"application_name"}}',
                array('application' => $arrayable)
            )
        );
    }

    /**
     * @dataProvider settingsData
     */
    public function testSettings($expected, $data)
    {
        $this->js->setting($data);

        $begin = self::$BEGIN . 'jQuery.extend(APP.settings, ';
        $end = ');</script>';

        $this->assertJSRender($begin . $expected . $end);
    }

    public function testEmptySettings()
    {
        $this->js->setting(array());
        $this->assertJSRender('');
    }

    public function testReady()
    {
        $this->js->ready('$("body.js").hide();');
        $this->js->ready('$("body.js").show();');

        $expected = "\n" . 'jQuery(document).ready(function($) {$("body.js").hide();
//---
$("body.js").show();});';

        $this->assertJSRender(self::$BEGIN . $expected . self::$END);
    }

    public function testScript()
    {
        $this->js->script('alert("yep");');
        $this->js->script('alert("yep yep");');

        $expected = 'alert("yep");' . "\n" . 'alert("yep yep");';

        $this->assertJSRender(self::$BEGIN . $expected . self::$END);
    }
}
