<?php
/**
 * Jalali DateTime Class, supports years higher than 2038
 * by: Sallar Kaboli
 *
 * Requires PHP >= 5.2
 *
 * PHP's default 'date' function does not support years higher than
 * 2038. Intorduced in PHP5, DateTime class supports higher years
 * and also invalid date entries.
 * Also, Persian users are using classic 'jdate' function for years now
 * and beside the fact that it's amazing and also helped me to write this
 * one, it's really out of date, and can't be used in modern real world
 * web applications as it is completely written in functions.
 *
 * Copyright (C) 2011  Sallar Kaboli (http://sallar.ir)
 * Part of Phoenix Framework (p5x.org) by Phoenix Alternatvie
 *
 * Original Jalali to Gregorian (and vice versa) convertor:
 * Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi
 *
 * List of supported timezones can be found here:
 * http://www.php.net/manual/en/timezones.php
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @package    jDateTime
 * @author     Sallar Kaboli <sallar.kaboli@gmail.com>
 * @author     Omid Pilevar <omid.pixel@gmail.com>
 * @copyright  2003-2011 Sallar Kaboli
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://sallar.me/projects/jdatetime/
 * @see        DateTime
 * @version    1.5.0
 */
class jDateTime
{

    /**
     * Defaults
     */
    private $jalali = true; //Use Jalali Date, If set to false, falls back to gregorian
    private $convert = true; //Convert numbers to Farsi characters in utf-8
    private $timezone = null; //Timezone String e.g Asia/Tehran, Default is GMT
    private $temp = array();

    /**
     * jDateTime::Constructor
     *
     * Pass these parameteres when creating a new instance
     * of this Class, and they will be used as defaults.
     * e.g $obj = new jDateTime(false, true, 'Asia/Tehran');
     * To use system defaults pass null for each one or just
     * create the object without any parameters.
     *
     * @author Sallar Kaboli
     * @param $convert bool Converts numbers to Farsi
     * @param $jalali bool Converts date to Jalali
     * @param $timezone string Timezone string
     */
    public function __construct($convert = null, $jalali = null, $timezone = null)
    {
        if ( $jalali   !== null ) $this->jalali = ($jalali === false) ? false : true;
        if ( $convert  !== null ) $this->convert = ($convert === false) ? false : true;
        if ( $timezone !== null ) $this->timezone = ($timezone != null) ? $timezone : null;
    }

