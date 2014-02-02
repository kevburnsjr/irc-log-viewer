<?php

class FunctionalTest extends PHPUnit_Framework_TestCase
{
    const BASEURL = 'http://localhost:8000/';

    public function test_index_html()
    {
        $test = file_get_contents(static::BASEURL . 'index.html');
        $regex = preg_quote(file_get_contents(__DIR__.'/html/index.html'), '/');
        $regex = str_replace('2014\-02\-02', '\d\d\d\d\-\d\d\-\d\d', $regex);
        $this->assertRegExp('/'.$regex.'/u', $test);
    }

    public function test_2010_02_13_html()
    {
        $test = file_get_contents(static::BASEURL . '2010-02-13.html');
        $expected = file_get_contents(__DIR__.'/html/2010-02-13.html');
        $this->assertEquals($expected, $test);
    }

    public function test_2010_02_13_txt()
    {
        $test = file_get_contents(static::BASEURL . '2010-02-13.txt');
        $expected = file_get_contents(__DIR__.'/html/2010-02-13.txt');
        $this->assertEquals($expected, $test);
    }

    public function test_2010_02_12_html()
    {
        $test = file_get_contents(static::BASEURL . '2010-02-12.html');
        $expected = file_get_contents(__DIR__.'/html/2010-02-12.html');
        $this->assertEquals($expected, $test);
    }

    public function test_2010_02_12_txt()
    {
        $test = file_get_contents(static::BASEURL . '2010-02-12.txt');
        $expected = file_get_contents(__DIR__.'/html/2010-02-12.txt');
        $this->assertEquals($expected, $test);
    }
}
