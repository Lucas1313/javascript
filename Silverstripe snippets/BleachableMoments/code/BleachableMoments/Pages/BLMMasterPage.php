<?php
class BLMMasterPage extends page {
    static $db = array();

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        return $fields;

    }

    /**
     * function randomizeDrLaundryBlogEntry($displayCount=3)
     * Description: This returns $displayCount random posts from the last $displayCount * 10 latest posts
     * Input:
     * @var: $displayCount = the number of blog posts to return after the random selection is made
     *
     * Internal:
     * @var: $blogPostCount = $displayCount*10;
     * @var: $blogPosts
     * @var: $blogPostsarray
     * @var: $randomArray
     * @var: $n
     *
     */
    public function randomizeDrLaundryBlogEntry($displayCount = 3) {
        $blogPostCount = $displayCount * 10;
        $blogPosts = DrLaundryBlogEntry::get() -> sort('Date', 'DESC') -> limit($blogPostCount);
        $blogPostsarray = array();
        $randomArray = array();
        for ($i = 0; $i < $displayCount; $i++) {
            $n = rand(0, $blogPostCount);

            while (in_array($n, $randomArray) == 1) {
                if ($n >= sizeof($blogPosts)) {
                    $multiplier = -1;
                }
                else {
                    $multiplier = 1;
                }
                $n = $n + $multiplier;
            }
            $randomArray[] = $n;
            $blogPostsarray[] = $blogPosts[$n];
        }

        return new ArrayList($blogPostsarray);
    }
    /**
     * function getLastWinner
     * returns the last winning moment
     * @author Luc Martin
     */
    public function getLastWinner(){
        $winner = BLMoment::get()->filter(array('Finalist'=>true))->sort('ID','DESC')->first();
        return $winner;
    }

}

class BLMMasterPage_Controller extends Page_Controller {
    public function init() {
        Requirements::javascript("js/pages/blm-common.js");
        Requirements::javascript("js/plugins/plugin-snapguide-embed.js");
        parent::init();
    }

    /**
     * function sectionName()
     * description:  this returns the defined name of this major section of the website.
     * 				the reason for this function is to have custom defined CSS files that
     * 				will override the standard files... without touching the original styling
     * @author: kody smith -at- clorox.com
     */
    public function sectionName() {
        return 'BLM';
    }

    /**
     * function bricksCoupon()
     * description: Generates a link for coupons
     * @author: kody smith -at- clorox.com & Luc Martin -at- Clorox
     */
    public function bricksCoupon() {

        // the keys constant are defined in the /BleachableMoments/_config.php file
        $offerCode = BLM_OFFER_CODE;
        $shortKey = BLM_SHORT_KEY;
        $longKey = BLM_LONG_KEY;
        $clientID = BLM_CLIENT_ID;

        // generate the link for coupons
        if (isset($offerCode) && isset($shortKey) && isset($longKey) && isset($clientID)) {

            //$couponEmail = new BricksCoupon();
            $link = BricksCoupon::EncodedURL($offerCode, $shortKey, $longKey, $clientID);

        }
        return $link;
    }

    public function index() {

        ////error_log('PROCESSING REQUEST FROM MASTER PAGE AJAX? '.Director::is_ajax());
        // setting a variable if the page request is
        // intended to be an ajax call
        if (Director::is_ajax()) {

            $this -> isAjax = true;

            return $this -> processAjaxRequest();
        }
        else {
            $this -> processQueries();
            return Array();
            // execution as usual in this case...
        }
    }

    /**
     * processQueries function
     * purpose: processes special queries for debugging and cheating.
     * This function is mostly for QA, it gets the queries and deletes votes and moment submissions
     *
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     */
    public  function processQueries() {
        ////error_log('PROCESSING QUERIES');
        // cleanup
        foreach ($_REQUEST as $key => $value) {
            $_REQUEST[$key] = Convert::raw2sql($value);
            if ($value == 'false') {
                $_REQUEST[$key] = 0;
            }
            elseif ($value == 'true') {
                $_REQUEST[$key] = true;
            }
        }

        $Group = DataObject::get_one('Group', "Code = 'Marketing'");

        //if(Member::currentUser()->inGroup($Group->ID)){

        ////error_log('Checking permissions on Marketing '.Member::currentUser()->inGroup($Group->ID));
        //THESE LINES OF CODE WERE THROWING AN ERROR. PLEASE CHECK.

        ////error_log('Checking permissions on Marketing '.Member::currentUser()->inGroup($Group->ID));
        // Admin only
        //if(Permission::check('ADMIN') !== true && Member::currentUser()->inGroup($Group->ID) !== true) {
        // only admins
        //return json_encode(array('error' => 'You need to be admin to do stuff like this that data'));
        //}

        if (Member::currentUser()) {
            try {
                $member = Member::currentUser();
                $consumer_id = $member -> pc_consumer_id;
            }
            catch(Exception $e) {
                error_log('Caught exception: ' . $e -> getMessage());
            }
        }

        if (!empty($consumer_id)) {

            if (!empty($_REQUEST['resetShowdownVoteSubmission'])) {

                //error_log('resetShowdownVoteSubmission '.$consumer_id);
                $query = DB::query("DELETE FROM `vote` WHERE `consumer_id` = " . $consumer_id . " AND `VoteType` = 'ShowdownVotes'");

            }
            if (!empty($_REQUEST['resetSingleVoteSubmission'])) {

                //error_log('resetSingleVoteSubmission');
                $query = DB::query("DELETE FROM `vote` WHERE `consumer_id` = " . $consumer_id . " AND `VoteType` = 'popularity'");
            }

            if (!empty($_REQUEST['resetMomentSubmission'])) {

                //error_log('resetSingleVoteSubmission');
                $query = DB::query("DELETE FROM `vote` WHERE `consumer_id` = " . $consumer_id . " AND `VoteType` = 'momentSubmission'");

            }
            if (!empty($_REQUEST['resetAllSubmissions'])) {

                //error_log('resetAllSubmissions');
                $query = DB::query("DELETE FROM `vote` WHERE `consumer_id` = " . $consumer_id . " AND `VoteType` = 'momentSubmission' OR `VoteType` = 'ShowdownVotes' OR  oteType` = 'popularity'");

            }
        }
    }

