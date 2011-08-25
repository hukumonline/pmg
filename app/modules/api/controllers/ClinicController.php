<?php

/**
 * Description of ClinicController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Api_ClinicController extends Zend_Controller_Action
{
	function fetchClinicByContributorAction()
	{
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $dateDiff = new Pandamp_Lib_DateDiff();
		
        $r = $this->getRequest();

        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $start = ($r->getParam('start'))? $r->getParam('start') : 0;
        $limit = ($r->getParam('limit'))? $r->getParam('limit'): 20;
        $orderBy = ($r->getParam('orderBy'))? $r->getParam('sortBy') : 'publishedDate';
        $sortOrder = ($r->getParam('sortOrder'))? $r->getParam('sortOrder') : ' DESC';

        $query = "profile:klinik sumber:$catalogGuid status:99;publishedDate desc";

        $a = array();

        $a['query']	= $query;

        $vTitle = new Pandamp_Controller_Action_Helper_GetCatalogTitle();

        $indexingEngine = Pandamp_Search::manager();

        $hits = $indexingEngine->find($query, $start, $limit);

        $num = $hits->response->numFound;

        $solrNumFound = count($hits->response->docs);

        $ii=0;
        if($solrNumFound==0)
        {
            $a['klinikkategori'][0]['guid']= 'XXX';
            $a['klinikkategori'][0]['title']= 'Kategori klinik kosong';
            $a['klinikkategori'][0]['pertanyaan']= "";
            $a['klinikkategori'][0]['createdBy']= "";
            $a['klinikkategori'][0]['kategori']= '';
            $a['klinikkategori'][0]['author']= '';
            $a['klinikkategori'][0]['sumber']= '';
        }
        else
        {
            if($solrNumFound>$limit)
                $numRowset = $limit ;
            else
                $numRowset = $solrNumFound;

            for($ii=0;$ii<$numRowset;$ii++)
            {
                $row = $hits->response->docs[$ii];
                if(!empty($row))
                {
                    $a['klinikkategori'][$ii]['guid'] = $row->id;
                    $a['klinikkategori'][$ii]['title'] = $row->title;
                    //$a['klinikkategori'][$ii]['pertanyaan'] = Pandamp_Lib_Formater::string_limit_words($row->commentQuestion,30);
                    $a['klinikkategori'][$ii]['createdBy'] = $row->createdBy;
                    $a['klinikkategori'][$ii]['kategori'] = $row->kategori;
                    $a['klinikkategori'][$ii]['pd'] = $dateDiff->ago(strftime('%Y-%m-%d %H:%M:%S',strtotime($row->publishedDate)));
                    $a['klinikkategori'][$ii]['author'] = $vTitle->getCatalogTitle($row->kontributor,'fixedTitle');
                    $a['klinikkategori'][$ii]['sumber'] = $vTitle->getCatalogTitle($row->sumber,'fixedTitle');
                    
				    $arraypictureformat = array("jpg", "jpeg", "gif");
				    $txt_allowedformat = implode('; ', $arraypictureformat);
				    $registry = Zend_Registry::getInstance();
				    $config = $registry->get(Pandamp_Keys::REGISTRY_APP_OBJECT);
				    $cdn = $config->getOption('cdn');
				    
				    $sDir = $cdn['static']['dir']['photo'];
                	$sDir2 = $cdn['static']['url']['photo'].'/';
                	
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
				        $txt_existence = "<img src=\"".$cdn['static']['images']."/gravatar-140.png\" width=\"38\" height=\"38\" class=\"avatar\" border=\"0\" />";
				    }
                
				    $a['klinikkategori'][$ii]['img'] = $txt_existence;
				    $a['klinikkategori'][$ii]['kategoriklinik'] = $row->kategoriklinik;
				    $a['klinikkategori'][$ii]['kontributor'] = $row->kontributor;
					$a['klinikkategori'][$ii]['source'] = $row->sumber;				    	    
                }
            }
        }

        echo Zend_Json::encode($a);
	}
    function fetchClinicByCategoryAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $r = $this->getRequest();

        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $start = ($r->getParam('start'))? $r->getParam('start') : 0;
        $limit = ($r->getParam('limit'))? $r->getParam('limit'): 20;
        $orderBy = ($r->getParam('orderBy'))? $r->getParam('sortBy') : 'publishedDate';
        $sortOrder = ($r->getParam('sortOrder'))? $r->getParam('sortOrder') : ' DESC';

        $query = "profile:klinik status:99 kategoriklinik:$catalogGuid;publishedDate desc";

        $a = array();

        $a['query']	= $query;

        $vTitle = new Pandamp_Controller_Action_Helper_GetCatalogTitle();

        $indexingEngine = Pandamp_Search::manager();

        $hits = $indexingEngine->find($query, $start, $limit);

        $num = $hits->response->numFound;

        $solrNumFound = count($hits->response->docs);

        $ii=0;
        if($solrNumFound==0)
        {
            $a['klinikkategori'][0]['guid']= 'XXX';
            $a['klinikkategori'][0]['title']= 'Kategori klinik kosong';
            $a['klinikkategori'][0]['pertanyaan']= "";
            $a['klinikkategori'][0]['createdBy']= "";
            $a['klinikkategori'][0]['author']= '';
            $a['klinikkategori'][0]['sid']= '';
            $a['klinikkategori'][0]['source']= '';
            $a['klinikkategori'][0]['publishedDate']= '';
            $a['klinikkategori'][0]['existence']= '';
        }
        else
        {
            if($solrNumFound>$limit)
                $numRowset = $limit ;
            else
                $numRowset = $solrNumFound;

            for($ii=0;$ii<$numRowset;$ii++)
            {
                if(isset($hits->response->docs[$ii]))
                {
	                $row = $hits->response->docs[$ii];
	                
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
				    
                    $a['klinikkategori'][$ii]['guid'] = $row->id;
                    $a['klinikkategori'][$ii]['title'] = $row->title;
                    $a['klinikkategori'][$ii]['pertanyaan'] = $row->commentQuestion;
                    $a['klinikkategori'][$ii]['createdBy'] = $row->createdBy;
					$a['klinikkategori'][$ii]['author'] = $row->kontributor;
					$a['klinikkategori'][$ii]['sid'] = $row->sumber;
					$a['klinikkategori'][$ii]['source'] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row->kontributor,'fixedTitle');
                    $a['klinikkategori'][$ii]['publishedDate'] = date("d/m/Y",strtotime($row->publishedDate));
                    $a['klinikkategori'][$ii]['existence'] = '<div style="float:left;padding:2px;margin: 1px 10px 10px 0px;"><a href="">'.$txt_existence.'</a></div>';
                }
            }
        }

        echo Zend_Json::encode($a);
    }
    function saveAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $request = $this->getRequest();

        if ($request->getParam('fixedCommentTitle') == '') {
            $error[] = '- Masukkan judul pertanyaan';
        }
        if ($request->getParam('fixedCommentQuestion') == '') {
            $error[] = '- Masukkan pertanyaan anda';
        }
        
        if (isset($error)) {

            echo '<b>Error</b>: <br />'.implode('<br />', $error);

        } else {
        	
        	$aData = $request->getParams();

            $auth = Zend_Auth::getInstance();
            $username = $auth->getIdentity()->username;

            if (!$auth->hasIdentity()) {
                echo "You are not login or your session is expired. Please login.";
            }
            else
            {
                $aData['username'] = $username;
            }

            try {
                $hol = new Pandamp_Core_Hol_Catalog();
                $hol->save($aData);

                echo "Terima kasih atas minat anda terhadap klinik kami";
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }


        }

    }
}
