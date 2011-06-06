<?php

/**
 * Description of Form
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Pandamp_Lib_Form
{
    function selectDate (
                $sel_d = 0			// selected day
              , $sel_m = 0       	// selected month
              , $sel_y = 0       	// selected year
              , $var_d = 'd'     	// name for day variable
              , $var_m = 'm'    // name for month variable
              , $var_y = 'y'     // name for year variable
              , $min_y = 2000       	// minimum year
              , $max_y = 0       	// maximum year
              , $enabled = true  	// enable drop-downs?
            ) {

        // Default day is today
        if ($sel_d == 0)
        $sel_d = date('j');
        // Default month is this month
        if ($sel_m == 0)
        $sel_m = date('n');
        // Default year is this year
        if ($sel_y == 0)
        $sel_y = date('Y');
        // Default minimum year is this year
        if ($min_y == 0)
        $min_y = date('Y');
        // Default maximum year is two years ahead
        if ($max_y == 0)
                $max_y = ($min_y + 2);

        // --------------------------------------------------------------------------
        // Start off with the drop-down for Days
        // Start opening the select element
        $dateout = '<select name="'. $var_d. '"';
        // Add disabled attribute if necessary
        if (!$enabled)
        $dateout .= ' disabled="disabled"';
        // Finish opening the select element
        $dateout .= '>\n';
        // Loop round and create an option element for each day (1 - 31)
        for ($i = 1; $i <= 31; $i++) {
        // Start the option element
        $dateout .= '\t<option value="'. $i. '"';
        // If this is the selected day, add the selected attribute
        if ($i == $sel_d)
                $dateout .= ' selected="selected"';
        // Display the value and close the option element
        $dateout .= '>'. $i. '</option>\n';
        }
        // Close the select element
        $dateout .= '</select>&nbsp;';

        // --------------------------------------------------------------------------
        // Now do the drop-down for Months
        // Start opening the select element
        $dateout .= '<select name="'. $var_m. '"';

        // Add disabled attribute if necessary
        if (!$enabled)
        $dateout .= ' disabled="disabled"';

        // Finish opening the select element
        $dateout .= '>\n';

        // Loop round and create an option element for each month (Jan - Dec)
        for ($i = 1; $i <= 12; $i++) {
        // Start the option element
        $dateout .= '\t<option value="'. $i. '"';
        // If this is the selected month, add the selected attribute
        if ($i == $sel_m)
                $dateout .= ' selected="selected"';
        // Display the value and close the option element
        $dateout .= '>'. date('F', mktime(3, 0, 0, $i)). '</option>\n';
        }

        // Close the select element
        $dateout .= '</select>&nbsp;';

        $max_y = date("Y");
        // --------------------------------------------------------------------------
        // Finally, the drop-down for Years
        // Start opening the select element
        $dateout .= '<select name="'. $var_y. '"';

        // Add disabled attribute if necessary
        if (!$enabled)
        $dateout .= ' disabled="disabled"';

        // Finish opening the select element
        $dateout .= '>\n';

        // Loop round and create an option element for each year ($min_y - $max_y)
        for ($i = $min_y; $i <= $max_y; $i++) {
        // Start the option element
        $dateout .= '\t<option value="'. $i. '"';
        // If this is the selected year, add the selected attribute
        if ($i == $sel_y)
                $dateout .= ' selected="selected"';
        // Display the value and close the option element
        $dateout .= '>'. $i. '</option>\n';
        }
        // Close the select element
        $dateout .= '</select>';
        return $dateout;
    }

    /**
     * categoryClinicPullDown
     * @return category clinic
     */

    static function categoryClinicPullDown($cat='')
    {
        $tblCatalog = new App_Model_Db_Table_Catalog();
        $rowset = $tblCatalog->fetchAll("profileGuid='kategoriklinik'",'createdDate DESC');
        $category = "<select name=\"category\">\n";
        if ($cat) {
            $rowCategory = $tblCatalog->find($cat)->current();
            $title = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($cat,'fixedTitle');
            $category .= "<option value='$rowCategory->guid' selected>$title</option>";
            $category .= "<option value=''>---Semua Kategori---</option>";
        }
        else
        {
            $category .= "<option value='' selected>---Semua Kategori---</option>";
        }
        foreach ($rowset as $row)
        {
            $attributeTitle = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row->guid,'fixedTitle');
            if (($cat) && ($row->guid == $rowCategory->guid))
                continue;
            else
                $category .= "<option value='$row->guid'>$attributeTitle</option>";
        }

        $category .= "</select>\n\n";
        return $category;
    }

    /**
     * province
     */
    function chooseProvince($province=null)
    {
        $tblProvince = new App_Model_Db_Table_Province();
        $row = $tblProvince->fetchAll();

        $select_province = "<select name=\"province\" id=\"province\">\n";
        if ($province) {
            $rowProvince = $tblProvince->find($province)->current();
            $select_province .= "<option value='$rowProvince->pid' selected>$rowProvince->pname</option>";
            $select_province .= "<option value =''>----- Pilih -----</option>";
        } else {
            $select_province .= "<option value ='' selected>----- Pilih -----</option>";
        }
        
        foreach ($row as $rowset) {
            if (($province) and ($rowset->pid == $rowProvince->pid)) {
                continue;
            } else {
                $select_province .= "<option value='$rowset->pid'>$rowset->pname</option>";
            }
        }

        $select_province .= "</select>\n\n";
        return $select_province;
    }
}