    /**
     * processAjaxRequest() function
     * catch all function to dispatch Ajax request to the SS system
     *
     * @author Luc Martin at Clorox
     * @version $ID
     */
    public  function processAjaxRequest() {
        //error_log('PROCESSING REQUEST FROM MASTER PAGE');
        if (!isset($_REQUEST)) {
            return json_encode(array(
                'response' => 'The request was empty',
                'error' => '001 Request Empty'
            ));
        }

        foreach ($_REQUEST as $key => $value) {
            $_REQUEST[$key] = Convert::raw2sql($value);
            if ($value == 'false') {
                $_REQUEST[$key] = 0;
            }
            elseif ($value == 'true') {
                $_REQUEST[$key] = true;
            }
        }

        $type = $_REQUEST['type'];
        //error_log('Processing request');
        switch($type) {
            case 'saveSingleMoment' :
                return $this -> saveSingleMoment();
                break;
            case 'popularity' :
            case 'ShowdownVotes' :
                return $this -> voteForBLMoment();
                break;
            case 'showdownSelection' :
                //error_log('SAVE SHOWDOWN');
                return $this -> saveShowdown();
                break;
            case 'generateBlMomentsUsingFilter' :
                $this -> generateFilterAndExcludeArrays();
                return $this -> generateBlMomentsUsingFilter();
                break;
            case 'lazyLoad' :
                $this -> generateFilterAndExcludeArrays();

                if (!empty($_REQUEST['filterBy'])) {
                    return parent::generateLazyLoadPage();
                }
                return parent::generateLazyLoadPage();
                break;
        }
    }

    /**
     * isLoggedIn function.
     * This returns a true or false if the user is loggedin
     *
     * @return object boolean true/false
     */
    function isLoggedIn() {
        if (Member::currentUserID()) {
            return true;
        }
        else {
            return false;
        }
    }

    public  function generateFilterAndExcludeArrays() {

        $quantity = (!empty($_REQUEST['quantity'])) ? $_REQUEST['quantity'] : 100;

        $filter = (!empty($_REQUEST['filterBy'])) ? $_REQUEST['filterBy'] : null;

        $filterAr = explode(',', $filter);

        $filterValue = (!empty($_REQUEST['filterValue'])) ? $_REQUEST['filterValue'] : null;
        $filterValueAr = explode(',', $filterValue);

        $filterRet = array();

        foreach ($filterAr as $key => $value) {
            if (!empty($filterRet[$value])) {
                $filterRet[$value] = array(
                    $filterRet[$value],
                    $filterValueAr[$key]
                );
            }
            else {
                $filterRet[$value] = $filterValueAr[$key];
            }

        }

        $_REQUEST['filterBy'] = $filterRet;

        $exclude = (!empty($_REQUEST['excludeBy'])) ? $_REQUEST['excludeBy'] : null;

        $excludeRet = array();

        if (!empty($exclude)) {
            $excludeAr = explode(',', $exclude);

            $excludeValue = (!empty($_REQUEST['excludeValue'])) ? $_REQUEST['excludeValue'] : null;
            $excludeValueAr = explode(',', $excludeValue);

            foreach ($excludeAr as $key => $value) {
                $excludeRet[$value] = $excludeValue[$key];
            }
            $_REQUEST['excludeBy'] = $excludeRet;
        }
    }

    public  function generateBlMomentsUsingFilter() {
        //PC::debug('FILTERING');
        $ret = '';
        $renderWithTemplate = 'AjaxLazyBLMoment';

        $quantity = (!empty($_REQUEST['quantity'])) ? $_REQUEST['quantity'] : 100;

        if (empty($returnObjects)) {
            $returnObjects = (empty($_REQUEST['excludeBy'])) ? BLMoment::get() -> filter($_REQUEST['filterBy']) -> sort('ID', 'DESC') -> limit($quantity) : BLMoment::get() -> exclude($_REQUEST['excludeBy']) -> sort('ID', 'DESC') -> limit($quantity);
        }

        foreach ($returnObjects as $key => $object) {
            $object -> submission = str_replace('\\n', ' ', $object -> submission);
            $object -> submission = str_replace('\\', '', $object -> submission);
            $ret .= $object -> renderWith($renderWithTemplate);
        }
        //error_log($ret);
        return $ret;
    }

