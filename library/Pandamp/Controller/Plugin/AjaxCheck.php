<?php
class Pandamp_Controller_Plugin_AjaxCheck
	extends Zend_Controller_Plugin_Abstract
{
	public function preDispatch($request)
	{
		//If the request is not an XHR, do nothing.
		if(!$request->isXmlHttpRequest())
			return;
		
		
		$oldViewHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		
		$viewHelper = new Zend_Controller_Action_Helper_ViewRenderer($oldViewHelper->view);
		
		Zend_Controller_Action_HelperBroker::addHelper($viewHelper);
	}
		
}