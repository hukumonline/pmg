<?php
class Pandamp_Controller_Action_Helper_GetRemoteSearchUrl extends Zend_Controller_Action_Helper_Abstract
{
	public function getRemoteSearchUrl()
	{
		$registry = Zend_Registry::getInstance();
		$config = $registry->get(Pandamp_Keys::REGISTRY_APP_OBJECT);
		$remoteSearchIn = $config->getOption('search');
		return $remoteSearchIn['website'];
	}
}