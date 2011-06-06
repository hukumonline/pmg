<?php

/**
 * Description of GetNumOfComment
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Pandamp_Controller_Action_Helper_GetNumOfComment extends Zend_Controller_Action_Helper_Abstract
{
    public function getNumOfComment($parent)
    {
        $count = App_Model_Show_Comment::show()->getCommentParentCount($parent);

        return ($count != 0)? $count.' Tanggapan' : '';
    }
}