    /**
     * voteForBLMoment function
     * Purpose: register a vote for a Bleachable moment from a ajax request
     *
     * @author LucMartin -at- Clorox.com
     * @version $ID
     */
    public  function voteForBLMoment() {
        //error_log('running votes');
        $voteCount = 0;
        $itemId = $_REQUEST['id'];

        // Is a member logged in?
        $member = Member::currentUser();
        if (empty($member -> pc_consumer_id)) {
            return array('error' => array(
                    'message' => 'There is no PC_Consumer associated with that request',
                    'redirect' => 'sign-up'
                ));
        }
        $consumer_id = $member -> pc_consumer_id;

        $this -> votingMachine = new Voting_Controller();
        $allowed = $this -> votingMachine -> allowVotingForUser('Bleachable Moments', $_REQUEST['type'], $consumer_id, $itemId, 'BLMoment');
        if ($allowed == true) {
            $voteCount = $this -> votingMachine -> registerVote(null, 1);
        }
        return json_encode(array(
            'status' => 'success',
            'voteCount' => $voteCount
        ));
    }

    function getLoginRedirect() {
        // TODO perform a redirect
        return 'redirect';
    }

    /** lastSubmissionStatus function
     * Determines if last submission was
     * 'never', 'recent' (less than 1 week),
     * or 'old' (more than 1 week)
     *
     * @param $consumer_id integer consumer id
     */
    public  $votingMachine;
    public function lastSubmissionStatus($consumer_id) {
        $this -> votingMachine = new Voting_Controller();
        $allowed = $this -> votingMachine -> allowVotingForUser('Bleachable Moments', 'momentSubmission', $consumer_id, 0, 'BLMoment');
        $previous = BLMoment::get() -> filter(array('consumer_id' => $consumer_id)) -> sort('ID', 'DESC');
        $first = $previous -> first();

        if (!$first) {
            $status = 'never';
        }

        else {
            $allowed = $this -> votingMachine -> allowVotingForUser('Bleachable Moments', 'momentSubmission', $consumer_id, 0, 'BLMoment');

            $status = ($allowed == true) ? 'old' : 'recent';
            //error_log('CAN USER VOTE ON THAT MOMENT???  '.'momentSubmission'.' ' . $allowed);
        }
        return $status;
    }

    /** createSingleMoment() function
     * Creates/saves new moment for a user
     *
     * @param $submission str the text of the submission
     * @param $consumer_id integer consumer id
     * @return last submission status ('recent', 'never', 'old')
     *
     */
    public function createSingleMoment($submission, $consumer_id) {

        $last = $this -> lastSubmissionStatus($consumer_id);

        if ($last == 'recent') {
            return 'recent';
        }
        elseif ($last == 'never') {
            $firstSubmission = true;
        }
        else {
            $firstSubmission = false;
            $MultipleSubmission = true;
        }

        $moment = new BLMoment();
        $moment -> modified_timestamp = date('Y-m-d H:i:s', strtotime('now'));
        $moment -> created_timestamp = date('Y-m-d H:i:s', strtotime('now'));
        $moment -> approval = 'PENDING';
        $moment -> consumer_id = $consumer_id;
        $moment -> submission = str_replace('\\n', ' ', $submission);
        $moment -> submission = str_replace('\\', '', $moment -> submission);
        $moment -> write();
        $voteCount = $this -> votingMachine -> registerVote($moment -> ID);

        if ($firstSubmission) {
            $changed = array(
                'changedApproval' => false,
                'changedSolved' => false,
                'changedTop10' => false,
                'changedFinalist' => false,
                'changedFirstSubmission' => $firstSubmission
            );

        }
        elseif ($MultipleSubmission) {
            $changed = array(
                'changedApproval' => false,
                'changedSolved' => false,
                'changedTop10' => false,
                'changedFinalist' => false,
                'changedFirstSubmission' => false,
                'changedMultipleSubmission' => true,
            );

        }
        $this -> notifyUserByEmail($moment, $changed);
        return $last;
    }

