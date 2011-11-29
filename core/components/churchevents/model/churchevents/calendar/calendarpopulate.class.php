<?php
/**
 * ChurchEvents
 *
 * Copyright 2011 by Joshua Gulledge <jgulledge19@hotmail.com>
 *
 * ChurchEvents is under the terms of the GNU General Public License
 *
 * ChurchEvents is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * @package churchevents
 */
/**
 * This class is does the math calculations to populate repeating events
 * 
 */
class calendarPopulate {
    /**
     * The year to populate the event, YYYY
     * @var int $year
     * @access protected
     */
	protected $year = NULL;
    /**
     * The month, MM
     * @var int $month
     * @access protected
     */
	protected $month = NULL;
    /**
     * the time zone
     * @var string $time_zone
     * @access protected
     */
	protected $time_zone = NULL;
    /**
     * An array of data 
     * @var array $month_grid
     * @access protected
     */
	protected $month_grid = array();
	/**
     * @return calendarPopulate
     */
	public function __construct() {
	    // set the default time zone:
	    $this->time_zone = date_default_timezone_get();
	    //date_default_timezone_set('Etc/GMT-0');// 'Europe/London');// Etc/GMT-0
	}
    /*
     * Populate, this will populate a calendar baised on how it should repeat
     * @param $start_date YYYY-MM-DD
     * @param $end_date YYYY-MM-DD
     * @param $repeat_type - daily, weekly, monthly
     * @param int $interval how often event should be repeated
     * @param array $days (can also be NULL) containing 1 (for Monday) through 7 (for Sunday) PHP 5.1+
     */
    public function populate($start_date, $end_date, $repeat_type, $interval, $days ){
        // convert start and end dates to time UNIX
        $start = $start_date;
        if ( !is_numeric($start_date) ){
            list($y,$m,$d) = explode("-", $start_date);
            $start = mktime(1, 0, 0, $m, $d, $y);// note we need to add an hour to prevent daylight savings errors.
        }
        $end = $end_date;
        if ( !is_numeric($end_date) ){
            list($y,$m,$d) = explode("-", $end_date);
            $end = mktime(1, 0, 0, $m, $d, $y);
        }
        //echo '<br>Populate: '.$repeat_type.' on line: '.__LINE__;
        //echo '<br>Start: '.$start_date.'('.$start.') <br>End: '.$end_date.'('.$end.') <br>Repeat: '.$repeat_type.'<br> Int: '.$interval.'<br>';
        //, $days ){
        //
        if ( $repeat_type == 'daily' ){
            return $this->repeatDaily($interval, $start, $end, $days=NULL);
        } elseif ( $repeat_type == 'weekly' ){
            return $this->repeatWeekly($interval, $start, $end, $days );
        } else{
            $this->month_grid = $days;
            return $this->repeatMonthly($interval, $start, $end );
        }
	}
    /**
     * set the time zone
     */
    public function timeZone() {
        // set the default time zone:
        date_default_timezone_set($this->time_zone);
    }
    /*
     * Repeat event daily
     * @param int $interval how often event should be repeated
     * @param int $start UNIX timestamps
     * @param int $end UNIX timestamps
     * @param array $days (can also be NULL) containing 1 (for Monday) through 7 (for Sunday) PHP 5.1+
     * @return array() $populated_days
     */
    private function repeatDaily($interval, $start, $end, $days){
        $populated_days = array();
        $inc = 3600*24*$interval;// 60 sec * 60 min * 24 hrs = 1 day
    
        for( $d=$start; $d<=$end; $d+=$inc ){
            $date = new DateTime('@'.$d );
            $date->setTimezone(new DateTimeZone($this->time_zone));
            $tz = $date->getTimezone();
            //echo '<br>Time Zone: '.$tz->getName();
            //$date->setTimestamp($d);
            $wk_day = $date->format('N');// ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0)
            //echo '<br> -- D: '.$d.' - '.$wk_day.' - '.$date->format('Y-m-d');
            if ( $days == NULL || ( is_array($days) && in_array($wk_day, $days) ) ) {
                $populated_days[] = $date->format('Y-m-d');
            }
        }
        return $populated_days;
    }
    
