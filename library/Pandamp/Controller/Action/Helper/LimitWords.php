<?php
class Pandamp_Controller_Action_Helper_LimitWords extends Zend_Controller_Action_Helper_Abstract
{
    function limitWords($string, $num_words=200)
    {
		$string = strip_tags($string);
		
		if (strlen($string) > $num_words) {
		
		    // truncate string
		    $stringCut = substr($string, 0, $num_words);
		
		    // make sure it ends in a word so assassinate doesn't become ass...
		    $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
		}
		
		return $string;
    }
}