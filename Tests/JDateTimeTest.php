<?php

class JDateTimeTest extends PHPUnit_Framework_TestCase
{
    //Start test
    public function setUp()
    {
        $this->obj = new jDateTime(true, true, 'Asia/Tehran');
    }
    //EOF
    public function tearDown()
    {
        //nothing
    }

    public function testMakeJalaliTime()
    {
        $time = $this->obj->mktime(0,0,0,10,02,1368);
        $this->assertEquals($time, 630361800);
        $this->assertEquals($this->obj->date("l Y/m/d", $time), "شنبه ۱۳۶۸/۱۰/۰۲");
        $this->assertEquals($this->obj->date("c", $time, false), "1368-10-02T00:00:00+03:30");
        $time = $this->obj->mktime(NULL, NULL, NULL, 10, 14, 1395);
        $this->assertEquals($time, 1483389000);
    }

    public function testMakeGregorianTime()
    {
        $time = $this->obj->mktime(0,0,0,1,1,2010, false, 'America/New_York');
        $this->assertEquals($time, 1262322000);
        $this->assertEquals($this->obj->date("c", $time, false, false, 'America/New_York'), "2010-01-01T00:00:00-05:00");
        $this->assertEquals($this->obj->date("c", $time, false, false, 'Europe/Berlin'), "2010-01-01T06:00:00+01:00");
    }

    public function testConvertFormatToFormat()
    {
        $a = '2016-02-14 14:20:38';
        $date = \jDateTime::convertFormatToFormat('Y-m-d H:i:s', 'Y-m-d H:i:s', $a, 'Asia/Tehran');
        $this->assertEquals($date, '۱۳۹۴-۱۱-۲۵ ۱۴:۲۰:۳۸');
    }
}
