<?php

/**
 * Description of GalleryController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Talks_Widgets_GalleryController extends Zend_Controller_Action
{
    function fgalAction()
    {
        //$rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4d5ca623676b2',0,5);
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4d92a915a8760',0,5);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['guid'];

            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function holgalleryAction()
    {
        $this->_helper->layout->disableLayout();
        $r = $this->getRequest();
        
        $catalogGuid = ($r->getParam("id"))? $r->getParam("id") : '';
        $start = ($r->getParam("limit"))? $r->getParam("limit") : 24;
        $end = ($r->getParam("page"))? $r->getParam("page") : 1;

        $settings['allowed_extensions'] = array();
        $settings['allowed_extensions'][] = 'jpg';
        $settings['allowed_extensions'][] = 'jpeg';
        $settings['allowed_extensions'][] = 'png';
        $settings['allowed_extensions'][] = 'gif';

        $settings['hidden_files'] = array();
        $settings['hidden_files'][] = 'Thumbs.db';
        $settings['hidden_files'][] = '.DS_Store';

    	$registry = Zend_Registry::getInstance();
    	$config = $registry->get(Pandamp_Keys::REGISTRY_APP_OBJECT);
        $url = $config->getOption('f');

    	$url = $url['remote']['url'];
        $this->view->remoteUrl = $url;
        
        $remoteUrl = $url."/uploads/images/$catalogGuid";
        $html = file_get_contents($remoteUrl);
        preg_match_all('/<a href="([-\w\d.]+\.(jpg|jpeg|png|gif))"/', $html, $uu);
        $imgName = $uu[1];

        $cdn = $config->getOption('cdn');
//        $dir = ROOT_DIR."/uploads/images/$catalogGuid";
        $dir = $cdn['static']['url']['images']."/".$catalogGuid;
        if (is_dir($dir))
        {
            $sDir = $dir;
        }
        else
        {
            if (mkdir($dir))
            {
                $sDir = $dir;
            }
            else
            {
                //$sDir = ROOT_DIR."/uploads/images/";
            }
        }

        foreach ($imgName as $filename)
        {
            if (!file_exists($sDir . "/" .$filename)) {
            $data = file_get_contents($remoteUrl."/".$filename);
            $file = fopen($sDir . "/" .$filename, "w+");
            fputs($file, $data);
            fclose($file);
            }
//            $this->smartCopy($remoteUrl."/".$filename, $sDir);

//            $ext = explode(".",$filename);
//            $ext = strtolower(array_pop($ext));
//            if ((strpos($filename,"lt") !== 0)
//            && (!in_array($filename, $settings['hidden_files']))
//            && (in_array($ext,$settings['allowed_extensions']))
//            ) {
//                $all_thumbs[] = $filename;
//            }

        }

        if (is_dir($dir))
        {
            // open directory and parse file list
            if ($dh = opendir($dir)) {
                // iterate over file list & output all filenames
                while (($filename = readdir($dh)) !== false) {
                    $pinfo = pathinfo($filename);
                    if ((strpos($filename,"_") !== 0)
                    && (strpos($filename,".") !== 0)
                    && (strpos($filename,"lt") !== 0)
                    && (!in_array($filename, $settings['hidden_files']))
                    && (in_array(strToLower($pinfo["extension"]),$settings['allowed_extensions']))
                    ) {
                        $all_thumbs[] = $filename;
                    }
                }
                // close directory
                closedir($dh);
            }

            $configGallery = new Zend_Session_Namespace("cfg");
            $configGallery->allThumbs = $all_thumbs;
            $configGallery->perPage = $start;

            $this->view->allThumbs = $all_thumbs;
            $this->view->page = $end;
            $this->view->perPage = $start;

        }

        $this->view->catalogGuid = $catalogGuid;
    }
    function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755))
    {
        $result=false;

        if (is_file($source)) {
            if ($dest[strlen($dest)-1]=='/') {
                if (!file_exists($dest)) {
                    cmfcDirectory::makeAll($dest,$options['folderPermission'],true);
                }
                $__dest=$dest."/".basename($source);
            } else {
                $__dest=$dest;
            }
            $result=copy($source, $__dest);
            chmod($__dest,$options['filePermission']);

        } elseif(is_dir($source)) {
            if ($dest[strlen($dest)-1]=='/') {
                if ($source[strlen($source)-1]=='/') {
                    //Copy only contents
                } else {
                    //Change parent itself and its contents
                    $dest=$dest.basename($source);
                    @mkdir($dest);
                    chmod($dest,$options['filePermission']);
                }
            } else {
                if ($source[strlen($source)-1]=='/') {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest,$options['folderPermission']);
                    chmod($dest,$options['filePermission']);
                } else {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest,$options['folderPermission']);
                    chmod($dest,$options['filePermission']);
                }
            }

            $dirHandle=opendir($source);
            while($file=readdir($dirHandle))
            {
                if($file!="." && $file!="..")
                {
                     if(!is_dir($source."/".$file)) {
                        $__dest=$dest."/".$file;
                    } else {
                        $__dest=$dest."/".$file;
                    }
                    //echo "$source/$file ||| $__dest<br />";
                    $result=smartCopy($source."/".$file, $__dest, $options);
                }
            }
            closedir($dirHandle);

        } else {
            $result=false;
        }
        return $result;
    } 
}
