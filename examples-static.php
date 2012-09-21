<?php

//Include
require_once dirname(__FILE__) . '/jdatetime.class.php';

//Just adding routine html tags and setting encoding to view Persian characters correctly.
echo "<html>";
echo "<head>";
echo "<meta charset='utf-8'>";
echo "</head>";
echo "<body>";

date_default_timezone_set('Asia/Tehran');

echo jDateTime::date('l j F Y H:i');
echo "<br />";
echo jDateTime::date('Y-m-d', false, false);
echo "<br />";
echo jDateTime::date('Y-m-d', false, false, false);
echo "<br />";
echo jDateTime::date("l j F Y H:i T", false, null, null, 'America/New_York');

//Just adding routine html tags.
echo "</body>";
echo "</html>";