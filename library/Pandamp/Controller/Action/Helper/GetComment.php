<?php

/**
 * Description of GetComment
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Pandamp_Controller_Action_Helper_GetComment extends Zend_Controller_Action_Helper_Abstract
{
    public function getComment($parent)
    {
        $rows = App_Model_Show_Comment::show()->getParentComment($parent);
        return $rows;
    }
}