    /**
     * saveSingleMoment() function
     * Saves a single Bleacheable moment
     * used by management page.
     *
     * @author Luc Martin at Clorox
     * @version $ID
     */
    public  function saveSingleMoment() {
        $changedAr = array();
        $Group = DataObject::get_one('Group', "Code = 'Marketing'");

        //if(Member::currentUser()->inGroup($Group->ID)){

        //error_log('Checking permissions on Marketing '.Member::currentUser()->inGroup($Group->ID));
        // Admin only
        if (Permission::check('ADMIN') !== true && Member::currentUser() -> inGroup($Group -> ID) !== true) {

            return json_encode(array('error' => 'You need to be admin to save that data'));
        }

        $BLMoment = BLMoment::get() -> filter(array('ID' => $_REQUEST['id'])) -> first();
        $consumer_id = $BLMoment -> consumer_id;
        $changed = false;
        $changed = ($BLMoment -> submission !== $_REQUEST['submission'] || $changed == true ? true : false);

        $changedApproval = $changed = ($BLMoment -> approval !== $_REQUEST['approval'] || $changed == true ? $_REQUEST['approval'] : false);

        $changedSelectedForWeeklyShowdown = $changed = ($BLMoment -> selectedForWeeklyShowdown !== $_REQUEST['selectedForWeeklyShowdown'] || $changed == true ? true : false);
        $changedSelectedForWeeklyShowdownUser = $changed = ($BLMoment -> selectedForWeeklyShowdownUsers !== $_REQUEST['selectedForWeeklyShowdownUsers'] || $changed == true ? true : false);

        $changedFinalist = $changed = ($BLMoment -> Finalist !== $_REQUEST['Finalist'] || $changed == true ? true : false);
        $changedFinalistWeek = $changed = ($BLMoment -> FinalistWeek !== $_REQUEST['FinalistWeek'] || $changed == true ? true : false);
        $changedSolved = $changed = ($BLMoment -> solved !== $_REQUEST['solved'] || $changed == true ? true : false);

        $changedSolved = $changed = ($BLMoment -> solved !== $_REQUEST['solved'] || $changed == true ? true : false);
        $changedTop10 = $changed = ($BLMoment -> CloroxPickTop10 != $_REQUEST['CloroxPickTop10'] || $changed == true ? true : false);
        $changedFinalist = $changed = ($BLMoment -> Finalist != $_REQUEST['Finalist'] || $changed == true ? true : false);

        if ($changed == false) {
            return json_encode(array('changed' => false));
        }

        /*/ Log changes
        if ($changed) {
            $BLMomentId = 'BLMoment_'.$BLMoment -> ID;
            // Now we need to log the changes
            CCL_Log::getLog(__CLASS__) -> addLog($BLMomentId, CCL_Log::SPT);

            CCL_Log::write(__CLASS__, '******************************** ' . $BLMoment -> ID . ' ************************************', $BLMomentId);
            CCL_Log::write(__CLASS__, 'MOMENT ID >>> ' . $BLMoment -> ID, $BLMomentId);

            CCL_Log::write(__CLASS__, 'LastEditorName = ' . $BLMoment -> LastEditorName . ' >>> ' . Member::currentUser() -> FirstName . ' ' . Member::currentUser() -> Surname, $BLMomentId);
            CCL_Log::write(__CLASS__, 'LastEditorId = ' . $BLMoment -> LastEditorId . ' >>> ' . Member::currentUserId(), $BLMomentId);

            $submission = $BLMoment -> submission;
            if ($submission !== $_REQUEST['submission']) {
                CCL_Log::write(__CLASS__, 'submission = ' . $BLMoment -> submission . ' >>> ' . $_REQUEST['submission'], $BLMomentId);
            }

            $approval = $BLMoment -> approval;
            if ($approval !== $_REQUEST['approval']) {
                CCL_Log::write(__CLASS__, 'approval = ' . $BLMoment -> approval . ' >>> ' . $_REQUEST['approval'], $BLMomentId);
            }

            $solved = $BLMoment -> solved;
            if ($solved !== $_REQUEST['solved']) {
                CCL_Log::write(__CLASS__, 'solved = ' . $BLMoment -> solved . ' >>> ' . $_REQUEST['solved'], $BLMomentId);
            }

            $cloroxPickTop10 = $BLMoment -> CloroxPickTop10;
            if ($cloroxPickTop10 != $_REQUEST['CloroxPickTop10']) {
                CCL_Log::write(__CLASS__, 'CloroxPickTop10 = ' . $BLMoment -> CloroxPickTop10 . ' >>> ' . $_REQUEST['CloroxPickTop10'], $BLMomentId);
            }

            $finalist = $BLMoment -> Finalist;
            if ($finalist !== $_REQUEST['Finalist']) {
                CCL_Log::write(__CLASS__, 'Finalist = ' . $BLMoment -> Finalist . ' >>> ' . $_REQUEST['Finalist'], $BLMomentId);
            }

            $selectedForWeeklyShowdown = $BLMoment -> selectedForWeeklyShowdown;
            if ($selectedForWeeklyShowdown !== $_REQUEST['selectedForWeeklyShowdown']) {
                CCL_Log::write(__CLASS__, 'selectedForWeeklyShowdown = ' . $BLMoment -> selectedForWeeklyShowdown . ' >>> ' . $_REQUEST['selectedForWeeklyShowdown'], $BLMomentId);
            }

            $showdownWeek = $BLMoment -> showdownWeek;
            if ($BLMoment -> showdownWeek !== $_REQUEST['showdownWeek']) {
                CCL_Log::write(__CLASS__, 'showdownWeek = ' . $BLMoment -> showdownWeek . ' >>> ' . $_REQUEST['showdownWeek'], $BLMomentId);
            }

            $selectedForWeeklyShowdownUsers = $BLMoment -> selectedForWeeklyShowdownUsers;
            if ($selectedForWeeklyShowdownUsers !== $_REQUEST['selectedForWeeklyShowdownUsers']) {
                CCL_Log::write(__CLASS__, 'selectedForWeeklyShowdownUsers = ' . $BLMoment -> selectedForWeeklyShowdownUsers . ' >>> ' . $_REQUEST['selectedForWeeklyShowdownUsers'], $BLMomentId);
            }

            $showdownUsersWeek = $BLMoment -> showdownUsersWeek;
            if ($showdownUsersWeek !== $_REQUEST['showdownUsersWeek']) {
                CCL_Log::write(__CLASS__, 'showdownUsersWeek = ' . $BLMoment -> showdownUsersWeek . ' >>> ' . $_REQUEST['showdownUsersWeek'], $BLMomentId);
            }
            CCL_Log::write(__CLASS__, '********************************END ' . $BLMoment -> ID . ' ************************************', $BLMomentId);

        }*/
        $BLMoment -> submission = $_REQUEST['submission'];
        $BLMoment -> approval = $_REQUEST['approval'];
        $BLMoment -> solved = $_REQUEST['solved'];
        $BLMoment -> CloroxPickTop10 = $_REQUEST['CloroxPickTop10'];
        $BLMoment -> Finalist = $_REQUEST['Finalist'];
        $BLMoment -> LastEditorName = Member::currentUser() -> FirstName . ' ' . Member::currentUser() -> Surname;
        $BLMoment -> LastEditorId = Member::currentUserId();
        $BLMoment -> modified_timestamp = date('Y-m-d H:i:s', strtotime('now'));

        $BLMoment -> selectedForWeeklyShowdown = $_REQUEST['selectedForWeeklyShowdown'];
        $BLMoment -> showdownWeek = $_REQUEST['showdownWeek'];
        $BLMoment -> selectedForWeeklyShowdownUsers = $_REQUEST['selectedForWeeklyShowdownUsers'];
        $BLMoment -> showdownUsersWeek = $_REQUEST['showdownUsersWeek'];

        $this -> notifyUserByEmail($BLMoment, array(
            'changedApproval' => $changedApproval,
            'changedSolved' => $changedSolved,
            'changedTop10' => $changedTop10,
            'changedFinalist' => $changedFinalist,
            'changedSelectedForWeeklyShowdown' => $changedSelectedForWeeklyShowdown,
            'changedFirstSubmission' => false
        ));
        $BLMoment -> write();
        $Group = DataObject::get_one('Group', "Code = 'Marketing'");

        //if(Member::currentUser()->inGroup($Group->ID)){

        //error_log('Checking permissions on Marketing '.Member::currentUser()->inGroup($Group->ID));
        // Admin only
        $isMarketing = (Member::currentUser() -> inGroup($Group -> ID)) ? true : false;

        return json_encode(array(
            'changed' => true,
            'member' => Member::currentUser(),
            'admin' => Permission::check('ADMIN'),
            'Marketing' => $isMarketing,
            'FirstName' => Member::currentUser() -> FirstName,
            'LastName' => Member::currentUser() -> Surname,
            'modified_timestamp' => $BLMoment -> modified_timestamp
        ));
    }

