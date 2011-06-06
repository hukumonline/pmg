<?php

/**
 * Description of Catalog
 *
 * @author nihki <nihki@madaniyah.com>
 */

class App_Model_Show_Catalog extends App_Model_Db_DefaultAdapter
{
    /**
     * class instance object
     */
    private static $_instance;

    /**
     * de-activate constructor
     */
    final private function  __construct() {}

     /**
      * de-activate object cloning
      */
    final private function  __clone() {}

    /**
     * @return obj
     */
    public function show()
    {
        if (!isset(self::$_instance)) {
                $show = __CLASS__;
                self::$_instance = new $show;
        }
        return self::$_instance;
    }

    /**
     * @return obj
     */
    public function fetchFromFolder($folderGuid, $start = 0 , $end = 0)
    {
        $now = date('Y-m-d H:i:s');
        $db = parent::_dbSelect();
        $statement = $db->from('KutuCatalog')
                        ->join('KutuCatalogFolder','KutuCatalog.guid=KutuCatalogFolder.catalogGuid',array())
                        ->where('KutuCatalog.status=?',99)
                        ->where('KutuCatalogFolder.folderGuid=?',$folderGuid)
                        ->where("KutuCatalog.publishedDate = '0000-00-00 00:00:00' OR KutuCatalog.publishedDate <= '$now'")
                        ->where("KutuCatalog.expiredDate = '0000-00-00 00:00:00' OR KutuCatalog.expiredDate >= '$now'")
                        ->order('KutuCatalog.publishedDate DESC')
                        ->limit($end,$start);

        $result = parent::_getDefaultAdapter()->fetchAll($statement);

        return $result;
    }
    public function getCatalogByGuid($guid)
    {
        $db = parent::_dbSelect();
        $statement = $db->from('KutuCatalog')->where('guid=?', $guid);
        $row = parent::_getDefaultAdapter()->fetchRow($statement);

        return $row;
    }
    function fetchFromFolderByDate($folderGuid, $gDate, $start = 0 ,$end = 0)
    {
        $db = parent::_dbSelect();
        $select = $db->from('KutuCatalog')
                ->join('KutuCatalogFolder','KutuCatalog.guid=KutuCatalogFolder.catalogGuid',array())
                ->where('KutuCatalog.status=?',99)
                ->where("DATE_FORMAT(KutuCatalog.createdDate,'%Y-%m-%d') = '$gDate'")
                ->where('KutuCatalogFolder.folderGuid=?',"$folderGuid")
                ->order('KutuCatalog.createdDate DESC')
                ->limit($end, $start);

        $rows = parent::_getDefaultAdapter()->fetchAll($select);

    	return $rows;
    }
    public function getWartaCount($folderGuid)
    {
        $db = parent::_dbSelect();
    	$now = date('Y-m-d H:i:s');
        $select = $db->from('KutuCatalog', array(
                    'COUNT(*) as count'
                  ))
                  ->join('KutuCatalogFolder','KutuCatalog.guid=KutuCatalogFolder.catalogGuid',array())
                  ->where('KutuCatalog.status=?',99)
                  ->where('KutuCatalogFolder.folderGuid=?',"$folderGuid")
	    		  ->where("KutuCatalog.publishedDate = '0000-00-00 00:00:00' OR KutuCatalog.publishedDate <= '$now'")
	    		  ->where("KutuCatalog.expiredDate = '0000-00-00 00:00:00' OR KutuCatalog.expiredDate >= '$now'");

        $row = parent::_getDefaultAdapter()->fetchRow($select);

        return ($row !== null) ? $row['count'] : 0;
    }
    public function getCatalogByProfile($profileGuid)
    {
        $db = parent::_dbSelect();
        $row = parent::_getDefaultAdapter()->fetchAll($db->from('KutuCatalog')->where('profileGuid=?', $profileGuid)->order('createdDate ASC'));

        return $row;
    }
    
}
?>
