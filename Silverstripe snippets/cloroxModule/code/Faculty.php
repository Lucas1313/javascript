<?php
/*
 * Faculty
 *
 * Describes the Model for a Faculty
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: Faculty.php 22794 2013-06-05 20:23:55Z lmartin $
 *
 * Relationships:
 *
 * hasOne = Image, FacultyPage, SingleFacultyPage, FacultyCategoryPage, AlsoLikeItem
 * many-one = FacultySubCategories
 * many-many = FAQs, FacultyBenefits, TagFeatures, TagNeed, TagType
 *
 */
class Faculty extends DataObject {
    public function init() {
     //   $this -> updateTagFields();
    }

    static $db = array(
        'Title' => 'Text',
        'Display_Name' => 'HtmlText', //The name cleaned up includes special characters
        'Code_Name' => 'Text', //The Code_Name, from Clorox API naming convention
        'ActiveStatus' => 'Boolean', // Is this Profile to be shown or not true / false
        'Description' => 'HTMLText', //A more detailled description
        'Sort_Order_Faculty' => 'Int'
    );

    static $has_one = array(
        'Image' => 'Image', //Faculty Image
        'Faculty_Page' => 'ClassroomsFacultyPage', // Page where all the Classroom Faculty should be displayed
    );

    static $many_many = array(
        'FacultyTips' => 'FacultyTips' // Tips that each faculty member generates
    );

    // Fields used for the display of info in the CSV and The grids
    static $summary_fields = array(
        'ID',
        'Title',
        'Code_Name',
        'Display_Name',
		'ActiveStatus',
    );

    // Searchable fields
    static $searchable_fields = array(
        'Display_Name',
        'Code_Name',
        'Title',
        'Description',
       // 'FacultyTips'
    );

    // Drag and Drop ordering
    public static $default_sort = 'Sort_Order_Faculty';

   


    //the cms fields
    public function getCMSFields() {

        $fields = parent::getCMSFields();
        $this -> init();

        // Remove un-necessary fields
        // these fields are automatically set and don't need to be edited by hand
        $fields -> removeFieldFromTab('Root.Main', 'Content');
        $fields -> removeFieldFromTab('Root.Main', 'From_Api');
        $fields -> removeFieldFromTab('Root.Main', 'All_Faculty_Sub_Category');
        $fields -> removeFieldFromTab('Root.Main', 'Faculty_Page');
        $fields -> removeFieldFromTab('Root.Main', 'Parent_Page');
        $fields -> removeFieldFromTab('Root.Main', 'Faculty_Category_PageID');
		$fields -> removeFieldFromTab('Root.Main', 'Code_Name');
        $fields -> removeFieldFromTab('Root.Main', 'Sort_Order_Faculty');
        $fields -> removeFieldFromTab('Root.Main', 'Parent_PageID');
        $fields -> removeFieldFromTab('Root.Main', 'Faculty_PageID');
		$fields -> removeFieldFromTab('Root.Main', 'Description');
       
        $fields -> addFieldToTab('Root.Main', new checkboxField('ActiveStatus', 'Enable or Disable Faculty Member'));
        $fields -> addFieldToTab('Root.Main', new checkboxField('Add_To_New_Faculty_Page', 'Add_To_New_Faculty_Page'));


        //***************** DISPLAY NAME
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));
		       
        //***************** DISPLAY TITLE
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        //***************** Faculty BIO Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));
	   
	   
        
        return $fields;
    }

    /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the Faculty with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {

        // Manipulate data before editing
        //String cleanup and manipulation
        $strManipulator = new StringManipulator_Controller();

        // Clean up special characters
        $this -> Display_Name = $strManipulator -> cleanupSpecialChar($this -> Display_Name);
        $this -> Display_Name = htmlentities($this -> Display_Name);
        $this -> Display_Name = html_entity_decode($this -> Display_Name);

        // generate the Code_Name from the FacultyName
        $this -> Code_Name = $strManipulator -> generatecodeName($this -> Name);

        // Make sure that the Name field is filled up (necessary for searches)
        if (empty($this -> name)) {
            $this -> name = $this -> Display_Name;
        }
        
/*
        // If yes add a alsoLike
        if (!$alsoLikeItem) {
            // generate the also like item
            $this -> generateAlsoLike();
        }
*/
        parent::onBeforeWrite();
    }

 
    
    public function pageUrl() {
        if (!empty($this -> Faculty_Page_Link)) {
            return $this -> Faculty_Page_Link;
        }
        $ret = '';
       /* $singleFacultyPage = SingleFacultyPage::get();

        foreach ($singleFacultyPage as $key => $FacultyPage) {

            $Facultys = $FacultyPage -> Faculty() -> filter(array('ID' => $this -> ID));

            foreach ($Facultys as $key => $Faculty) {
                $this -> Faculty_Page_Link = $FacultyPage -> Link();
                $this -> write();
                return $FacultyPage -> Link();
            }

        }*/

    }

    public function Display_Name() {
        return str_replace('1', '<small>1</small>', $this -> Display_Name);
    }

    /**
     * starRatingImage function
     * generate a star rating URL based on the current
     * Faculty's BV id. main functionality found in bvModule
     *
     * @return string $bvStarRatingURL or void
     */
    public function starRatingImage() {
        $bvFacultyId = $this -> BV_Id;
        // only do this if valid so as not to
        // return an error if BV data is out of sync
        if (!empty($bvFacultyId)) {
            $bvStarRatingURL = bvModule_Controller_HelperMethods::starRatingImage($bvFacultyId);
            return $bvStarRatingURL;
        }
        //else ss needs return of some sort
        return false;
    }

    /**
     * bvStats function
     * get an object populated with statistics
     * to be used on the template from the method
     * by the same name in bvModule_Controller_HelperMethods
     *
     * @return ArrayList $bvStats or void
     */
    public function bvStats() {
        $bvFacultyId = $this -> BV_Id;
        // only do this if valid so as not to
        // return an error if BV data is out of sync
        if (!empty($bvFacultyId)) {
            $bvStats = bvModule_Controller_HelperMethods::bvStats($bvFacultyId);
            if (!empty($bvStats)) {
                $bvStatDataSet = new ArrayList();
                $bvStatDataSet -> push($bvStats);
                return $bvStatDataSet;
            }
        }
        //else ss needs return of some sort
        return false;
    }

    public function Code_Name() {
        $strManipulator = new StringManipulator_Controller();
        $Code_Name = $strManipulator -> generateCodeName($this -> Title);
        if ($this -> Code_Name != $Code_Name) {
            $this -> Code_Name = $Code_Name;
            $this -> write();
        }
        return $this -> Code_Name;
    }

}
