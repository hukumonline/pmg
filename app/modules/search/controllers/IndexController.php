<?php

/**
 * Description of IndexController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Search_IndexController extends Zend_Controller_Action
{
    function  preDispatch()
    {
        $this->_helper->layout->setLayout("layout-search");
    }
    function indexAction()
    {
        $time_start = microtime(true);

        $r = $this->getRequest();

        $query = ($r->getParam('keyword'))? $r->getParam('keyword') : '';
        $category = ($r->getParam('category'))? $r->getParam('category') : '';

        $indexingEngine = Pandamp_Search::manager();
        if(empty($query))
        {
            $hits = $indexingEngine->find("fjkslfjdkfjls",0, 1);
        } else {

            if ($category)
            {
                $querySolr = $query.' -profile:kutu_doc status:99 profile:'.$category.';publishedDate desc';
            }
            else
            {
                $querySolr = $query.' -profile:kutu_doc status:99;publishedDate desc';
            }

            $hits = $indexingEngine->find($querySolr,0, 1);
        }

        $solrNumFound = $hits->response->numFound;

        $a = array();

        $a['query'] = $query;

        $a['totalCount'] = $solrNumFound;
        $a['limit'] = 20;

        $ii=0;

        if($a['totalCount']==0)
        {
            $a['catalogs'][0]['guid']= 'XXX';
            $a['catalogs'][0]['title']= "No Data";
            $a['catalogs'][0]['subTitle']= "";
            $a['catalogs'][0]['createdDate']= '';
            $a['catalogs'][0]['modifiedDate']= '';
        }


        $this->view->aData = $a;
        $this->view->hits = $hits;

        $this->_helper->layout()->searchQuery = $query;

        switch ($category)
        {
            case "kutu_peraturan":
            case "kutu_putusan":
            case "kutu_rancangan_peraturan":
            case "kutu_peraturan_kolonial":
                $ct = "(kutu_peraturan OR kutu_peraturan_kolonial OR kutu_rancangan_peraturan OR kutu_putusan)";
                break;
            case "isuhangat":
                $ct = "article";
                break;
            default :
                $ct = $category;
                break;
        }

        $this->_helper->layout()->categorySearchQuery = $ct;
        $this->view->query = $query;
        $this->view->ct = $category;

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $this->view->time = round($time,2);
    }
}