    /**
     * notifyUserByEmail function
     * Purpose Notify a customer if their submission has changed
     * @param $BLMoment // BLMMoment object
     * @param $changedStatus Array with all changed fields
     * @author Luc Martin at Clorox
     * @version $ID
     */
    public  function notifyUserByEmail($BLMoment, $changedStatus) {
        $member = Member::currentUser();
        $consumer = new CCL_PC_Model_Consumer();
        $consumer -> load($BLMoment -> consumer_id);
        $email = $consumer -> getEmailAddress();
        $link = WEBROOT . '/laugh/bleach-it-away/vote-for-moments/moment/idnumber/' . $BLMoment -> ID;
        if (empty($email)) {
            return;
        }

        $Moment_Link = 'www.clorox.com/laugh/bleach-it-away/vote-for-moments/moment/idnumber/' . $BLMoment -> ID;
        $SD_Winners_Link = 'www.clorox.com/laugh/bleach-it-away/vote-for-moments/#winners';

        foreach ($changedStatus as $key => $value) {

            if ($value == true) {
                switch($key) {
                    case 'changedApproval' :
                        //error_log('::::::::: changed approval:::::::');
                        if ($BLMoment -> approval == "REJECTED") {
                            $campaign = 43912292;
                            $emailVariableArray = array(
                                'NAME_FIRST' => $member -> FirstName,
                                'EMAIL' => $email,
                                'Moment_Link' => $Moment_Link,
                            );
                        }
                        break;
                    case 'changedSolved' :
                        //error_log('::::::::: changed changedSolved:::::::');
                        $campaign = 42315291;
                        $emailVariableArray = array(
                            'NAME_FIRST' => $member -> FirstName,
                            'Moment_Link' => $Moment_Link,
                        );
                        break;
                    case 'changedTop10' :
                        //error_log('::::::::: changed changedTop10:::::::');
                        $campaign = null;
                        break;
                    case 'changedFinalist' :
                        //error_log('::::::::: changed changedFinalist:::::::');
                        $campaign = 43912296;
                        $emailVariableArray = array(
                            'NAME_FIRST' => $member -> FirstName,
                            'Moment_Link' => $Moment_Link,
                            'SD_Winners_Link' => $SD_Winners_Link,
                            'EMAIL' => $email,
                        );
                        break;
                    case 'changedSelectedForWeeklyShowdown' :
                        //error_log('::::::::: changed changedSelectedForWeeklyShowdown:::::::');
                        $campaign = 42315291;
                        $emailVariableArray = array(
                            'NAME_FIRST' => $member -> FirstName,
                            'EMAIL' => $email,
                        );
                        break;
                    case 'changedFirstSubmission' :
                        //error_log('::::::::: changed changedFirstSubmission:::::::');
                        $campaign = 43912197;
                        $pinCode = $member -> ID;
                        $Coupon_Link = CCL_CouponsInc::getCouponURL(BLM_OFFER_CODE, BLM_CHECK_CODE, $pinCode, BLM_SHORT_KEY, BLM_LONG_KEY);
                        $Coupon_Link = substr($Coupon_Link, 7, strlen($Coupon_Link));
                        //error_log('::::::::: changed $Coupon_Link:::::::'.$Coupon_Link);
                        $emailVariableArray = array(

                            'NAME_FIRST' => $member -> FirstName,
                            'Coupon_Link' => $Coupon_Link,
                            'Moment_Link' => $Moment_Link,
                            'EMAIL' => $email,
                        );
                        break;
                    case 'changedMultipleSubmission' :
                        //error_log('::::::::: changed changedMultipleSubmission:::::::');
                        $campaign = 43912199;
                        $emailVariableArray = array(
                            'NAME_FIRST' => $member -> FirstName,
                            'Moment_Link' => $Moment_Link,
                            'EMAIL' => $email,
                        );
                        break;
                    default :
                }
                if (empty($campaign)) {
                    return;
                }
                $result = CCL_SilverpopTransact::generate($campaign, //campaign ID is the ID of CL_Classrooms_Pledge
                $email, $emailVariableArray);
            }
        }

    }

