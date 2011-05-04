<?php

//Include
require_once dirname(__FILE__) . '/jdatetime.class.php';

//Init
$date = new jDateTime(true, true, 'Asia/Tehran');

echo $date->date("l j F Y H:i"); // Outputs: پنجشنبه ۱۵ اردیبهشت ۱۳۹۰ ۰۰:۰۰
echo "<br />\n";
echo $date->date("Y-m-d", false, false); // Outputs: 1390-02-15
echo "<br />\n";
echo $date->date("Y-m-d", false, false, false); //Outputs: 2011-05-05
echo "<br />\n";
echo $date->date("l j F Y H:i T", false, null, null, 'America/New_York'); //چهارشنبه ۱۴ اردیبهشت ۱۳۹۰ ۱۵:۳۰ EDT

echo "<br />\n";
echo "<br />\n";

$time = $date->mktime(0,0,0,10,2,1368); //630361800
echo $date->date("l Y/m/d", $time); //Outputs: شنبه ۱۳۶۸/۱۰/۰۲
echo "<br />\n";
echo $date->date("l M jS, Y", $time, false, false); //Outputs: Saturday Dec 23rd, 1989

echo "<br />\n";
echo "<br />\n";

$time2 = $date->mktime(0,0,0,1,1,2010, false, 'America/New_York');  //1262291400
echo $date->date("c", $time2, false, false); //Outputs: 2010-01-01T00:00:00+03:30
echo "<br />\n";
//Lets see whens its January 1st in New York, What Time is it in Berlin?
echo $date->date("c", $time2, false, false, 'Europe/Berlin'); //Outputs: 2009-12-31T21:30:00+01:00 