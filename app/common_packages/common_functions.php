<?php

	function ch($arr) {  
		echo '<pre>';  
		print_r($arr);  
		echo '</pre>';  
	}
	
	function niceShort($date_string) {
        $date = $date_string ? strtotime($date_string) : time();

        if (date('Y-m-d', $date) == date('Y-m-d', time())) {
            // is today
            $ret = sprintf(__('Today, %s', true), date('H:i', $date));
        } elseif (date('Y-m-d', $date) == date('Y-m-d', strtotime('yesterday'))) {
            // was yesterday
            $ret = sprintf(__('Yesterday, %s', true), date('H:i', $date));
        } else {
            $ret = date('d/m/Y H:i', $date);
        }
        return $ret;
    }
	
?>