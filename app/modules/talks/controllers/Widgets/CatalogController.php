<?php

/**
 * Description of CatalogController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Talks_Widgets_CatalogController extends Zend_Controller_Action
{
    function detailAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $folderGuid = ($this->_getParam('folderGuid'))? $this->_getParam('folderGuid') : '';
        $start		= ($this->_getParam('start'))? $this->_getParam('start') : 0;
        $limit		= ($this->_getParam('limit'))? $this->_getParam('limit') : 0;
        
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder($folderGuid,$start,$limit);

        $content = 0;
        $data = array();
        $array_hari = array(
            1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"
        );

        $a['folderGuid'] = $folderGuid;
        $a['totalCount'] = count($rowset);

        $ii = 0;

        if ($a['totalCount']!=0)
        {
            foreach ($rowset as $row)
            {
			    $registry = Zend_Registry::getInstance();
			    $config = $registry->get(Pandamp_Keys::REGISTRY_APP_OBJECT);
			    $cdn = $config->getOption('cdn');
			    $sDir = $cdn['static']['url']['images'];
			    $smg = $cdn['static']['images'];
//			    $sDir = 'uploads/images';
			    $thumb = "";
			
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
				
			
				if (Pandamp_Lib_Formater::thumb_exists($pict . ".jpg")) 	{ $thumb = $pict . ".jpg"; 	}
				if (Pandamp_Lib_Formater::thumb_exists($pict . ".gif")) 	{ $thumb = $pict . ".gif"; 	}
				if (Pandamp_Lib_Formater::thumb_exists($pict . ".png")) 	{ $thumb = $pict . ".png"; 	}
			
			    if ($thumb == "") { $thumb = $smg."/slider/image1.jpg"; }
			
			    $screenshot = "<img src=\"".$thumb."\"  vspace=\"0\" width=\"104\" border=\"0\" hspace=\"0\" align=\"left\" />";
			
            	
                $a['index'][$ii]['title'] 	= App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
                $a['index'][$ii]['shortTitle']	= $row['shortTitle'];
                $a['index'][$ii]['guid']	= $row['guid'];
                $a['index'][$ii]['desc']	= App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
	            $hari = $array_hari[date("N", strtotime($row['publishedDate']))];
                $a['index'][$ii]['publish']	= $hari . ', '. date("d F Y",strtotime($row['publishedDate']));
                $a['index'][$ii]['images']	= (isset($thumb))? '<div class="width1 column first ta-center">'.$screenshot.'</div>' : "";
                $ii++;
            }
        }
        if ($a['totalCount'] == 0)
        {
            $a['index'][0]['title'] = "-";
            $a['index'][0]['shortTitle'] = "-";
            $a['index'][0]['guid'] = "-";
            $a['index'][0]['publish'] = "-";
            $a['index'][0]['desc'] = "-";
        }
        echo Zend_Json::encode($a);
    }
    
	/*
    function detailAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4a607b5e3c2f2',0,10);

        $content = 0;
        $data = array();
        $array_hari = array(
            1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"
        );

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['guid'];
            $data[$content][2] = $row['shortTitle'];
            $data[$content][3] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
            $hari = $array_hari[date("N", strtotime($row['publishedDate']))];
            $data[$content][4] = $hari . ', '. date("d F Y",strtotime($row['publishedDate']));

            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    */
    function ueventAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4c93230c9d0a5',0,10);

        $content = 0;
        $data = array();
        $array_hari = array(
            1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"
        );

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['guid'];
            $data[$content][2] = $row['shortTitle'];
            $data[$content][3] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
            $hari = $array_hari[date("N", strtotime($row['publishedDate']))];
            $data[$content][4] = $hari . ', '. date("d F Y",strtotime($row['publishedDate']));
            $data[$content][5] = $row['price'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function testimonialAction()
    {
        
    }
}