    /**
     * jDateTime::Date
     *
     * Formats and returns given timestamp just like php's
     * built in date() function.
     * e.g:
     * $obj->date("Y-m-d H:i", time());
     * $obj->date("Y-m-d", time(), false, false, 'America/New_York');
     *
     * @author Sallar Kaboli
     * @param $format string Acceps format string based on: php.net/date
     * @param $stamp int Unix Timestamp (Epoch Time)
     * @param $convert bool (Optional) forces convert action. pass null to use system default
     * @param $jalali bool (Optional) forces jalali conversion. pass null to use system default
     * @param $timezone string (Optional) forces a different timezone. pass null to use system default
     * @return string Formatted input
     */
    public function date($format, $stamp = false, $convert = null, $jalali = null, $timezone = null)
    {

        //Timestamp + Timezone
        $stamp = ($stamp != false) ? $stamp : time();
        $obj = new DateTime('@' . $stamp);
        if ( $this->timezone != null || $timezone != null ) {
            $obj->setTimezone( new DateTimeZone(($timezone != null) ? $timezone : $this->timezone) );
        }

        if ( ($this->jalali === false && $jalali === null) || $jalali === false ) {
            return $obj->format($format);
        }
        else {
            
            //Find what to replace
            $chars = (preg_match_all('/([a-zA-Z]{1})/', $format, $chars)) ? $chars[0] : array();
            
            //Intact Keys
            $intact = array('B','h','H','g','G','i','s','I','U','u','Z','O','P');
            $intact = $this->filterArray($chars, $intact);
            $intactValues = array();

            foreach ($intact as $k => $v) {
                $intactValues[$k] = $obj->format($v);
            }
            //End Intact Keys


            //Changed Keys
            list($year, $month, $day) = array($obj->format('Y'), $obj->format('n'), $obj->format('j'));
            list($jyear, $jmonth, $jday) = $this->toJalali($year, $month, $day);

            $keys = array('d','D','j','l','N','S','w','z','W','F','m','M','n','t','L','o','Y','y','a','A','c','r','e','T');
            $keys = $this->filterArray($chars, $keys, array('z'));
            $values = array();

            foreach ($keys as $k => $key) {

                $v = '';
                switch ($key) {
                    //Day
                    case 'd':
                        $v = sprintf("%02d", $jday);
                        break;
                    case 'D':
                        $v = $this->getDayNames($obj->format('D'), true);
                        break;
                    case 'j':
                        $v = $jday;
                        break;
                    case 'l':
                        $v = $this->getDayNames($obj->format('l'));
                        break;
                    case 'N':
                        $v = $this->getDayNames($obj->format('l'), false, 1, true);
                        break;
                    case 'S':
                        $v = 'ام';
                        break;
                    case 'w':
                        $v = $this->getDayNames($obj->format('l'), false, 1, true) - 1;
                        break;
                    case 'z':
                        if ($jmonth > 6) {
                            $v = 186 + (($jmonth - 6 - 1) * 30) + $jday;
                        }
                        else {
                            $v = (($jmonth - 1) * 31) + $jday;
                        }
                        $this->temp['z'] = $v;
                        break;
                    //Week
                    case 'W':
                        $v = is_int($this->temp['z'] / 7) ? ($this->temp['z'] / 7) : intval($this->temp['z'] / 7 + 1);
                        break;
                    //Month
                    case 'F':
                        $v = $this->getMonthNames($jmonth);
                        break;
                    case 'm':
                        $v = sprintf("%02d", $jmonth);
                        break;
                    case 'M':
                        $v = $this->getMonthNames($jmonth, true);
                        break;
                    case 'n':
                        $v = $jmonth;
                        break;
                    case 't':
                        $v = ( $jmonth == 12 ) ? 29 : ( ($jmonth > 6 && $jmonth != 12) ? 30 : 31 );
                        break;
                    //Year
                    case 'L':
                        $tmpObj = new DateTime('@'.(time()-31536000));
                        $v = $tmpObj->format('L');
                        break;
                    case 'o':
                    case 'Y':
                        $v = $jyear;
                        break;
                    case 'y':
                        $v = $jyear % 100;
                        break;
                    //Time
                    case 'a':
                        $v = ($obj->format('a') == 'am') ? 'ق.ظ' : 'ب.ظ';
                        break;
                    case 'A':
                        $v = ($obj->format('A') == 'AM') ? 'قبل از ظهر' : 'بعد از ظهر';
                        break;
                    //Full Dates
                    case 'c':
                        $v  = $jyear.'-'.sprintf("%02d", $jmonth).'-'.sprintf("%02d", $jday).'T';
                        $v .= $obj->format('H').':'.$obj->format('i').':'.$obj->format('s').$obj->format('P');
                        break;
                    case 'r':
                        $v  = $this->getDayNames($obj->format('D'), true).', '.sprintf("%02d", $jday).' '.$this->getMonthNames($jmonth, true);
                        $v .= ' '.$jyear.' '.$obj->format('H').':'.$obj->format('i').':'.$obj->format('s').' '.$obj->format('P');
                        break;
                    //Timezone
                    case 'e':
                        $v = $obj->format('e');
                        break;
                    case 'T':
                        $v = $obj->format('T');
                        break;

                }
                $values[$k] = $v;

            }
            //End Changed Keys

            //Merge
            $keys = array_merge($intact, $keys);
            $values = array_merge($intactValues, $values);

            //Return
            $ret = strtr($format, array_combine($keys, $values));
            return
            ($convert === false ||
                ($convert === null && $this->convert === false) ||
                ( $jalali === false || $jalali === null && $this->jalali === false ))
                ? $ret : $this->convertNumbers($ret);

        }

    }

