<?php
/**
 * ChurchEvents
 *
 * Copyright 2011 by Joshua Gulledge <jgulledge19@hotmail.com>
 *
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
 * calendarGrid class
 * creates a calendar grid for a month
 * See doc tags: http://manual.phpdoc.org/HTMLframesConverter/default/
 * 
 * @param modX $modx
 * @param calendar
 * @param int $month
 * @param int $year
 * @param boolean $sun true will show day in the grid view
 * @param boolean $sat true will show day in the grid view
 * @param boolean $mon true will show day in the grid view
 * @param boolean $tue true will show day in the grid view
 * @param boolean $wed true will show day in the grid view
 * @param boolean $thu true will show day in the grid view
 * @param boolean $fri true will show day in the grid view
 *  
 */
class calendarGrid {
    /**
     * A reference to the Calendar instance
     * @var calendar $calendar
     */
    public $calendar;
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;
    /**
     * @var int $year YYYY
     * @access protected
     */
    protected $year = NULL;
    /**
     * @var int $month
     * @access protected
     */
    protected $month = NULL;
    /**
     * @var boolean $add_link
     * @access protected
     */
    protected $add_link = false;
    /**
     * @var string $params, any parms for generated links
     * @access protected
     */
    protected $params=NULL;
    /**
     * 
     * @access protected
     */
    protected $view = 'add';
    /**
     * @var array $day_array
     * @access protected
     */
    protected $day_array = array();
    /**
     * @var array $month_array
     * @access protected
     */
    protected $month_array = array();
    /**
     * @var array $show_day
     * @access protected
     */
    protected $show_day = array();
    /**
     * @param modX $modx
     * @param calendar
     * @param int $month
     * @param int $year
     * @param boolean $sun true will show day in the grid view
     * @param boolean $sat true will show day in the grid view
     * @param boolean $mon true will show day in the grid view
     * @param boolean $tue true will show day in the grid view
     * @param boolean $wed true will show day in the grid view
     * @param boolean $thu true will show day in the grid view
     * @param boolean $fri true will show day in the grid view
     *  
     */
	public function __construct(modX $modx, $calendar, $month, $year, $sun=true, $sat=true, $mon=true, $tue=true, $wed=true, $thu=true, $fri=true) {
	    $this->modx = &$modx;
        $this->calendar = &$calendar;
        
        $this->year = $year;
        $this->month = $month;# numeric values
        # show the day on the calendar if true
        $this->show_day[1] = $mon;
        $this->show_day[2] = $tue;
        $this->show_day[3] = $wed;
        $this->show_day[4] = $thu;
        $this->show_day[5] = $fri;
        $this->show_day[6] = $sat;
        $this->show_day[7] = $sun;
        
        // set the days
        $this->day_array = array(
                1=>'Monday',
                2=>'Tuesday',
                3=>'Wednesday',
                4=>'Thursday' ,
                5=>'Friday' ,
                6=>'Saturday',
                7=>'Sunday' );
        // set the months
        $this->month_array = array(
                1=>'January',
                2=>'February',
                3=>'March',
                4=>'April' ,
                5=>'May' ,
                6=>'June' ,
                7=>'July',
                8=>'August',
                9=>'September',
                10=>'October',
                11=>'November' ,
                12=>'December' );
        
	}
    /**
     * @param string $params
     * @param boolean $boolean, true will create an add event link
     * @return void
     */
    public function setAddLink($params, $boolean=true) {
        $this->params = $params;
        $this->add_link = $boolean;
    }
    /**
     * @param string $params
     * @param boolean $boolean, true will create an request event link
     * @return void
     */
    public function setRequestLink($params, $boolean=true) {
        $this->params = $params;
        $this->add_link = $boolean;
        $this->view = 'request';
    }
    /**
     * @param array $event_array, an erray of events
     * @param int $month
     * @param int $year, YYYY
     * @return string of calendar grid for the month
     */
    public function getMonth($event_array=NULL, $month=NULL, $year=NULL) {
        if($month != NULL){
            $this->month = $month;
        }
        if($year != NULL){
            $this->year = $year;
        }
        $this->event_array = $event_array;
        //print_r($this->event_array);
        $month_days = date("t", mktime(0, 0, 0, $this->month, 1, $this->year));// days in the current month
        //echo '<br />Days of Month: '.$month_days;
            # N - numeric representation of the day of the week (added in PHP 5.1.0) - 1 (for Monday) through 7 (for Sunday)
            # org - l "L" - A full textual representation of the day of the week
        # get the offset
        $offset = date("N", mktime(0, 0, 0, $this->month, 1, $this->year));//
        if( $offset == 7){
            $offset = 0;
        }
        //echo '<br />Offset: '.$offset;
        # get the pre filler start date
        if( $this->month > 1){
            $pre_month = $this->month - 1;
            $pre_year = $this->year;
        }else{
            $pre_month = 12;
            $pre_year = $this->year - 1;
        }
        $pre_day = date("t", mktime(0, 0, 0, $pre_month, 1, $pre_year)) - $offset + 1;//day is the previous month
        # get the pre filler start date
        if( $this->month < 12 ){
            $after_month = $this->month + 1;
            $after_year = $this->year;
        }else{
            $after_month = 1;
            $after_year = $this->year + 1;
        }
        $after_len = 7 - ($month_days + $offset)%7;
        if($after_len == 7){
            $after_len = 0;
        }
        $str = '
        <table class="calendar">
        	<tr>';
        # Make the table headers
        if( $this->show_day[7]){ // this is sunday
            $str .= '
                <th>'.$this->day_array[7]."</th>";
        }
        for ($count = 1; $count < 7; $count++) {
            if( $this->show_day[$count]){
            $str .= '
                <th>'.$this->day_array[$count]."</th>";
            }
        }
        $str .= '
            </tr>
            <tr>';
        ## pre filler columns
        for ($count = 0; $count < $offset; $count++) {
            $tmp = $count;
            if( $tmp == 0){
                $tmp = 7;
            }
            if( $this->show_day[$tmp]) {
                $tmp_date =  $pre_year.'-'.$pre_month.'-'.$pre_day;//YYYY-MM-DD
                $str .=  '
                <td class="grey">
            		<div class="dayWrapper">
            			<span class="date">'.$pre_day.'</span>
            			'.( $this->add_link ? '<a class="addEvent" href="'.CH_URL_BASE.'view='.$this->view.'&amp;start_date='.$pre_year.'-'.$pre_month.'-'.$pre_day.'&amp;'.$this->params.'" title="Add event to '.$tmp_date.'">[ + ]</a>' : '' ).'
            			'.$this->eventList($tmp_date).'
            		</div>
            	</td>';
            }
            $pre_day++;
        }
        ## the day columns
        for ($count = 1; $count <= $month_days; $count++) {
            $cur_day = ($count + $offset)%7;//0 to 6 - 0 is saturday, sunday, mon, tue, wed, thr, fri
            if( $cur_day == 1) {// Sunday = 1  - start new row
                $str .= '
            </tr>
            <tr>';
            }
            # reassign to day array
            if($cur_day == 0){
                $cur_day = 6;//Saturday
            }
            elseif($cur_day == 1){
                $cur_day = 7;//Sunday
            }
            else{
            	$cur_day -= 1;//Set to proper day
            }
            if( $this->show_day[$cur_day]){
                $str .= '
				<td>
					<div class="dayWrapper">
						<span class="date">'.$count.'</span>
						'.( $this->add_link ? '<a class="addEvent" href="'.CH_URL_BASE.'view='.$this->view.'&amp;start_date='.$this->year.'-'.$this->month.'-'.$count.'&amp;'.$this->params.'" title="Add event to '.$this->month.'/'.$count.'/'.$this->year.'">[ + ]</a>' : '' ).'
						'.$this->eventList($this->year.'-'.$this->month.'-'.$count).'
					</div>
				</td>';
				//' CUR: '.(($count + $offset)%7).' - Count: '.$count.' - OFF: '.$offset.
            }
        }
        if( $cur_day == 7 ){
            $cur_day = 0;//set to 0 since the loop advances before use!
        }
        ## after filler columns
        for ($count = 1; $count <= $after_len; $count++) {
            $cur_day++;
            if( $this->show_day[$cur_day]){ // keeping from above
                $tmp_date =  $after_year.'-'.$after_month.'-'.$count;//YYYY-MM-DD
                $str .=  '
                <td class="grey">
                	<div class="dayWrapper">
                		<span class="date">'.$count.'</span>
                		'.( $this->add_link ? '<a class="addEvent" href="'.CH_URL_BASE.'view='.$this->view.'&amp;start_date='.$after_year.'-'.$after_month.'-'.$count.'&amp;'.$this->params.'" title="Add event to '.$tmp_date.'">[ + ]</a>' : '' ).'
                		'.$this->eventList($tmp_date).'
                	</div>
                </td>';
            }
        }
        
        $str .= '
        	</tr>
        </table>';	
        return $str;
    }
    /**
     * @param $date YYYY-MM-DD
     * @return string $str this is a list of the days events
     */
    public function eventList($date){
        # this gets the events for that day
        $str = NULL;
        if( is_array($this->event_array) ){
        	// fix date:
        	list($y, $m, $d) = explode('-', $date);
        	$date = $y.'-'.( strlen($m)==1 ? '0'.$m : $m ).'-'.( strlen($d)==1 ? '0'.$d : $d );
        	$day_array = $this->event_array[$date];
        	//echo '<br>'.$day_array;
        	//print_r($this->event_array);
        	if( is_array($day_array) ){
        		# go thourgh the events
        		$str = '
        		<ul class="dayList">';
        		foreach($day_array as $e_id => $array){
        			$str .= '
        			<li id="e_'.$e_id.'" class="'.$array['class'].'">
        				'.$array['event'].'
        			</li>';
        		}
        		$str .= '
        		</ul>';
        	} else{
        		$str = $day_array;
        	}
        }
        return $str;
    }
	/**
     * generate navigation links for the calendar
     * @param chunk $chunk
     * @param string $param
     * @return string str the processed chunk
     */
    public function nav($chunk=NULL, $params=NULL ){
		$nav .= '
		<ul id="cal_nav">
			';
		# previous
		if( $this->month > 1){
			$pre_month = $this->month - 1;
			$pre_year = $this->year;
		}else{
			$pre_month = 12;
			$pre_year = $this->year - 1;
		}
		$nav .= '<li class="previous">&lt; <a href="'.CH_URL_BASE.'month='.$pre_month.'&amp;year='.$pre_year.$params.'">Previous</a></li>'; 
		# month name
		$nav .= '<li class="title">'.$this->month_array[$this->month].' '.$this->year.'</li>';
		# next
		if( $this->month < 12 ){
			$next_month = $this->month + 1;
			$next_year = $this->year;
		}else{
			$next_month = 1;
			$next_year = $this->year + 1;
		}
		$nav .= '
		<li class="next"><a href="'.CH_URL_BASE.'month='.$next_month.'&amp;year='.$next_year.$params.'">Next</a> &gt;</li>
		</ul>'; 
		
		return $nav;
	}
}
?>