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
		
	    $registry = Zend_Registry::getInstance();
	    $config = $registry->get(Pandamp_Keys::REGISTRY_APP_OBJECT);
	    $cdn = $config->getOption('cdn');
	    
	    $sDir = $cdn['static']['url']['images'];
	    
	    $content = 0;
	    $entries = array();
	    
		$rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4aaa29322bdbb',0,10);
		foreach ($rowset as $row)
		{
		    $rowsetRelatedItem = App_Model_Show_RelatedItem::show()->getDocumentById($row['guid'],'RELATED_IMAGE');
		    $itemGuid = (isset($rowsetRelatedItem['itemGuid']))? $rowsetRelatedItem['itemGuid'] : '';
			$chkDir = $sDir."/".$row['guid']."/".$itemGuid;
			if (@getimagesize($chkDir))
			{
				$pict = $sDir ."/". $row['guid'] ."/". $itemGuid;
			}
			else
			{
				$pict = $sDir ."/". $itemGuid;
			}
			
			
			if (Pandamp_Lib_Formater::thumb_exists($pict . ".jpg")) { $thumb = $pict . ".jpg"; }
			if (Pandamp_Lib_Formater::thumb_exists($pict . ".gif")) { $thumb = $pict . ".gif"; }
			if (Pandamp_Lib_Formater::thumb_exists($pict . ".png")) { $thumb = $pict . ".png"; }

			$entries[$content]['title'] 		= App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
			$entries[$content]['link']			= ROOT_URL.'/berita/baca/'.$row['guid'].'/'.$row['shortTitle'];
			$entries[$content]['guid']			= ROOT_URL.'/berita/baca/'.$row['guid'].'/'.$row['shortTitle'];
			$entries[$content]['pubDate']		= strtotime($row['publishedDate']);
			
			if (!empty($thumb)) {
				$d = "<img src=\"".$thumb."\" align=\"left\" hspace=\"7\" width=\"100\" />".$this->limitword(2,App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedContent'));	
				$entries[$content]['description']	= $d;
				$size = getimagesize($thumb);
				$entries[$content]['enclosure'] = array(
					array(
						'url' 		=> $thumb,
						'type'		=> $size['mime'],
						'length'	=> '10240'
					)
				);
			}
			else 
			{
				$d = $this->limitword(2,App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedContent'));
				$entries[$content]['description']	= $d;
			}
		
			$content++;
		}
		
		$feed = Zend_Feed::importArray(array(
	        'title'   		=> 'Hukumonline Warta',
	        'link'    		=> ROOT_URL,
	        'description'	=> 'hukumonline.com sindikasi',
	        'image'			=> $cdn['static']['images'].'/logo.gif',
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