    /**
     * jDateTime::gDate
     *
     * Same as jDateTime::Date method
     * but this one works as a helper and returns Gregorian Date
     * in case someone doesn't like to pass all those false arguments
     * to Date method.
     *
     * e.g. $obj->gDate("Y-m-d") //Outputs: 2011-05-05
     *      $obj->date("Y-m-d", false, false, false); //Outputs: 2011-05-05
     *      Both return the exact same result.
     *
     * @author Sallar Kaboli
     * @param $format string Acceps format string based on: php.net/date
     * @param $stamp int Unix Timestamp (Epoch Time)
     * @param $timezone string (Optional) forces a different timezone. pass null to use system default
     * @return string Formatted input
     */
    public function gDate($format, $stamp = false, $timezone = null)
    {
        return $this->date($format, $stamp, false, false, $timezone);
    }
    
    /**
     * jDateTime::Strftime
     *
     * Format a local time/date according to locale settings
     * built in strftime() function.
     * e.g:
     * $obj->strftime("%x %H", time());
     * $obj->strftime("%H", time(), false, false, 'America/New_York');
     *
     * @author Omid Pilevar
     * @param $format string Acceps format string based on: php.net/date
     * @param $stamp int Unix Timestamp (Epoch Time)
     * @param $jalali bool (Optional) forces jalali conversion. pass null to use system default
     * @param $timezone string (Optional) forces a different timezone. pass null to use system default
     * @return string Formatted input
     */
    public function strftime($format, $stamp = false, $jalali = null, $timezone = null)
    {
        //Defaults
        $stamp = (!$stamp) ? time() : $stamp;

        $str_format_code = array(
            "%a", "%A", "%d", "%e", "%j", "%u", "%w",
            "%U", "%V", "%W",
            "%b", "%B", "%h", "%m",
            "%C", "%g", "%G", "%y", "%Y",
            "%H", "%I", "%l", "%M", "%p", "%P", "%r", "%R", "%S", "%T", "%X", "%z", "%Z",
            "%c", "%D", "%F", "%s", "%x",
            "%n", "%t", "%%"
        );
        $date_format_code = array(
            "D", "l", "d", "j", "z", "N", "w",
            "W", "W", "W",
            "M", "F", "M", "m",
            "y", "y", "y", "y", "Y",
            "H", "h", "g", "i", "A", "a", "h:i:s A", "H:i", "s", "H:i:s", "h:i:s", "H", "H",
            "D j M H:i:s", "d/m/y", "Y-m-d", "U", "d/m/y",
            "\n", "\t", "%"
        );

        //Change Strftime format to Date format
        $format = str_replace($str_format_code, $date_format_code, $format);

        //Convert to date
        return $this->date($format, $stamp, $jalali, $timezone);
    }
	
