<?php

/**
 * Description of AssetSetting
 *
 * @author nihki <nihki@madaniyah.com>
 */
class App_Model_Show_AssetSetting extends App_Model_Db_DefaultAdapter
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
    public function getAsset($cond, $limit)
    {
        $db = parent::_dbSelect();
        
        $statement = $db->from('KutuAssetSetting')
                ->where($cond)
                ->order("valueInt DESC")
                ->limit($limit);
        
        $row = parent::_getDefaultAdapter()->fetchAll($statement);

        return $row;
    }
    public function getAssetNumOfClick($catalogGuid)
    {
        $db = parent::_dbSelect();

        $row = parent::_getDefaultAdapter()->fetchRow($db->from('KutuAssetSetting')->where('guid=?',$catalogGuid));

        return $row;
    }
    public function addCounterAsset($catalogGuid, array $data)
    {
        if (!isset($catalogGuid)) {
                return 0;
        }

        $whiteList = array(
                'guid',
                'application',
                'part',
                'valueType',
                'valueInt',
                'valueText'
        );

        $addData = array();

        foreach ($data as $key => $value) {
            if (in_array($key, $whiteList)) {
                $addData[$key] = $value;
            }
        }

        if (empty($addData)) {
            return 0;
        }

        $db = parent::_dbSelect();
        $where = $db->from('KutuAssetSetting')->where('guid=?',$catalogGuid);
        $row = parent::_getDefaultAdapter()->fetchRow($where);
        if ($row) {
                // check ip
                /*
                if (($row->guid == $catalogGuid) && ($row->valueType == Pandamp_Lib_Formater::getRealIpAddr())) {
                        return 0;
                }
                */
                $row['valueInt'] = $row['valueInt'] += 1;
                $id = parent::_getDefaultAdapter()->update('KutuAssetSetting',array('valueInt'=>$row['valueInt']), "guid='".$catalogGuid."'");
        } else {
                $id = parent::_getDefaultAdapter()->insert('KutuAssetSetting',$addData);
        }

        if ((int)$id == 0) {
            return 0;
        }

        return $id;
    }
}
