<?php

/**
 * Description of IndexController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Hold_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->cache(array('index'), array('hold'));
    }
    function preDispatch()
    {
        $this->_helper->layout->setLayout('layout-pusatdata');
    }
    function indexAction()
    {
        $this->_forward('view','browser','hold');
    }

}
