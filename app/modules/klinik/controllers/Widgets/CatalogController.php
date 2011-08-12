<?php

/**
 * Description of CatalogController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Klinik_Widgets_CatalogController extends Zend_Controller_Action
{
    function klideAction()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $ip = Pandamp_Lib_Formater::getRealIpAddr();

        $rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($catalogGuid);

        if ($rowset)
        {
                $rowAsset = App_Model_Show_AssetSetting::show()->getAssetNumOfClick($catalogGuid);
                $data = array(
                        'guid'		=> $catalogGuid,
                        'application'	=> 'ASSET',
                        'part'		=> 'MOST_READABLE_CLINIC',
                        'valueType'	=> $ip,
                        'valueInt'	=> 1,
                        'valueText'	=> 'klinik'
                );
                $asset = App_Model_Show_AssetSetting::show()->addCounterAsset($rowset['guid'],$data);

                $title = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedCommentTitle');
                $question = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedCommentQuestion');
                $category = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedKategoriKlinik');
                $answer = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedAnswer');
                $author = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedSelectNama');
                $source = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedSelectMitra');
                
                $dateDiff = new Pandamp_Lib_DateDiff();

                $this->view->title 		= (isset($title))? $title : '';
                $this->view->question 		= (isset($question))? $question : '';
                $this->view->category 		= (isset($category))? $category : '';
                $this->view->answer 		= (isset($answer))? $answer : '';
                $this->view->author 		= (isset($author))? $author : '';
                $this->view->source 		= (isset($source))? $source : '';
                $this->view->createdBy 		= $rowset['createdBy'];
                //$this->view->publishedDate	= Pandamp_Lib_Formater::get_date($rowset['publishedDate']);
                //$this->view->publishedDate	= $dateDiff->ago(strftime('%Y-%m-%d %H:%M:%S',strtotime($rowset['publishedDate'])));
                $this->view->publishedDate	= date("d/m/Y",strtotime($rowset['publishedDate']));
                $this->view->createdDate	= $dateDiff->ago(strftime('%Y-%m-%d %H:%M:%S',strtotime($rowset['createdDate'])));
                $this->view->numofclick		= (isset($rowAsset))? $rowAsset['valueInt'] : 0;

                // get votes
                $rowRate = App_Model_Show_Vote::show()->getRating($catalogGuid,$ip);
                $val = ($rowRate)? $rowRate['value'] : 0;
                $counter = ($rowRate)? $rowRate['counter'] : 0;

                if ($counter < 1) {
                    $count = 0;
                } else {
                    $count=$counter; //how many votes total
                }
                $current_rating = $val;
                $tense=($count==1) ? "vote" : "votes"; //plural form votes/vote
                $rating = @number_format($current_rating/$count,1);

                $drawrating = '('.$count.' '.$tense.', average: '. $rating .' out of 5)';

                $this->view->drawrating = $drawrating;

                $this->view->catalogGuid = $catalogGuid;
        }
    }
    function detailmitraAction()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($catalogGuid);

        if ($rowset)
        {
            $this->view->title          = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedTitle');
            $this->view->content 	= App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedContent');
            $this->view->catalogGuid    = $catalogGuid;
        }
    }
    function authorAction()
    {
        $this->_helper->layout->disableLayout();
        $mkGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $query = "profile:author sumber:$mkGuid;publishedDate desc";

        $indexingEngine = Pandamp_Search::manager();
        $hits = $indexingEngine->find($query);

        $solrNumFound = count($hits->response->docs);

        $content = 0;
        $data = array();

        for($ii=0;$ii<$solrNumFound;$ii++) {
            $row = $hits->response->docs[$ii];
            $data[$content][0] = $row->title;
            $data[$content][1] = $row->subTitle;
            $data[$content][2] = $row->id;
            $content++;
        }

        $num_rows = $solrNumFound;

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
        $this->view->catalogGuid = $mkGuid;
    }
    function contributorAction()
    {
        $this->_helper->layout->disableLayout();
        
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $query = "profile:klinik sumber:$catalogGuid status:99";

        $indexingEngine = Pandamp_Search::manager();
        $hits = $indexingEngine->find($query);

        $solrNumFound = count($hits->response->docs);

        $num_rows = $solrNumFound;

        $limit = 20;

        $data['catalogGuid'] = $catalogGuid;
        $data['totalCount'] = $num_rows;
        $data['limit'] = $limit;

        $this->view->aData = $data;
    }
    function pengasuhAction()
    {
        $author = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($author);

        $this->view->author = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedTitle');
        $this->view->description = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedSubTitle');
    }
    function _viewerClinicAction()
    {
        $this->_helper->layout->disableLayout();
        
        $author = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        
        $dateDiff = new Pandamp_Lib_DateDiff();

        $query = "profile:klinik status:99 kontributor:$author;publishedDate desc";

        $indexingEngine = Pandamp_Search::manager();
        $hits = $indexingEngine->find($query);

        $solrNumFound = count($hits->response->docs);

        $content = 0;
        $data = array();

        for($ii=0;$ii<$solrNumFound;$ii++) {
            $row = $hits->response->docs[$ii];
            $data[$content][0] = $row->title;
            $data[$content][1] = $row->commentQuestion;
            $data[$content][2] = $row->kategoriklinik;
            $data[$content][3] = $row->id;
            $data[$content][4] = $row->createdBy;
            $data[$content][5] = $row->kontributor;
            $data[$content][6] = $row->sumber;
            $data[$content][7] = $dateDiff->ago(strftime('%Y-%m-%d %H:%M:%S',strtotime($row->publishedDate)));
            $content++;
        }

        $num_rows = $solrNumFound;

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function viewerClinicAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
		
        $a = array();

        $dateDiff = new Pandamp_Lib_DateDiff();
        
        $r = $this->getRequest();
        
        $author = ($r->getParam('folderGuid'))? $r->getParam('folderGuid') : '';
        $start	= ($r->getParam('start'))? $r->getParam('start') : 0;
        $limit	= ($r->getParam('limit'))? $r->getParam('limit') : 10;
        $sort 	= ($r->getParam('sort'))? $r->getParam('sort') : "publishedDate";
        $sortBy = ($r->getParam('by'))? $r->getParam('by') : "desc";
        
        
        $db = Zend_Db_Table::getDefaultAdapter()->query
        ("SELECT catalogGuid as guid from KutuCatalogAttribute where value='$author'");

        $rowset = $db->fetchAll(Zend_Db::FETCH_OBJ);
        
        $solrAdapter = Pandamp_Search::manager();

        $numi = count($rowset);
        $sSolr = "id:(";
        for($i=0;$i<$numi;$i++)
        {
            $row = $rowset[$i];
            $sSolr .= $row->guid .' ';
        }
        $sSolr .= ')';

        if(!$numi)
                $sSolr="id:(hfgjhfdfka)";

    	
		$solrResult = $solrAdapter->findAndSort($sSolr,$start,$limit, array($sort.' '.$sortBy));
		$solrNumFound = $solrResult->response->numFound;

        $a['folderGuid'] = $author;
        $a['totalCount'] = $numi;

        $ii=0;
        if($solrNumFound==0)
        {
            $a['index'][0]['title']= 'XXX';
            $a['index'][0]['question']= "No Data";
            $a['index'][0]['secat']= "";
            $a['index'][0]['category']= '';
            $a['index'][0]['guid']= '';
            $a['index'][0]['createdBy']= '';
            $a['index'][0]['author']= '';
            $a['index'][0]['sid']= '';
            $a['index'][0]['source']= '';
            $a['index'][0]['publishedDate']= '';
            $a['index'][0]['existence']= '';
        }
        else
        {
            if($solrNumFound>$limit)
                $numRowset = $limit ;
            else
                $numRowset = $solrNumFound;

            for($ii=0;$ii<$numRowset;$ii++)
            {
                $row = $solrResult->response->docs[$ii];
                if(!empty($row))
                {
				    $arraypictureformat = array("jpg", "jpeg", "gif");
				    $txt_allowedformat = implode('; ', $arraypictureformat);
				    $registry = Zend_Registry::getInstance();
				    $config = $registry->get(Pandamp_Keys::REGISTRY_APP_OBJECT);
				    $cdn = $config->getOption('cdn');
				    
				    $sDir = $cdn['static']['dir']['photo'];
				    $sDir2 = $cdn['static']['url']['photo'].'/';
				    $smg = $cdn['static']['images'];
				    
				    $modelUser = App_Model_Show_User::show()->getUserByName($row->createdBy);
				    
				    $x = 0;
				    foreach ($arraypictureformat as $key => $val) {
				        if (is_file($sDir."/".$modelUser['kopel'].".".$val)) {
				            $myphoto = $sDir."/".$modelUser['kopel'].".".$val;
				            $myext = $val;
				            $x = 1;
				            break;
				        }
				    }
				    if ($x == 1) {
				        $myphotosize = getimagesize($myphoto);
				        $dis = "";
				        if (isset($myext) && is_file($sDir."/".$modelUser['kopel'].".".$myext))
				            $txt_existence = "<img src=\"".$sDir2.$modelUser['kopel'].".".$myext."\" class=\"avatar\" width=\"38\" height=\"38\" />";
				
				    }
				    else
				    {
				        $txt_existence = "<img src=\"".$smg."/gravatar-140.png\" width=\"38\" height=\"38\" class=\"avatar\" border=\"0\" />";
				    }
				    
					$a['index'][$ii]['title'] = $row->title;
					$a['index'][$ii]['question'] = $row->commentQuestion;
					$a['index'][$ii]['secat'] = $row->kategoriklinik;
					$a['index'][$ii]['category'] = $row->kategori;
					$a['index'][$ii]['guid'] = $row->id;
					$a['index'][$ii]['createdBy'] = $row->createdBy;
					$a['index'][$ii]['author'] = $row->kontributor;
					$a['index'][$ii]['sid'] = $row->sumber;
					$a['index'][$ii]['source'] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row->kontributor,'fixedTitle');
		            //$a['index'][$ii]['publishedDate'] = $dateDiff->ago(strftime('%Y-%m-%d %H:%M:%S',strtotime($row->publishedDate)));
		            $a['index'][$ii]['publishedDate'] = date("d/m/Y",strtotime($row->publishedDate));
		            $a['index'][$ii]['existence'] = '<div style="float:left;padding:2px;margin: 1px 10px 10px 0px;"><a href="">'.$txt_existence.'</a></div>';
                }
            }
        }

        echo Zend_Json::encode($a);
    }
    function viewerClinic80Action()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
		
        $dateDiff = new Pandamp_Lib_DateDiff();
        
        $author = ($this->_getParam('folderGuid'))? $this->_getParam('folderGuid') : '';
        $start	= ($this->_getParam('start'))? $this->_getParam('start') : 0;
        $limit	= ($this->_getParam('limit'))? $this->_getParam('limit') : 10;

        $a = array();

		$tblCatalog = new App_Model_Db_Table_Catalog();
		$tblCatalogAttribute = new App_Model_Db_Table_CatalogAttribute();
		$clinic = $tblCatalogAttribute->fetchAll("value='".$author."'",'',$limit,$start);
		$clinic1 = $tblCatalogAttribute->fetchAll("value='".$author."'");

        $a['folderGuid'] = $author;
        $a['totalCount'] = count($clinic1);

        $ii = 0;

		
		if ($clinic)
		{ 
			$value_clinic = array();
			foreach ($clinic as $c)
			{
				$value_clinic[] = $c->catalogGuid;
			}
			
//			$value_clinic = $tblCatalog->implode_with_keys(", ", $value_clinic, "'");
			
			echo '<pre>';
			print_r($value_clinic);
			echo '</pre>';
			die;
			
			if (isset($value_clinic)) 
			{
				$rowset = $tblCatalog->fetchAll("guid IN($value_clinic) AND status=99","publishedDate desc");
				
				foreach ($rowset as $row)
				{
				    $arraypictureformat = array("jpg", "jpeg", "gif");
				    $txt_allowedformat = implode('; ', $arraypictureformat);
				    $registry = Zend_Registry::getInstance();
				    $config = $registry->get(Pandamp_Keys::REGISTRY_APP_OBJECT);
				    $cdn = $config->getOption('cdn');
				    
				    $sDir = $cdn['static']['dir']['photo'];
				    $sDir2 = $cdn['static']['url']['photo'].'/';
				    $smg = $cdn['static']['images'];
				    
				    $modelUser = App_Model_Show_User::show()->getUserByName($row->createdBy);
				    
				    $x = 0;
				    foreach ($arraypictureformat as $key => $val) {
				        if (is_file($sDir."/".$modelUser['kopel'].".".$val)) {
				            $myphoto = $sDir."/".$modelUser['kopel'].".".$val;
				            $myext = $val;
				            $x = 1;
				            break;
				        }
				    }
				    if ($x == 1) {
				        $myphotosize = getimagesize($myphoto);
				        $dis = "";
				        if (isset($myext) && is_file($sDir."/".$modelUser['kopel'].".".$myext))
				            $txt_existence = "<img src=\"".$sDir2.$modelUser['kopel'].".".$myext."\" class=\"avatar\" width=\"38\" height=\"38\" />";
				
				    }
				    else
				    {
				        $txt_existence = "<img src=\"".$smg."/gravatar-140.png\" width=\"38\" height=\"38\" class=\"avatar\" border=\"0\" />";
				    }
				    
					$rowsetCatalogAttribute = $row->findDependentRowsetCatalogAttribute(); 
					$rowCatalogTitle = $rowsetCatalogAttribute->findByAttributeGuid('fixedCommentTitle');
					$rowCatalogQuestion = $rowsetCatalogAttribute->findByAttributeGuid('fixedCommentQuestion');
					$rowCatalogSelectCat = $rowsetCatalogAttribute->findByAttributeGuid('fixedKategoriKlinik');
					$author = $rowsetCatalogAttribute->findByAttributeGuid('fixedSelectNama');
					$source = $rowsetCatalogAttribute->findByAttributeGuid('fixedSelectMitra');
					
					/* Get Category from profile clinic_category */
					$findCategory = $tblCatalog->find($rowCatalogSelectCat->value)->current();
					$rowCategory = $findCategory->findDependentRowsetCatalogAttribute();
					$category = $rowCategory->findByAttributeGuid('fixedTitle');
					
					$a['index'][$ii]['title'] = $rowCatalogTitle->value;
					$a['index'][$ii]['question'] = $rowCatalogQuestion->value;
					$a['index'][$ii]['secat'] = $rowCatalogSelectCat->value;
					$a['index'][$ii]['category'] = $category->value;
					$a['index'][$ii]['guid'] = $row->guid;
					$a['index'][$ii]['createdBy'] = $row->createdBy;
					$a['index'][$ii]['author'] = (isset($author->value))? $author->value : '';
		            if (isset($source->value)) {
		            	
						$findSource = $tblCatalog->find($source->value)->current();
						$rowSource = $findCategory->findDependentRowsetCatalogAttribute();
						$sc = $rowSource->findByAttributeGuid('fixedTitle');
						
		            	$a['index'][$ii]['source'] = $sc->value;
		            	$a['index'][$ii]['sid'] = $source->value;
		            }
		            else 
		            {
		            	$a['index'][$ii]['source'] = '';
		            	$a['index'][$ii]['sid'] = '';
		            }
		            $a['index'][$ii]['publishedDate'] = $dateDiff->ago(strftime('%Y-%m-%d %H:%M:%S',strtotime($row->publishedDate)));
		            $a['index'][$ii]['existence'] = '<div style="float:left;padding:2px;margin: 1px 10px 10px 0px;"><a href="">'.$txt_existence.'</a></div>';
		            

		            
		            
					$ii++;
				}
				
			}
		} 

		
		
		echo Zend_Json::encode($a);
    }
    function kategoriklinikAction()
    {
        $this->_helper->layout->disableLayout();
        
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $query = "kategoriklinik:$catalogGuid status:99";

        $indexingEngine = Pandamp_Search::manager();
        $hits = $indexingEngine->find($query);

        $solrNumFound = count($hits->response->docs);

        $num_rows = $solrNumFound;

        $limit = 20;

        $data['catalogGuid'] = $catalogGuid;
        $data['totalCount'] = $num_rows;
        $data['limit'] = $limit;

        $this->view->aData = $data;
    }

}
