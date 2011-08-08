<?php

/**
 * Description of GetNumOfComment
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Pandamp_Controller_Action_Helper_GetParentComment extends Zend_Controller_Action_Helper_Abstract
{
    public function getParentComment($catalogGuid)
    {
        $count = App_Model_Show_Comment::show()->getParentCommentCount($catalogGuid);

        return $count;
    }
}
