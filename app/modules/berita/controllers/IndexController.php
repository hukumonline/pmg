<?php

/**
 * Description of IndexController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Berita_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->cache(array('index'), array('warta'));
    }
    function preDispatch()
    {
        $this->_helper->layout->setLayout('layout-berita');
    }
    function indexAction()
    {
        $year 	= $this->_getParam('year');
        $month 	= $this->_getParam('month');
        $date 	= $this->_getParam('date');

        $this->view->year = $year;
        $this->view->month = $month;
        $this->view->date = $date;
    }
    function utamaAction() 	{}
    function terbaruAction() 	{}
    function fokusAction() 	{}
    function isuhangatAction()	{}
    function tajukAction()	{}
    function kolomAction()	{}
    function jedaAction()	{}
    function resensiAction()	{}
    function tokohAction()	{}
    function infoAction()	{}
    function aktualAction()	{}
}
