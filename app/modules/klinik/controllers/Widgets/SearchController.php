<?php

/**
 * Description of SearchController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Klinik_Widgets_SearchController extends Zend_Controller_Action
{
    function viewResultAction()
    {
        $time_start = microtime(true);

        $query 		= ($this->_getParam('query'))? $this->_getParam('query') : '';
        $category 	= ($this->_getParam('category'))? $this->_getParam('category') : '';

        $a = array();

        if ($category)
        {
            $querynum = $query.' profile:klinik status:99 kategoriklinik:'.$category;
        }
        else
        {
            $querynum = $query.' profile:klinik status:99';
        }

        $a['query']	= $query;

        $indexingEngine = Pandamp_Search::manager();
        $hits = $indexingEngine->find($query,0,1);

        $num = Pandamp_Lib_Formater::findCatalog($querynum);
        $limit = 20;

        $a['totalCount'] = $num;
        $a['limit'] = $limit;

        $ii=0;

        if($a['totalCount']==0)
        {
            $a['catalogs'][0]['title']= "No Data";
            $a['catalogs'][0]['guid']= 'XXX';
            $a['catalogs'][0]['question']= "";
            $a['catalogs'][0]['kategoriklinik']= "";
            $a['catalogs'][0]['kategori']= "";
            $a['catalogs'][0]['createdBy']= '';
            $a['catalogs'][0]['author']= '';
            $a['catalogs'][0]['sid']= '';
            $a['catalogs'][0]['source']= '';
            $a['catalogs'][0]['publishedDate']= '';
            $a['catalogs'][0]['existence']= '';
        }


        $this->view->aData = $a;
        $this->view->query = $query;
        $this->view->ct = $category;
        $this->view->hits = $hits;

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $this->view->time = round($time,2);
    }
}
