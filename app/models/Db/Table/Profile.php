<?php

/**
 * Description of Profile
 *
 * @author nihki <nihki@madaniyah.com>
 */
class App_Model_Db_Table_Profile extends Zend_Db_Table_Abstract
{
    protected $_name = 'KutuProfile';
    protected $_dependentTables = array('App_Model_Db_Table_ProfileAttribute', 'App_Model_Db_Table_Catalog');
}