   /**
     * jDateTime::Mktime
     *
     * Creates a Unix Timestamp (Epoch Time) based on given parameters
     * works like php's built in mktime() function.
     * e.g:
     * $time = $obj->mktime(0,0,0,2,10,1368);
     * $obj->date("Y-m-d", $time); //Format and Display
     * $obj->date("Y-m-d", $time, false, false); //Display in Gregorian !
     *
     * You can force gregorian mktime if system default is jalali and you
     * need to create a timestamp based on gregorian date
     * $time2 = $obj->mktime(0,0,0,12,23,1989, false);
     *
     * @author Sallar Kaboli
     * @param $hour int Hour based on 24 hour system
     * @param $minute int Minutes
     * @param $second int Seconds
     * @param $month int Month Number
     * @param $day int Day Number
     * @param $year int Four-digit Year number eg. 1390
     * @param $jalali bool (Optional) pass false if you want to input gregorian time
     * @param $timezone string (Optional) acceps an optional timezone if you want one
     * @return int Unix Timestamp (Epoch Time)
     */
    public function mktime($hour, $minute, $second, $month, $day, $year, $jalali = null, $timezone = null)
    {
        //Defaults
        $month = (intval($month) == 0) ? $this->date('m') : $month;
        $day   = (intval($day)   == 0) ? $this->date('d') : $day;
        $year  = (intval($year)  == 0) ? $this->date('Y') : $year;

        //Convert to Gregorian if necessary
        if ( $jalali === true || ($jalali === null && $this->jalali === true) ) {
            list($year, $month, $day) = $this->toGregorian($year, $month, $day);
        }

        //Create a new object and set the timezone if available
        $date = $year.'-'.sprintf("%02d", $month).'-'.sprintf("%02d", $day).' '.$hour.':'.$minute.':'.$second;

        if ( $this->timezone != null || $timezone != null ) {
            $obj = new DateTime($date, new DateTimeZone(($timezone != null) ? $timezone : $this->timezone));
        }
        else {
            $obj = new DateTime($date);
        }

        //Return
        return $obj->format("U");
    }
    
    /**
     * jDateTime::Checkdate
     *
     * Checks the validity of the date formed by the arguments.
     * A date is considered valid if each parameter is properly defined.
     * works like php's built in checkdate() function.
     * Leap years are taken into consideration.
     * e.g:
     * $obj->checkdate(10, 21, 1390); // Return true
     * $obj->checkdate(9, 31, 1390);  // Return false
     *
     * You can force gregorian checkdate if system default is jalali and you
     * need to check based on gregorian date
     * $check = $obj->checkdate(12, 31, 2011, false);
     *
     * @author Omid Pilevar
     * @param $month int The month is between 1 and 12 inclusive.
     * @param $day int The day is within the allowed number of days for the given month.
     * @param $year int The year is between 1 and 32767 inclusive.
     * @param $jalali bool (Optional) pass false if you want to input gregorian time
     * @return bool
     */
    public function checkdate($month, $day, $year, $jalali = null)
    {
        //Defaults
        $month = (intval($month) == 0) ? $this->date('n') : intval($month);
        $day   = (intval($day)   == 0) ? $this->date('j') : intval($day);
        $year  = (intval($year)  == 0) ? $this->date('Y') : intval($year);
        
        //Check if its jalali date
        if ( $jalali === true || ($jalali === null && $this->jalali === true) )
        {
            $epoch = $this->mktime(0, 0, 0, $month, $day, $year);
            
            if( $this->date("Y-n-j", $epoch,false) == "$year-$month-$day" ) {
                $ret = true;
            }
            else{
                $ret = false; 
            }
        }
        else //Gregorian Date
        { 
            $ret = checkdate($month, $day, $year);
        }
        
        //Return
        return $ret;
    }

    /**
     * System Helpers below
     *
     */
    private function filterArray($needle, $heystack, $always = array())
    {
        foreach($heystack as $k => $v)
        {
            if( !in_array($v, $needle) && !in_array($v, $always) )
                unset($heystack[$k]);
        }
        
        return $heystack;
    }
    
    private function getDayNames($day, $shorten = false, $len = 1, $numeric = false)
    {
        $ret = '';
        switch ( strtolower($day) ) {
            case 'sat': case 'saturday': $ret = 'شنبه'; $n = 1; break;
            case 'sun': case 'sunday': $ret = 'یکشنبه'; $n = 2; break;
            case 'mon': case 'monday': $ret = 'دوشنبه'; $n = 3; break;
            case 'tue': case 'tuesday': $ret = 'سه شنبه'; $n = 4; break;
            case 'wed': case 'wednesday': $ret = 'چهارشنبه'; $n = 5; break;
            case 'thu': case 'thursday': $ret = 'پنجشنبه'; $n = 6; break;
            case 'fri': case 'friday': $ret = 'جمعه'; $n = 7; break;
        }
        return ($numeric) ? $n : (($shorten) ? mb_substr($ret, 0, $len, 'UTF-8') : $ret);
    }

