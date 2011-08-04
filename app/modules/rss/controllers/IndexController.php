<?php

class Rss_IndexController extends Zend_Controller_Action 
{
	function preDispatch()
	{
		//$this->_helper->layout->setLayout('layout-rss');
	}
	function indexAction()
	{
		$this->_helper->layout->disableLayout();
		$this->getHelper('viewRenderer')->setNoRender(true); 
		
		$rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4aaa29322bdbb',0,10);
		foreach ($rowset as $row)
		{
			$entries[] = array(
				'title' 		=> App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle'),
				'link'			=> ROOT_URL.'/berita/baca/'.$row['guid'].'/'.$row['shortTitle'],
				'description'	=> $this->limitword(2,App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedContent')),
				'lastUpdate'	=> strtotime($row['publishedDate'])
			);
		}
		
//	    $registry = Zend_Registry::getInstance();
//	    $config = $registry->get(Pandamp_Keys::REGISTRY_APP_OBJECT);
//	    $cdn = $config->getOption('cdn');
	    
//		$img = array('link'=>'ff','url'=>'http://rss.detik.com/images/rsslogo_detiknews.gif','title'=>'HukumonlineBerita');
		
		$feed = Zend_Feed::importArray(array(
	        'title'   		=> 'RSS Feed Hukumonline',
	        'link'    		=> ROOT_URL,
	        'description'	=> 'hukumonline.com sindikasi',
	        'image'			=> array(
	        	array(
	        	'link'=>'ff',
	        	'url'=>'http://rss.detik.com/images/rsslogo_detiknews.gif',
	        	'title'=>'HukumonlineBerita'
	        	)
	        ),
	        'charset' 		=> 'UTF-8',
	        'entries' 		=> $entries
		), 'rss');
		    
		$feed->send();
	}
	private function limitword($sentence_num = 1, $content) 
	{
		if ($sentence_num == 0) {
			return false;
		}
		if ($sentence_num >= 1) {
			$sentence_num = $sentence_num-1;
		}
		$content = strip_tags($content);
		$pos = strpos($content, '.');
		for($i=1; $i<=$sentence_num; $i++) {
			$pos = strpos($content, '.', $pos+1);
		}
		return substr($content,0,$pos+1) ;
	}
}