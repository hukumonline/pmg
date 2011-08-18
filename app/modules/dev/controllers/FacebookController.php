<?php

class Dev_FacebookController extends Zend_Controller_Action 
{
	function metadataAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
		
	    $url = 'http://developers.facebook.com/tools/lint/?url={http://www.hukumonline.com/berita/baca/lt4e4a964227335/sby-minta-jangan-lemahkan-kpk}&format=json';
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_NOBODY, true);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_exec($ch);
	    $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    if (200==$retcode) {
	        echo 'Yes';
	    } else {
	        echo 'No';
	    }

		
	}
}