    /**
     * function setShowdownWinner
     * purpose: will check if the last showdown has winners
     * If not it will query for the most votes and select the winner
     *
     * @author Luc martin -at- Clorox.com
     * @version $ID
     */
    public  function setShowdownWinner() {
        // Default values of variables to prevent errors
        $cloroxSelectedId = 0;

        for ($n = -1; $n > -20; --$n) {

            if (!empty($_SESSION['winner set']) && $_SESSION['winner set'] == 1) {
                continue;
            }
            //error_log('Running '.$n);
            // we go back to the precedent Show down period
            $cutInDate = Date('Y-m-d H:i:s', strtotime(($n - 1) . ' week Monday 12:00:00'));
            $cutOutDate = Date('Y-m-d H:i:s', strtotime($n . ' week Monday 11:59:59'));

            // Try to get a finalist in the UserPopularity Showdown for that period
            $userPopularityWinner = DB::query("SELECT COUNT(`ID`) FROM BLMoment WHERE `Finalist` = true AND `created_timestamp` >= STR_TO_DATE('" . $cutInDate . "', '%Y-%m-%d %H:%i:%s') AND `created_timestamp` <=  STR_TO_DATE('" . $cutOutDate . "', '%Y-%m-%d %H:%i:%s') ORDER BY `popularity` DESC") -> value();

            // We got a winner, no need to continue
            if ($userPopularityWinner > 0) {
                continue;
            }

            // No winner so far let have a  look at the Clorox winner
            $cloroxWinner = DB::query("SELECT COUNT(`ID`) FROM BLMoment WHERE `Finalist` = 1 AND `created_timestamp` >= STR_TO_DATE('" . $cutInDate . "', '%Y-%m-%d %H:%i:%s') AND `created_timestamp` <=  STR_TO_DATE('" . $cutOutDate . "', '%Y-%m-%d %H:%i:%s')") -> value();

            // If There is a winner no need to continue
            if ($cloroxWinner > 0) {
                continue;
            }
            // The winner has not been selected for the week
            // first get the ID of the Clorox showdown selected
            $query = BLMoment::get() -> where("`selectedForWeeklyShowdown` = 1  AND `created_timestamp` >= STR_TO_DATE('" . $cutInDate . "', '%Y-%m-%d %H:%i:%s') AND `created_timestamp` <=  STR_TO_DATE('" . $cutOutDate . "', '%Y-%m-%d %H:%i:%s')");

            foreach ($query as $k => $v) {

                if (empty($v -> ID)) {
                    continue;
                }

                $cloroxSelectedId = $v -> ID;

            }

            // get the id of the user selected (popularity)
            $userSelected = BLMoment::get() -> where("`created_timestamp` >= STR_TO_DATE('" . $cutInDate . "', '%Y-%m-%d %H:%i:%s') AND `created_timestamp` <=  STR_TO_DATE('" . $cutOutDate . "', '%Y-%m-%d %H:%i:%s')") -> sort('popularity DESC') -> first();

            if (empty($userSelected -> ID)) {
                continue;
            }
            $userSelectedId = $userSelected -> ID;

            // now I need to select the vote count using the id and compare them
            try {
                $cloroxCount = DB::query("SELECT COUNT(`ID`) FROM `Vote` WHERE `PromotionName` = 'Bleachable Moments' AND `VoteType` = 'ShowdownVotes' AND 'itemId' =" . $cloroxSelectedId) -> value();
                $usersCount = DB::query("SELECT COUNT(`ID`) FROM `Vote` WHERE `PromotionName` = 'Bleachable Moments' AND `VoteType` = 'ShowdownVotes' AND 'itemId' =" . $userSelectedId) -> value();
                // Check to see if there is a legitimate value that isn't Default
                if ($cloroxSelectedId > 0) {
                    // We give advantage to the popularity winner because we are nice
                    $winnerId = ($cloroxCount > $usersCount) ? $cloroxSelectedId : $userSelectedId;

                    // Let write the winner so we don't need to do it again
                    $winnerMoment = BLMoment::get() -> filter(array('ID' => $winnerId)) -> first();
                    $winnerMoment -> Finalist = true;
                    $winnerMoment -> FinalistWeek = $cutInDate;
                    $winnerMoment -> write();
                }
                else {
                    //PC::debug('No Bleachable Moments ShowDown Winners Available');
                }
            }
            catch(Exception $e) {
                //PC::debug('Error loading queries for BLM Showdown winners');
            }

        }
        $_SESSION['winner set'] = true;
    }

