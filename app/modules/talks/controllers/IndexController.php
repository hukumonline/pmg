<?php

/**
 * Description of IndexController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Talks_IndexController extends Zend_Controller_Action
{
    function init()
    {
        $this->_helper->cache(array('index'), array('talks'));

        Zend_Session::start();
    }
    function  preDispatch()
    {
        $this->_helper->layout->setLayout('layout-talks');
    }
    function headerAction(){}
    function viewcartAction() {}
    function viewAction() {}
    function galleryAction()
    {
        $r = $this->getRequest();
        $catalogGuid = ($r->getParam("id"))? $r->getParam("id") : '';

        $this->view->id = $catalogGuid;
    }
    function indexAction()
    {
        
    }
}
