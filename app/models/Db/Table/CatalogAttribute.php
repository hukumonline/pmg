<?php

/**
 * Description of CatalogAttribute
 *
 * @author nihki <nihki@madaniyah.com>
 */
class App_Model_Db_Table_CatalogAttribute extends Zend_Db_Table_Abstract
{
    protected $_name = 'KutuCatalogAttribute';
    protected $_rowsetClass = 'App_Model_Db_Table_Rowset_CatalogAttribute';
    protected $_referenceMap    = array(
        'Catalog' => array(
            'columns'           => 'catalogGuid',
            'refTableClass'     => 'App_Model_Db_Table_Catalog',
            'refColumns'        => 'guid'
        ),
        'Attribute' => array(
            'columns'           => 'attributeGuid',
            'refTableClass'     => 'App_Model_Db_Table_Attribute',
            'refColumns'        => 'guid'
        )
    );
    public function insert (array $data)
    {
    	if(empty($data['guid']))
    	{
            $guidMan = new Pandamp_Core_Guid();
            $data['guid'] = $guidMan->generateGuid();
    	}

    	return parent::insert($data);
    }

    
}
?>