    /**
     * public function getThisWeekShowdown
     * purpose: return the selected showdown moments for the week
     *
     * Also need to check and select the winners for the last showdown
     *
     * @var: $type is a string and is used to select what type of data to pass back
     * @var $type definitions:
     * 			'clorox' = return moments selected by clorox for showdown
     * 			'users' = return moments selected by users for showdown
     * 			'available' = return true / false of if clorox Moments are selected for showdown
     *
     * @author Luc Martin _at- Clorox
     * @author Kody Smith -at- Clorox 10-18-2013
     * @version $ID
     */
    public function getThisWeekShowdown($type) {

        $this -> setShowdownWinner();

        if (Date('U', strtotime('this week Monday 12:00:00')) == Date('U', strtotime('Today 12:00:00')) && Date('U', strtotime('Today 12:00:00')) <= Date('U', strtotime('now'))) {
            $cutInDate = Date('Y-m-d H:i:s', strtotime('-1 week Monday 12:00:00'));
            $cutOutDate = Date('Y-m-d H:i:s', strtotime('this week Monday 11:59:59'));
        }
        else {
            $cutInDate = Date('Y-m-d H:i:s', strtotime('-2 week Monday 12:00:00'));
            $cutOutDate = Date('Y-m-d H:i:s', strtotime('-1 week Monday 11:59:59'));

        }

        switch($type) {
            case 'clorox' :
                $allMoments = BLMoment::get() -> where("`selectedForWeeklyShowdown` = true AND `created_timestamp` >= STR_TO_DATE('" . $cutInDate . "', '%Y-%m-%d %H:%i:%s') AND `created_timestamp` <=  STR_TO_DATE('" . $cutOutDate . "', '%Y-%m-%d %H:%i:%s')") -> first();
                break;
            case 'users' :
                $allMoments = BLMoment::get() -> where("`created_timestamp` >= STR_TO_DATE('" . $cutInDate . "', '%Y-%m-%d %H:%i:%s') AND `created_timestamp` <=  STR_TO_DATE('" . $cutOutDate . "', '%Y-%m-%d %H:%i:%s')") -> sort('popularity DESC') -> first();
                break;
            case 'available' :
                $count = 0;
                $count = DB::query("SELECT COUNT(`ID`) FROM BLMoment WHERE `selectedForWeeklyShowdown` = true AND `created_timestamp` >= STR_TO_DATE('" . $cutInDate . "', '%Y-%m-%d %H:%i:%s') AND `created_timestamp` <=  STR_TO_DATE('" . $cutOutDate . "', '%Y-%m-%d %H:%i:%s')") -> value();
                $allMoments = ($count > 0) ? true : false;
                return $allMoments;
                break;
            default :
            //error_log('getThisWeekShowdown($type) did not find a match in BLMMasterPage controller');
        }

        $retAr = array();
        if (empty($allMoments)) {
            return;
        }
        foreach ($allMoments as $key => $moment) {
            $moment -> submission = str_replace('\\n', ' ', $moment -> submission);
            $moment -> submission = str_replace('\\', '', $moment -> submission);
            $retAr[] = $moment;
        }
        return new ArrayList($retAr);
    }

    /**
     * public function showdownAvailable
     * purpose: return if there are selected showdown moments for the week
     * @author Kody Smith _at- Clorox
     * @version $ID
     */
    public function showdownAvailable() {
        return $this -> getThisWeekShowdown('available');
    }

