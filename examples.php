<?php

//Include
require_once dirname(__FILE__) . '/jdatetime.class.php';

//Just add routine html tags and encoding the settings to view Persian characters correctly.
echo "<html>";
echo "<head>";
echo "<meta charset='utf-8'>";
echo "</head>";
echo "<body>";

//Init
$date = new jDateTime(true, true, 'Asia/Tehran');

echo $date->date("l j F Y H:i"); // Outputs: پنجشنبه ۱۵ اردیبهشت ۱۳۹۰ ۰۰:۰۰
echo "<br />\n";
echo $date->date("Y-m-d", false, false); // Outputs: 1390-02-15
echo "<br />\n";
echo $date->date("Y-m-d", false, false, false); //Outputs: 2011-05-05
     //Or you could just use: $date->gDate("Y-m-d"); 
     //Same as above
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

$time2 = $date->mktime(0,0,0,1,1,2010, false, 'America/New_York');  //1262322000

echo $date->date("c", $time2, false, false, 'America/New_York'); //Outputs: 2010-01-01T00:00:00-05:00
echo "<br />\n";
//Lets see whens its January 1st in New York, What Time is it in Berlin?
echo $date->date("c", $time2, false, false, 'Europe/Berlin'); //Outputs: 2010-01-01T06:00:00+01:0
//Or Iran in Native Time?
echo "<br />\n";
echo $date->date("c", $time2, false, true, 'Asia/Tehran'); //Outputs: 1388-10-11T08:30:00+03:30

//Just adding routine html tags.
echo "</body>";
echo "</html>";
