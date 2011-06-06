<?php

/**
 * Description of Comment
 *
 * @author nihki <nihki@madaniyah.com>
 */
class App_Model_Db_Table_Comment extends Zend_Db_Table_Abstract
{
    protected $_name = 'comments';

    public function addComment($data)
    {
    	if (!isset($data['object_id'])) {
    		return 0;
    	}
    	if (!isset($data['name'])) {
    		return 0;
    	}
    	if (!isset($data['email'])) {
    		return 0;
    	}
    	if (!isset($data['title'])) {
    		return 0;
    	}
    	if (!isset($data['comment'])) {
    		return 0;
    	}

    	$whiteList = array(
    		'parent',
    		'object_id',
    		'userid',
    		'name',
    		'email',
    		'title',
    		'comment',
    		'ip',
    		'date'
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

        $id = $this->insert($addData);

        if ((int)$id == 0) {
            return 0;
        }

        return $id;
    }
}