    /**
     * function saveShowdown()
     * Purpose: Will remove the selected for showdown for the week
     * and add a new one
     *
     * @author Luc martin -at- Clorox.com
     */
    public  function saveShowdown() {

        // Only admin of marketing

        $Group = DataObject::get_one('Group', "Code = 'Marketing'");

        //if(Member::currentUser()->inGroup($Group->ID)){

        //error_log('Checking permissions on Marketing '.Member::currentUser()->inGroup($Group->ID));
        // Admin only
        if (Permission::check('ADMIN') !== true && Member::currentUser() -> inGroup($Group -> ID) !== true) {
            return json_encode(array('error' => 'You need to be admin to save that data'));
        }
        //error_log('saveShowDown');
        // get the id from the ajax request
        $id = $_REQUEST['id'];

        // remove previous selected in showdown if exists
        $moment = $this -> getThisWeekShowdown('clorox');
        //error_log('Managing the showdown '.$moment -> selectedForWeeklyShowdown);
        if (!empty($moment -> selectedForWeeklyShowdown)) {
            $moment -> selectedForWeeklyShowdown = false;
            $moment -> write();
        }

        // prepare the new showdown
        $newShowDown = BLMoment::get() -> filter(array('ID' => $id)) -> first();
        $newShowDown -> selectedForWeeklyShowdown = true;
        $newShowDown -> write();

        // notify user by email
        if ($_REQUEST['notifyUser'] == 'true') {
            //error_log('notifyUser');
            $this -> notifyUserByEmail($newShowDown, array('changedSelectedForWeeklyShowdown' => true));
        }
        return json_encode($newShowDown -> ID);

    }

    /**
     * function getSortedMoments
     * Purpose get a list of Bleachable moments in a certain sort order
     * also this function cleans up the Older moments from the \p tags
     *
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     */
    public function getSortedMoments($limit = null, $sortOrder = null) {
        // Cleanup the request
        foreach ($_REQUEST as $key => $value) {

            $_REQUEST[$key] = Convert::raw2sql($value);

        }

        $sortOrder = (!empty($sortOrder)) ? $sortOrder : (!empty($_REQUEST['sort']) ? html_entity_decode($_REQUEST['sort']) : 'ID DESC');
        $limit = (!empty($limit)) ? $limit : (!empty($_REQUEST['limit']) ? html_entity_decode($_REQUEST['limit']) : '2');

        $allMoments = BLMoment::get() -> exclude(array('approval' => 'REJECTED')) -> sort($sortOrder) -> limit($limit);
        $retAr = array();

        foreach ($allMoments as $key => $moment) {
            $moment -> submission = str_replace('\\n', ' ', $moment -> submission);
            $moment -> submission = str_replace('\\', '', $moment -> submission);
            $retAr[] = $moment;
        }
        return new ArrayList($retAr);

    }

    public function BLMGifPromo() {
        return BLMGifPromo::get() -> first();
    }

    /**
     * function userIsRegistered
     * test if a user is registered
     * Returns true if user is registered
     *
     * @author Kody Smith & Luc Martin -at- clorox.com
     * @version $ID
     */
    public function userIsRegistered() {
        // $isRegistered = (Member::currentUserID()) ? true : false;
        $member = Member::currentUser();
        if ($member) {
            $isRegistered = true;
        }
        else {
            $isRegistered = 0;
        }
        return $isRegistered;
    }

    /**
     * userCanVote function
     * tests if a user can vote after registration
     *
     * @author Luc Martin -at- clorox.com
     * @version $ID
     */
    public function userCanVote($type = "ShowdownVotes") {

        $member = Member::currentUser();

        if (empty($member -> pc_consumer_id)) {

            return true;
        }
        $consumer_id = $member -> pc_consumer_id;

        $this -> votingMachine = new Voting_Controller();
        $allowed = $this -> votingMachine -> allowVotingForUser('Bleachable Moments', $type, $consumer_id, 0, 'BLMoment');
        //error_log('CAN USER VOTE ON THAT MOMENT???  '.$type.' ' . $allowed);
        return $allowed;
    }

    /**
     * lastVoteId function
     * tests if a user has voted during this show down
     *
     * @author Kody Smith -at- clorox.com
     * @version $ID
     */
    public function lastVoteId($type = "ShowdownVotes") {
        $member = Member::currentUser();
        if (empty($member -> pc_consumer_id)) {
            return 0;
        }
        $consumer_id = $member -> pc_consumer_id;

        $vote = Vote::get() -> filter(array(
            'VoteType' => $type,
            'consumer_id' => $consumer_id
        )) -> sort('ID', 'DESC') -> first();

        if (empty($vote -> itemId)) {
            return 0;
        }
        return $vote -> itemId;
    }

    public function RulesPDFURL() {
        $page = BLMLandingPage::get() -> first();
        $rulesURL = $page -> RulesPDF -> URL;
    }

    /**
     * Funtion RandomizeSSObject
     * Description: This function is to get some number of random results of objects to loop through in SS templates
     *      Give the function an object class name to find objects, and by default it will get upto 10 items and return 3
     *      of those 10 items at random, without duplicates
     *
     * @var: className  = some SS data object class name as a string
     * @var: displayCount  = the number of objects you want to return in your ArrayList to loop through in the SS file
     * @var: itemMaxCount = the total number of items you want to load from the database to pick from at random
     *
     * @return: silverstripe ArrayList() object
     *
     * @author: Kody Smith -at- clorox.com
     *
     * @version: {$ID}  2014-01-16
     *
     * */

    public function randomizeSSObject($className, $displayCount = 3, $itemMaxCount = 10) {

        $randomizer = new Randomizer;
        $randomizer -> setClassName($className);
        $randomizer -> setDisplayCount($displayCount);
        $randomizer -> setItemMaxCount($itemMaxCount);
        $randomizer -> selectRandom();
        return $randomizer -> result();
    }


}
