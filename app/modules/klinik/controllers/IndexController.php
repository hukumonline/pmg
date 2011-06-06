<?php

/**
 * Description of IndexController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Klinik_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->cache(array('index'), array('clinic'));
    }
    function preDispatch()
    {
        $this->_helper->layout->setLayout('layout-klinik');
    }
    function indexAction(){}
    function rubrikAction() {}
    function browseAction() {}
    function disclaimerAction(){}
}
