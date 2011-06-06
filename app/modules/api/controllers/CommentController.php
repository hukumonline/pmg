<?php

/**
 * Description of CommentController
 *
 * @author nihki <nihki@madaniyah.com>
 */

require_once('recaptchalib.php');
class Api_CommentController extends Zend_Controller_Action
{
    function saveAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $request = $this->getRequest();

        $validator = new Zend_Validate_EmailAddress();

        if ($request->getParam('name') == '') {
                $error[] = '- Nama harus diisi';
        }
        if ($request->getParam('email') == '') {
                $error[] = '- Email harus diisi';
        }
        if (!$validator->isValid($request->getParam('email'))) {
                $error[] = '- Penulisan email salah!';
        }
        if ($request->getParam('title') == '') {
                $error[] = '- Judul harus diisi';
        }
        if ($request->getParam('comment') == '') {
                $error[] = '- Komentar harus diisi';
        }

        $privatekey = "6LcKob8SAAAAAN4TTHJMVFG88p2hkeTIztBEs4kT"; // http://hukumonline.n2
        //$privatekey = "6LcL47wSAAAAAATTV9Xufi-GCHj1GvL9TxyuKm-E"; // http://hukumonline.pl
        //$privatekey = "6Lem4rwSAAAAAFeSUqpBonLBhixm-GLeap1eTWNK"; // http://www.hukumonline.com

        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if (!$resp->is_valid) {
            // What happens when the CAPTCHA was entered incorrectly
            $error[] = "- The reCAPTCHA wasn't entered correctly. Go back and try it again." .
            "(reCAPTCHA said: " . $resp->error . ")";
        }

        if (isset($error)) {

            echo '<b>Error</b>: <br />'.implode('<br />', $error);

        } else {

            $aResult = array();

            $aData = $request->getParams();

            try {
                    $com = new Pandamp_Core_Hol_Comment();
                    $com->save($aData);

                    echo "Terima kasih atas tanggapan anda";
            }
            catch (Exception $e)
            {
                    echo $e->getMessage();
            }

        }
    }

}
