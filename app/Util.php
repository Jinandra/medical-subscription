<?php
/**
 * Questwork PHP Development Framwork
 *
 * @author Tiep Nguyen <tiep@tiep.info>
 * @link http://tiep.info/questwork
 * @copyright Copyright (c) 2014, Questwork by Tiep Nguyen.
 */
namespace App;

/**
 * Class Util
 *
 * @package Questwork
 */
class Util
{
	/**
	 * Parse global requests into defined requests
	 *
	 * @param  array|mixed[] $request Array of defined requestes
	 * @return null
	 */
	
	public static function generateReferUrl($url, $scheme = 'http://')
    {
        return parse_url($url, PHP_URL_SCHEME) === null ?
   		$scheme . $url : $url;
		return $str;
    }
	
	public static function ago($date, $suffix = true)
	{
		if(empty($date)) 
		{
			return "No date provided";
		}
		
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");
		$now = time();
		$unix_date = strtotime($date);
		
		// check validity of date
		if(empty($unix_date))
		{    
			return "Bad date";
		}
		
		// is it future date or past date
		if($now > $unix_date) 
		{    
			$difference     = $now - $unix_date;
			$tense         = "ago";
			
		}
		else
		{
			 $difference     = $unix_date - $now;
			 $tense         = "from now";
		}
		
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++)
		{
			$difference /= $lengths[$j];
		}
		$difference = round($difference);
		
		if($difference != 1) 
		{
			$periods[$j].= "s";
		}
                
                $result_string = $difference.' '.$periods[$j];
                if($suffix) {
                    $result_string .= ' '.$tense;
                }
		
		return $result_string;
	}
}