    private function getMonthNames($month, $shorten = false, $len = 3)
    {
        $ret = '';
        switch ( $month ) {
            case '1': $ret = 'فروردین'; break;
            case '2': $ret = 'اردیبهشت'; break;
            case '3': $ret = 'خرداد'; break;
            case '4': $ret = 'تیر'; break;
            case '5': $ret = 'امرداد'; break;
            case '6': $ret = 'شهریور'; break;
            case '7': $ret = 'مهر'; break;
            case '8': $ret = 'آبان'; break;
            case '9': $ret = 'آذر'; break;
            case '10': $ret = 'دی'; break;
            case '11': $ret = 'بهمن'; break;
            case '12': $ret = 'اسفند'; break;
        }
        return ($shorten) ? mb_substr($ret, 0, $len, 'UTF-8') : $ret;
    }

    private function convertNumbers($matches)
    {
        $farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
        $english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        return str_replace($english_array, $farsi_array, $matches);
    }

    private function div($a, $b)
    {
        return (int) ($a / $b);
    }

    /**
     * Gregorian to Jalali Conversion
     * Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi
     *
     */
    public function toJalali($g_y, $g_m, $g_d)
    {

        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

        $gy = $g_y-1600;
        $gm = $g_m-1;
        $gd = $g_d-1;

        $g_day_no = 365*$gy+$this->div($gy+3, 4)-$this->div($gy+99, 100)+$this->div($gy+399, 400);

        for ($i=0; $i < $gm; ++$i)
            $g_day_no += $g_days_in_month[$i];
        if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
            $g_day_no++;
        $g_day_no += $gd;

        $j_day_no = $g_day_no-79;

        $j_np = $this->div($j_day_no, 12053);
        $j_day_no = $j_day_no % 12053;

        $jy = 979+33*$j_np+4*$this->div($j_day_no, 1461);

        $j_day_no %= 1461;

        if ($j_day_no >= 366) {
            $jy += $this->div($j_day_no-1, 365);
            $j_day_no = ($j_day_no-1)%365;
        }

        for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
            $j_day_no -= $j_days_in_month[$i];
        $jm = $i+1;
        $jd = $j_day_no+1;

        return array($jy, $jm, $jd);

    }

    /**
     * Jalali to Gregorian Conversion
     * Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi
     *
     */
    public function toGregorian($j_y, $j_m, $j_d)
    {

        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

        $jy = $j_y-979;
        $jm = $j_m-1;
        $jd = $j_d-1;

        $j_day_no = 365*$jy + $this->div($jy, 33)*8 + $this->div($jy%33+3, 4);
        for ($i=0; $i < $jm; ++$i)
            $j_day_no += $j_days_in_month[$i];

        $j_day_no += $jd;

        $g_day_no = $j_day_no+79;

        $gy = 1600 + 400*$this->div($g_day_no, 146097);
        $g_day_no = $g_day_no % 146097;

        $leap = true;
        if ($g_day_no >= 36525) {
            $g_day_no--;
            $gy += 100*$this->div($g_day_no,  36524);
            $g_day_no = $g_day_no % 36524;

            if ($g_day_no >= 365)
                $g_day_no++;
            else
                $leap = false;
        }

        $gy += 4*$this->div($g_day_no, 1461);
        $g_day_no %= 1461;

        if ($g_day_no >= 366) {
            $leap = false;

            $g_day_no--;
            $gy += $this->div($g_day_no, 365);
            $g_day_no = $g_day_no % 365;
        }

        for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
            $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
        $gm = $i+1;
        $gd = $g_day_no+1;

        return array($gy, $gm, $gd);

    }

}