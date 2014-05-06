<?php
/*
 * Class BLMManagementPage
 * Describes the Model for a BLMLandingPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class BLMManagementPage extends BLMMasterPage {

    public $pagesQty;
    public $actualPage;
    public $totalCount;

    static $db = array(
        'QuantityOfMomentsToManage' => 'int',
        'ShowStartingDate' => 'SS_Datetime',
        'ShowEndingDate' => 'SS_Datetime',
        'ShowOnlyPending' => 'Boolean',
        'PublicationDate' => 'Date',
        'Description' => 'HtmlText',
        'SharingTitle' => 'Varchar(75)',
        'SharingPromoText' => 'Varchar(275)',
        'HelpChooseWinnerTitle' => 'Varchar(75)',
        'HelpChooseWinnerContent' => 'HTMLText',
        'ChooseWinnerCTATitle1' => 'Text',
        'ChooseWinnerCTALink1' => 'Text',
        'ChooseWinnerCTATitle2' => 'Text',
        'ChooseWinnerCTALink2' => 'Text',
    );

    public static $has_one = array();

    public static $has_many = array('BLMoments' => 'BLMoment');

    public static $many_many = array();

    public static $belongs_many_many = array();

    public function getCMSFields() {
        $this -> BLMomentsGet();
        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        $fields -> removeFieldFromTab('Root.Main', 'Content');

        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description', 'Description'));

        //***************** Sharing
        $fields -> addFieldToTab('Root.Main', new HeaderField('SharingHeader', 'Sharing'));
        $fields -> addFieldToTab('Root.Main', new TextField('SharingTitle'));
        $fields -> addFieldToTab('Root.Main', new TextareaField('SharingPromoText', 'SharingPromoText'));

        //***************** Choose winners
        $fields -> addFieldToTab('Root.Main', new HeaderField('ChoosingWinnersHeader', 'Help Choose Your winners section'));
        $fields -> addFieldToTab('Root.Main', new TextField('HelpChooseWinnerTitle', 'Title'));
        $fields -> addFieldToTab('Root.Main', new TextareaField('HelpChooseWinnerContent', 'Choose your winners promo text'));
        $fields -> addFieldToTab('Root.Main', new TextField('ChooseWinnerCTATitle1', 'Button "Week Vote" title'));
        $fields -> addFieldToTab('Root.Main', new TextField('ChooseWinnerCTALink1', 'Button "Week Vote" Link'));
        $fields -> addFieldToTab('Root.Main', new TextField('ChooseWinnerCTATitle2', 'Button "Vote for Moment in the gallery" title'));
        $fields -> addFieldToTab('Root.Main', new TextField('ChooseWinnerCTALink2', 'Button "Vote for Moment in the gallery" link'));

        $fields -> addFieldToTab('Root.ManageMoments', new TextField('QuantityOfMomentsToManage', 'How many moments would you like to manage? (Saving required) Max 200'));

        $fields -> addFieldToTab('Root.ManageMoments', new CheckboxField('ShowOnlyPending', 'Show Only Pending?'));

        $dateField = new DateField('ShowStartingDate');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.ManageMoments', $dateField);

        $dateField = new DateField('ShowEndingDate');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.ManageMoments', $dateField);
        //***************** Feature Panels
        $BLMomentField = new GridField('BLMoment', 'BLMoment', $this -> BLMomentsGet(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation'), new GridFieldExportButton()));
        $fields -> addFieldToTab('Root.ManageMoments', $BLMomentField);

        //**
        $fields -> addFieldToTab('Root.ManageMoments', new NestedDataObjectField('BLMoments', 'This BLMoments:', array(
            'parent' => $this,
            'parentClass' => 'BLMLandingPage',
            'object' => 'BLMomentsGet',
            'objectClass' => 'BLMoment',
            'titleFields' => array(
                'Name',
                'created_timestamp'
            ),
            'showClassInTitle' => false,
            'fields' => array(
                'Name' => 'TextField',
                'submission' => 'TextAreaField',
                'approval' => 'OptionsetField',
                'solved' => 'TextAreaField'
            ),
            'optionsSetSources' => array('approval' => array(
                    'APPROVED' => 'APPROVED',
                    'REJECTED' => 'REJECTED',
                    'PENDING' => 'PENDING'
                )),
            'parentId' => $this -> ID,
            'addImageInfo' => null,
            'recursions' => array()
        )));

        return $fields;

    }

    /**
     * BLMomentsGet function
     * Purpose: This is the main function for administrate and generate moments
     * Can be accessed from Ajax using GET or POST parameters
     * Also from the Admin interface od silverstripe.
     *
     * @param  $QuantityOfMomentsToManage Integer
     * @param $_REQUEST['qty'] Get or Post Integer
     * @param $_REQUEST['filter'] String The filtering 3 options ''APPROVED, REJECTED, PENDING''
     * @param $_REQUEST['startDate'] Limit the dates for showing Moments
     *
     * @author Luc Martin at Clorox
     * @version $ID
     */
    function BLMomentsGet($QuantityOfMomentsToManage = 0) {

        // Cleanup the request
        foreach ($_REQUEST as $key => $value) {

            $_REQUEST[$key] = Convert::raw2sql($value);

        }
        $Group = DataObject::get_one('Group', "Code = 'Marketing'");

        //if(Member::currentUser()->inGroup($Group->ID)){

		try{
        //	error_log('Checking permissions on Marketing '.Member::currentUser()->inGroup($Group->ID));
		}catch(Exception $e){

		}
        // Admin only
        if (Permission::check('ADMIN') !== true && Member::currentUser()->inGroup($Group->ID) !== true) {

            return null;

        }

        $moments = BLMoment::get() -> where($this -> generateFilter(false)) -> sort(array('ID' => 'DESC')) -> limit($_SESSION['quantityOfMomentsToManage'],$this -> calculateLimit());

        return $moments;

    }

    /**
     * Method called before an Object is saved

     * @param none
     * @return void
     */
    public function onBeforeWrite() {
        parent::onBeforeWrite();
        // Update all changes in the Nested Objects
        NestedDataObjectField::updateNestedDataObjects($this);
    }

    /**
     * Method called After an Object is saved

     * @param none
     * @return void
     * @author Luc Martin at Clorox
     * @version $ID
     */

    function onAfterWrite() {
        parent::onAfterWrite();
        // Saves New NestedDataObjects
        NestedDataObjectField::generateNewNestedDataObjectItem($this);

    }

    public  function generateFilter($addWhere = true) {

        //error_log('#3 BLM generateFilter is running');
        $filter = '';

        // Init the filter
        if ($addWhere == true) {
            $ret = ' WHERE 1 ';
        }
        else {
            $ret = ' 1 ';
        }

        // acceptable values for the filter are 'APPROVED', 'REJECTED', 'PENDING'
        $acceptableFilterValues = array(
            'APPROVED',
            'REJECTED',
            'PENDING',
            'Selected10',
            'Finalists',
            'Showdown'
        );

        // test if there is a filter and if the filter is within the acceptable values
        if (!empty($_REQUEST['filter']) && in_array($_REQUEST['filter'], $acceptableFilterValues)) {

            $filter .= $_REQUEST['filter'];

        }

        // empty string for the filter query if the request is set to "ALL"
        if (!empty($filter) && $filter == 'ALL') {

            $ret .= '';
            $this -> ShowOnlyPending = false;

        // we need to show anly the 10 finalists
        }elseif(!empty($filter) && $filter == 'Selected10'){

            $_REQUEST['qty'] = 10;
            return $ret."  AND `CloroxPickTop10` = 1 ". $this -> generateDateFilter('this week monday 1:00 am', 'this week sunday 12:59pm');

        // request to see all finalists
        }elseif(!empty($filter) && $filter == 'Finalists'){

             return $ret."  AND `Finalist` = 1 ";

        }elseif(!empty($filter)  && $filter == 'Showdown'){
            $this->setPopularityShowdownMoments();
            $ret .= "  AND `selectedForWeeklyShowdown` = 1 OR `selectedForWeeklyShowdownUsers` = 1";

        }elseif(!empty($filter)){

            $ret .= "  AND `approval` = '" . $filter . "' ";

        }


        return $ret . $this -> generateDateFilter(). $this -> generateTextSearch();
    }

    public  function generateTextSearch(){
        if(!empty($_REQUEST) && !empty($_REQUEST['textSearch'])){
            $search = urldecode($_REQUEST['textSearch']);
            return " AND `submission` LIKE '%".$search."%' ";
        }
        return '';
    }

    /**
     * generateDateFilter function
     * Purpose will select Moments in a time range
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     */
    public  function generateDateFilter($startDate = null, $endDate = null) {
        //error_log('BLM generate DATE Filter is running');
        if(!empty($startDate)){
            $_REQUEST['startDate'] = date('U', strtotime($startDate));
        }

        if(!empty($endDate)){
            $_REQUEST['startDate'] = date('U', strtotime($endDate));
        }

        $filter = "";

        // Set the stating date period to show using the GET or POST
        if (!empty($_REQUEST['startDate'])) {
            $this -> ShowStartingDate = $_REQUEST['startDate'];
        }
        // Set the ending date period endDate using GET or POST
        if (!empty($_REQUEST['endDate'])) {
            $this -> ShowEndingDate = $_REQUEST['endDate'];
        }

        // Test if there is a request for a start date at all
        if (!empty($this -> ShowStartingDate)) {
            // generate time stamp for the request
            $dateFrom = date('U', strtotime($this -> ShowStartingDate));
        }

        // test if there is a ending date at all
        if (!empty($this -> ShowEndingDate)) {
            // generates timestamp
            $dateTo = date('U', strtotime($this -> ShowEndingDate));
        }
        if (!empty($dateFrom) && !empty($dateTo)) {
            $filter = " AND `created_timestamp` >= STR_TO_DATE('" . $this -> ShowStartingDate . "', '%Y-%m-%d %H:%i:%s') AND `created_timestamp` <=  STR_TO_DATE('" . $this -> ShowEndingDate . "', '%Y-%m-%d %H:%i:%s')";
        }
        return $filter;
    }

    public function calculateQuantityOfMomentsToManage($QuantityOfMomentsToManage = null) {

        //error_log('BLM Calculate QTY To manage is running');
        // php acccess to the quantity of moments to display for CMS purpose
        if (!empty($QuantityOfMomentsToManage)) {

            $this -> QuantityOfMomentsToManage = $QuantityOfMomentsToManage;
            $_SESSION['quantityOfMomentsToManage'] = $this -> QuantityOfMomentsToManage;

        }
        // Required quantity of moments by Get or POST Ajax request
        elseif (!empty($_REQUEST['qty'])) {

            $this -> QuantityOfMomentsToManage = $_REQUEST['qty'];

            if ($this -> QuantityOfMomentsToManage > 500) {
                $this -> QuantityOfMomentsToManage = 500;
            }
            $_SESSION['quantityOfMomentsToManage'] = $this -> QuantityOfMomentsToManage;

        }
        // Default the quantity to show  to 10 if the value is not set
        if (empty($this -> QuantityOfMomentsToManage)) {

            $this -> QuantityOfMomentsToManage = 10;
            $_SESSION['quantityOfMomentsToManage'] = 10;

        }

        // limit the quantity to show in we are in the cms side of the admin
        // SS admin side is very slow so 500m is pretty much the acceptable value
        if ($this -> QuantityOfMomentsToManage >= 500 && empty($_REQUEST['qty'])) {

            $this -> QuantityOfMomentsToManage = 500;
            $_SESSION['quantityOfMomentsToManage'] = 500;

        }
        if(empty($_SESSION['quantityOfMomentsToManage'])){
            $_SESSION['quantityOfMomentsToManage'] = 20;
        }
        return $_SESSION['quantityOfMomentsToManage'];
    }

    public  function calculateActualPage() {
        //error_log('BLM Calculate Actual Page is running');
        if (!empty($_REQUEST['page'])) {

            $_SESSION['page'] = $_REQUEST['page'];

        }
        else {

            $_SESSION['page'] = 0;

        }

        return $_SESSION['page'];
    }

    public  function nextPage() {
        $this -> calculateActualPage();
        if ($_SESSION['page'] < $_SESSION['totalPages']) {
            return $_SESSION['page'] + 1;
        }
        return $_SESSION['page'] = 1;
    }

    public  function precedentPage() {
        $this -> calculateActualPage();
        if ($_SESSION['page'] > 1) {
            return $_SESSION['page'] - 1;
        }
        return $_SESSION['page'] = 1;
    }

    /**
     * function calculateTotalPages
     *
     * Purpose calculates the quantity of pages for a query
     * step 1 will calculate how many moments to manage
     * step 2 calculates the actual page
     * step 3 calculates the total amount of moments count
     * then we divide by the limit
     *
     * @author luc Martin -at- clorox
     * @version $ID
     */
    public function calculateTotalPages($onlyCountPages = false) {

        //error_log('BLM CALCULATE TOTAL Pages is running');

        $this -> calculateQuantityOfMomentsToManage();
        $this -> calculateActualPage;
        $this -> calculateCount();

        // calculates the limit
        $start = 1;
        $end = $_SESSION['quantityOfMomentsToManage'];

        $ret = '';

        if ($_SESSION['totalCount'] > $_SESSION['quantityOfMomentsToManage']) {

            $_SESSION['totalPages'] = $_SESSION['totalCount'] / $_SESSION['quantityOfMomentsToManage'];

        }
        else {
            $_SESSION['totalPages'] = 1;
        }

        if($onlyCountPages == true){
            return round($_SESSION['totalPages']);
        }

        $selected = '';


        for ($n = 1; $n <= $_SESSION['totalPages']; $n++) {
            if(!empty($_REQUEST['page']) && $n == $_REQUEST['page'] -1){
                $selected = 'selected';
            }
            $pageNumber = $n;
            $current = $ret .= '<option value="' . $start . '" '.$selected.'>page ' . $pageNumber . '</option>';

            $start = $start + $_SESSION['quantityOfMomentsToManage'] + 1;
            $end = $end + $_SESSION['quantityOfMomentsToManage'] + 1;
            $selected = '';
        }
        return $ret;
    }

    public function calculateCount() {
        //error_log('BLM calculate COUNT is running '.$this -> generateFilter());
        return $_SESSION['totalCount'] = DB::query('SELECT COUNT("id") FROM BLMoment' . $this -> generateFilter()) -> value();

    }

    public  function calculateLimit() {
        //error_log('BLM calculate LIMIT is running');
        $this->calculateQuantityOfMomentsToManage();

        $this->calculateActualPage();

        if (!empty($_REQUEST['limit'])) {
            return $_REQUEST['limit'];
        }

        $this -> calculateActualPage();
        $_SESSION['limit'] =  $_SESSION['page'] * $_SESSION['quantityOfMomentsToManage'];

        return $_SESSION['limit'];
    }
     /**
     * public function thisWeekCloroxPickTop10
     * purpose: return the selected showdown moments for the week
     * @author Luc Martin _at- Clorox
     * @version $ID
     */
    public function thisWeekCloroxPickTop10() {


        $weekBegin = Date('Y-m-d H:i:s', strtotime('-2 week Sunday 12:59 pm'));
        $nextWeekBegin = Date('Y-m-d H:i:s', strtotime('+1 week Monday 1:00 am'));

        $ret = BLMoment::get() -> where("`CloroxPickTop10` = true AND `created_timestamp` >= STR_TO_DATE('" . $weekBegin . "', '%Y-%m-%d %H:%i:%s') AND `created_timestamp` <=  STR_TO_DATE('" . $nextWeekBegin . "', '%Y-%m-%d %H:%i:%s')");

        return $ret;
    }
    /**
     * function setPopularityShowdownMoments
     * purpose: mark popular moments as showdown winners
     * If not it will query for the most votes and select the winner
     *
     * @author Luc martin -at- Clorox.com
     * @version $ID
     */
    public  function setPopularityShowdownMoments(){
        // Default values of variables to prevent errors
        $cloroxSelectedId=0;

        for ($n = -1 ; $n > -20; --$n){

            //error_log('Running '.$n);
            // we go back to the precedent Show down period
            $cutInDate = Date('Y-m-d H:i:s', strtotime(($n -1).' week Monday 12:00:00'));
            $cutOutDate = Date('Y-m-d H:i:s', strtotime($n.' week Monday 11:59:59'));

            $cloroxWinner = BLMoment::get()->where("`selectedForWeeklyShowdown` = 1  AND `created_timestamp` >= STR_TO_DATE('" . $cutInDate . "', '%Y-%m-%d %H:%i:%s') AND `created_timestamp` <=  STR_TO_DATE('" . $cutOutDate . "', '%Y-%m-%d %H:%i:%s')")->first();
            if(!empty($cloroxWinner -> ID) && empty($cloroxWinner -> showdownWeek)){
                $cloroxWinner -> showdownWeek = $cutInDate;
                $cloroxWinner->write();
            }

            // Try to get a finalist in the UserPopularity Showdown for that period
            $userPopularityWinner = BLMoment::get()-> where(" `created_timestamp` >= STR_TO_DATE('" . $cutInDate . "', '%Y-%m-%d %H:%i:%s') AND `created_timestamp` <=  STR_TO_DATE('" . $cutOutDate . "', '%Y-%m-%d %H:%i:%s')")->sort('popularity','DESC') -> first();
            if(!empty($userPopularityWinner->ID) && ($userPopularityWinner -> selectedForWeeklyShowdownUsers !==1 || empty($userPopularityWinner -> showdownWeek) )){
                $userPopularityWinner -> selectedForWeeklyShowdownUsers = 1;
                $userPopularityWinner -> showdownUsersWeek = $cutInDate;
                $userPopularityWinner -> showdownWeek = $cutInDate;
                $userPopularityWinner -> write();
            }

        }
    }

}

class BLMManagementPage_Controller extends BLMMasterPage_Controller {

    /**
     * Init function
     * Purpose run when the page is initiated
     *
     * @author Luc Martin at Clorox
     * @version $ID
     */
    public function init() {

        // Set the default of moments to generate
        if (!empty($_REQUEST['qty'])) {
            $this -> QuantityOfMomentsToManage = $_REQUEST['qty'];
        }

        // Javascript requirements
        Requirements::javascript("js/pages/blm-management-page.js");

        // prepare the Tips for the rotating sort order
        // This will generate a new sort order for the Tips depending of the day of the week
        $args = array(
            'page' => $this,
            'sortableitemsClass' => 'BLMTip',
            'daysOfWeekToSortItems' => array(
                1,
                3,
                5
            ),
            'sortOrderName' => 'TipSortOrder'
        );
        // The timed sorter is the plugin that sorts an object using the day of the week
        //$sort = new TimedSorter();
        //$sort -> sortItems($args);

        parent::init();
    }



}