    /*
     * Repeat event weekly
     * @param int $interval how often event should be repeated
     * @param int $start UNIX timestamps
     * @param int $end UNIX timestamps
     * @param array $days (can also be NULL) containing 1 (for Monday) through 7 (for Sunday) PHP 5.1+
     * @return array() $populated_days
     */
    private function repeatWeekly($interval, $start, $end, $days ){
        $populated_days = array();
        $week = 3600*24*7;// 60 sec * 60 min * 24 hrs * 7 days
        $inc = $week*$interval;
        $x=1;
        for( $d=$start; $d<=$end; $d+=$inc ){
            //echo '<br>Week '.$x++;
            $day_end = $d + $week -3600*24;// add just 6 days
            if ( $day_end > $end ){
            	$day_end = $end;
            }
            $new = $this->repeatDaily(1, $d, $day_end, $days);
            $populated_days = array_merge($populated_days,$new);
        }
        return $populated_days;
	}
    
    /*
     * Repeat event monthly
     * @param int $interval how often event should be repeated
     * @param int $start UNIX timestamps
     * @param int $end UNIX timestamps
     * @return array() $populated_days
     */
    private function repeatMonthly($interval, $start, $end){
        $return = false;
        $populated_days = array();
        $week = 3600*24*7;// 60 sec * 60 min * 24 hrs * 7 days
        $inc = $week*$interval;
        // change start to the first day of the month:
        // $date = new DateTime();
        //$date->setTimestamp($start);// this is PHP 5.3!
        //echo '<br>repeatMonthly() Line: '.__LINE__;
        //echo '<br>Start: '.$start.' <br>End: '.$end.' <br> Int: '.$interval.'<br>';
        
        $d=$start;
        //for( $d=$loop_start; $d<=$end; $d+=$inc ){
        while ( $d<=$end ) {
            //echo '<br>D: '.$d;
            $date = new DateTime('@'.$d );
            $date->setTimezone(new DateTimeZone($this->time_zone));
            //$date->setTimestamp($d);
            $m = $date->format('n');// 1 to 12
            $y = $date->format('Y');// 1 to 12
            //$date->setTimestamp( mktime(0, 0, 0, $m, 1, $y) );
            // 1. get days in the current month
            $month_days = $date->format("t");
            // week 1, get first day of week and last day of week:
            $st_wk = mktime(1, 0, 0, $m, 1, $y);
            //echo '<br>M: '.$m.'<br>Y: '.$y.'<br>st_wk: '.$st_wk;
            //$st_wk = $date->getTimestamp();
            // N - numeric representation of the day of the week (added in PHP 5.1.0) - 1 (for Monday) through 7 (for Sunday)
            // get the offset, first day (Mon, Tue..) of the month
            $offset = date("N", $st_wk);//$date->format("N");//
            if( $offset == 7){
            	$offset = 0;
            }
            // last day of month:
            $last_month_date = $st_wk+3600*24*($month_days-1);
            //echo '<br>Month: '.$m.' Days: '.$month_days.' Last: '.date('Y-m-d', $last_month_date).' ('.$last_month_date.')';
            //echo '<br>$last_month_date: '.$last_month_date.' <br>MD: '.$month_days;
            //$date->setTimestamp( $st_wk+3600*24*(6-$offset) );// just add 6 days!
            $ed_wk = $st_wk+3600*24*(6-$offset);
            $days_of_month = $this->adjustGrid($offset); 
            //$ed_wk = $date->getTimestamp();
            if ( $st_wk < $start && $ed_wk < $start || !isset($days_of_month[1]) ){
            	// dont run this week
            } else {
                if ( $st_wk < $start ){
                	$st_wk = $start;
                }
                if ( $ed_wk > $end ){
                	$ed_wk = $end;
                	// need to return:
                	$return = true;
                }
                $new = $this->repeatDaily(1, $st_wk, $ed_wk, $days_of_month[1]);
                $populated_days = array_merge($populated_days,$new);
                if ( $return ){
                	return $populated_days;
                }
            }
            // week 2 to 4: (these are always the same)
            for( $x=2; $x<=6; $x++ ){
                $st_wk = $ed_wk + 3600*24;
                $ed_wk = $st_wk + 3600*24*6;// just add 6 days!
                if ( $st_wk > $last_month_date ) {
                    continue;
                }
                //echo '<br>Pre: '.$x.' '.$st_wk.' - '.$ed_wk.' '.$start.' '.$end;
                if ( $st_wk < $start && $ed_wk < $start || !isset($days_of_month[$x]) ){
                	// dont run this week
                	//echo '<br>Dont Run: '.$x;
                } else{
                    if ( $st_wk < $start ){
                        $st_wk = $start;
                    }
                    if ( $ed_wk > $end ){
                        $ed_wk = $end;
                        // need to return:
                        $return = true;
                    }
                    if ( $ed_wk > $last_month_date ) {
                        $ed_wk = $last_month_date;
                    }
                    //echo '<br>Week Run: '.$x.' '.$st_wk.' - '.$ed_wk.' '.$start.' '.$end.' Last: '.$last_month_date;
                    $new = $this->repeatDaily(1, $st_wk, $ed_wk, $days_of_month[$x]);
                    $populated_days = array_merge($populated_days,$new);
                    if ( $return ){
                        return $populated_days;
                    }
                }
            }
            // week 5, need to get the last day of this month:
            /*
            $last_month_date;
            $st_wk = $ed_wk+ 3600*24;
            $ed_wk = $last_month_date;
            if ( ( $st_wk < $start && $ed_wk < $start) || $st_wk > $last_month_date  || !isset($days_of_month[5]) ){
            	// dont run this week
            } else{
            	if ( $st_wk < $start ){
            		$st_wk = $start;
            	}
            	if ( $ed_wk > $end ){
            		$ed_wk = $end;
            		// need to return:
            		$return = true;
            	}
            	$new = $this->repeatDaily(1, $st_wk, $ed_wk, $days_of_month[5]);
            	$populated_days = array_merge($populated_days,$new);
            	if ( $return ){
            		return $populated_days;
            	}
            }
            */
            // now advance by the interval, which is how many months to repeat
            if ( $interval == 1 ){
                //echo '<br>$last_month_date: '.$last_month_date;
                $d = $last_month_date+3600*24;// first day of next month
            } else {
                if ( $interval == 12 ){
                    ++$y;
                } else {
                    // advance months and years:
                    $m += $interval;
                    if ( $m > 12 ){
                    $m -= 12;
                       ++$y;
                   }
                }
            	$d = mktime(1, 0, 0, $m, 1, $y);
            }
        }
        return $populated_days;
    }
    
    /*
     * Set filler days for before and after month
     * @param int $offset when to start the date
     * @return array() $days_of_month
     */
    protected function adjustGrid( $offset ){
        $days = $this->month_grid;
        //echo '<br>O: '.$offset;
        // can be 0 to 34 ( 5 possible weeks times 7 days )
        foreach($days as $d ){
            $day = $d;
            if ( $d > 6 ){
                $day = $d%7;
                if ( $day == 0 ){
                   $day = 7;// sunday;
                }
            }
            if ( $d == 0 ){
                $week = 1;// not always true?
                $day = 7;
            } else {
                $week = (floor($d/7)+1);
            }
            //echo '<br>D: '.$d.' - W: '.$week;
            if ( $offset > $day || ( $day==7 && $offset > 0 ) ){
                $week += 1;
            }
            //echo ' - W2: '.$week;
            // week is 1 to 5
            $days_of_month[$week][] = $day;
        }
        //print_r($days_of_month);
       	
        return $days_of_month;
	}
}
?>
