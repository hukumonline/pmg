<?php

/**
 * Description of CatalogController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class HolSite_Widgets_CatalogController extends Zend_Controller_Action
{
    function detailAction()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        
        // get current ip address
        $ip = Pandamp_Lib_Formater::getRealIpAddr();

        $rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($catalogGuid);

        if (isset($rowset))

        $rowAsset = App_Model_Show_AssetSetting::show()->getAssetNumOfClick($catalogGuid);

        $data = array(
                'guid'		=> $catalogGuid,
                'application'   => 'ASSET',
                'part'		=> 'MOST_READABLE_TICKER',
                'valueType'	=> $ip,
                'valueInt'	=> 1,
                'valueText'	=> 'TICKER'
        );
        
        $asset = App_Model_Show_AssetSetting::show()->addCounterAsset($rowset['guid'],$data);

        $title = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedTitle');
        $subtitle = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedSubTitle');
        $content = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedContent');
        $description = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedDescription');
        $author = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedAuthor');

        $array_hari = array(1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
        $hari = $array_hari[date("N",strtotime($rowset['createdDate']))];

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

        $this->view->title = $title;
        $this->view->subtitle = $subtitle;
        $this->view->content = $content;
        $this->view->description = $description;
        $this->view->author = $author;
        $this->view->date = $hari . ', '. date("d F Y",strtotime($rowset['publishedDate']));
        $this->view->numOfClick	= (isset($rowAsset))? $rowAsset['valueInt'] : 0;

        $this->view->drawrating = $drawrating;

        $this->view->catalogGuid = $catalogGuid;

    }
    function shareAction()
    {
        $this->_helper->layout->disableLayout();
        
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $this->view->catalogGuid = $catalogGuid;

        $rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($catalogGuid);

        $title = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedTitle');

        $this->view->title = $title;

                /*Wawan :: short url :: begin */
        //echo $title;

        //$temp = $rowset->toArray();
        $temp = $rowset;
        $sub = $temp["shortTitle"];

        $sub="";
        if($temp["shortTitle"] != null ) { $sub = '/'.$temp["shortTitle"]; }


        $http = new Zend_Http_Client();
        $longUrl=ROOT_URL."/berita/baca/".$catalogGuid.$sub;

        //echo $longUrl;
         $http->setUri('http://api.bit.ly/shorten?version=2.0.1&longUrl='.$longUrl.'&login=hukumonline&apiKey=R_77ba1fce98783c1734e24bc28dfdb8c7');
        $shortUrl="";

          $response = $http->request();
          if ($response->isSuccessful())
         {
              $result = Zend_Json::decode($response->getBody());
              if (isset($result["results"][$longUrl]["shortUrl"])) {
                  $shortUrl =  $result["results"][$longUrl]["shortUrl"];
              }

			  //print_r($result["results"]);
			  //echo "<pre>";print_r( $result);echo "</pre>";
			  //echo $shortUrl;

          }

          $statsShortUrl="";
         $http->setUri('http://api.bit.ly/stats?version=2.0.1&shortUrl='.$shortUrl.'&login=hukumonline&apiKey=R_77ba1fce98783c1734e24bc28dfdb8c7');
         $response = $http->request();

          if ($response->isSuccessful())
         {
              $result = Zend_Json::decode($response->getBody());
              $statsShortUrl =  $result["results"]["clicks"];

              //print_r($result["results"]);
            //  echo "<pre>";print_r( $result);echo "</pre>";
              //echo $statsShortUrl;

          }

          $this->view->shortUrl = $shortUrl;
          $this->view->statsShortUrl = $statsShortUrl;
        /*Wawan :: short url :: end */
    }
    function detailIssueAction()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($catalogGuid);

        if ($rowset)
        {
            $title = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedTitle');
            $subtitle = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedSubTitle');
            $description = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedDescription');

            $this->view->title = $title;
            $this->view->subtitle = $subtitle;
            $this->view->description = $description;

            $array_hari = array(1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
            $hari = $array_hari[date("N",strtotime($rowset['createdDate']))];

            $this->view->date = $hari . ', '. date("d F Y",strtotime($rowset['createdDate']));
            $this->view->catalogGuid = $catalogGuid;
        }
    }
	function detailfotoAction()
	{
		$catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
		
		$rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($catalogGuid);
		
        $array_hari = array(1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
        $hari = $array_hari[date("N",strtotime($rowset['publishedDate']))];

        $this->view->date = $hari . ', '. date("d/m/y H:i",strtotime($rowset['publishedDate']));
        
        $title = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedTitle');
        $this->view->title = $title;
        $subtitle = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedSubTitle');
        $this->view->subtitle = $subtitle;
        $author = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedAuthor');
        $this->view->author = $author;
	}
}
