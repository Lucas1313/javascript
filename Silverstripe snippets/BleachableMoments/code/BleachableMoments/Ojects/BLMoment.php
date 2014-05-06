<?php
/**
 * BLMoment Object
 * Purpose Building blocks for the Bleacheable Moments marketing campain
 * @author Luc at Clorox.com
 * @version $ID
 */
class BLMoment extends DataObject {

        public $iterator = 0;


    public static $db = array(
        'Name' => 'Varchar(150)',
        'LastUpdated' => 'int',
        'LastEditorId'=>'int',
        'LastEditorName'=>'Text',
        'submission' => 'HTMLText',
        'created_timestamp' => "SS_Datetime",
        'modified_timestamp' => "SS_Datetime",
        'daily_winner' => 'Date',
        'favorite' => 'Date',
        'traffic_source' => "enum('CLOROX, FACEBOOK, TWITTER')",
        'consumer_id' => 'int(20)',
        'popularity' => 'int',
        'approval' => "enum('APPROVED, REJECTED, PENDING')",
        'VotesCount' => 'Int',
        'CloroxPickTop10'=>'Boolean',
        'selectedForWeeklyShowdown' => 'Boolean',
        'selectedForWeeklyShowdownUsers' => 'Boolean',
        'showdownWeek' => "SS_Datetime",
        'showdownUsersWeek'=>"SS_Datetime",
        'ShowdownVotes' => 'Int',
        'Finalist' => 'Boolean',
        'FinalistWeek'=>'SS_DateTime',
        'twitter_handle' => 'HtmlText',
        'solved' => 'htmlText',
        'BLMomentsSortOrder' => 'Int',
        'WinnerAll' => 'Boolean'
    );

    public static $has_one = array(
        'Image' => 'Image',
        'BLMDetailPage' => 'BLMDetailPage',
        'BLMSolve' => 'BLMSolve',
        'BLMTip' => 'BLMTip',
        'BLMLandingPage' => 'BLMLandingPage',
        'BLMManagementPage'=>'BLMManagementPage',
        'Author' => 'Member'
     );
    public static $has_many = array('BLMVotes' => 'BLMVote');
    public static $many_many = array('BLMPromos' => 'BLMPromo');
    public static $belongs_many_many = array();

    // Fields used for the display of info in the CSV and The grids
    static $summary_fields = array(
        'ID',
        'approval',
        'Name',
        'created_timestamp'
    );
	 /**
     * Will effectively add an index on the Email column in the database
     * This will improve lookups and sorting on Email
     */
    public static $indexes = array(
        // Just smack a btree index on Email
        'selectedForWeeklyShowdown' => true,
        // Combined index for selectedForWeeklyShowdown and ID
        'selectedForWeeklyShowdown_ID' => '(selectedForWeeklyShowdown,ID)',
        // Just smack a btree index on Email
        'selectedForWeeklyShowdownUsers' => true,
        // Combined index for selectedForWeeklyShowdown and ID
        'selectedForWeeklyShowdownUsers_ID' => '(selectedForWeeklyShowdownUsers,ID)',
        // Just smack a btree index on Email
        'Finalist' => true,
        // Combined index for selectedForWeeklyShowdown and ID
        'Finalist_ID' => '(Finalist,ID)',
        
    );
    // Searchable fields
    static $searchable_fields = array();

    // Sort Order for the grid
    public static $many_many_extraFields = array('BLMPromos' => array('SortOrderBLMPromos' => 'Int'));

    public function BLMPromos() {
        return $this -> getManyManyComponents('BLMPromos') -> sort('SortOrderBLMPromos');
    }
	public function ShowdownWinDate(){
		return $this->daily_winner;
	}
    //the cms fields
    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new HeaderField('NameHeader', 'Name this moment'));

        $fields -> addFieldToTab('Root.Main', new TextField('Name', 'Name'));

        //*****************  PublicationDate DATES
        $fields -> addFieldToTab('Root.Main', new HeaderField('PublicationHeader', 'Publication Date'));

        $dateField = new DateField('PublicationDate');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        //******************  VOTE COUNTS
        $fields -> addFieldToTab('Root.Main', new HeaderField('VotesHeader', 'User popularity votes'));
        $fields -> addFieldToTab('Root.Main', new ReadonlyField('VotesCount', 'VotesCount'));

        //******************* STATUS of the Moment

        $fields -> addFieldToTab('Root.Main', new HeaderField('StatusHeader', 'Status of the Moment (Admin only)'));
        $status = new OptionsetField($name = "Status", $title = "Status", $source = array(
            "1" => "New",
            "2" => "Required Review",
            "3" => "Review in Process",
            "4" => "Accepted",
            "5" => "Rejected",
            "6" => "Finalist",
            "5" => "Winner",
            "5" => "Featured"
        ), $value = "1");

        $fields -> addFieldToTab('Root.Main', $status);

        $fields -> addFieldToTab('Root.Main', new HeaderField('SpecificsHeader', 'Author/Moment Number'));
        //******************* AUTHOR of the Moment
        $fields -> addFieldToTab('Root.Main', new TextField('Author', 'Author'));

        //******************* NUMBER
        $fields -> addFieldToTab('Root.Main', new TextField('MomentNumber', 'MomentNumber'));

        //******************* CONTENT of the Moment
        $fields -> addFieldToTab('Root.Main', new HeaderField('ContentHeader', 'The content of the moment'));
        $fields -> addFieldToTab('Root.Main', new HtmlEditorField('Content', 'Content'));

        //***************** Promos
        $fields -> addFieldToTab('Root.Main', new HeaderField('Promos', 'Promos Associated with this moment'));

        $BLMPromosField = new GridField('BLMPromos', 'BLMPromos', $this -> BLMPromos(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderBLMPromos'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $BLMPromosField);

        //******************* IMAGE for the Moment
        $fields -> addFieldToTab('Root.Main', new HeaderField('ImageHeader', 'Image (optional)'));
        $fields -> addFieldToTab('Root.Main', new UploadField('Image', 'Image'));

        return $fields;
    }


    /**
     * Function Name
     * Will generate a name using the selection text
     *
     * @param $length Integer default 6 : will restrict the quantity of words to use for the name
     * @author Luc Martin -at- Clorox
     * @version $ID
     */
    function Name($length = 6, $urlEncoded = false){
        $ret = '';
        $submissionAr = explode(' ', $this->submission);

        for($n=0; $n < $length; $n ++ ) {

            if(!empty($submissionAr[$n])){

                $ret .= $submissionAr[$n].' ';

            }

        }
        if($urlEncoded == 'true'){
            //error_log(urlencode($ret));
            return urlencode($ret);
        }
        return $ret;
    }

    public function updateMoments() {
        	return;
        set_time_limit(900);
        $today = Date('U', strtotime('today'));
        $firstMoment = BLMoment::get() -> first();
        if (!empty($firstMoment)) {
            $updateDate = $firstMoment -> LastUpdated;
        }
        else {
            $updateDate = 0;
        }
        if ($updateDate !== $today) {

            $result = DB::query('SELECT * FROM "bm_submission" ORDER BY `id` ASC');
            // Iterate over results

            foreach ($result as $row) {
                $moment = BLMoment::get() -> byID($row['id']);
                if (empty($moment)) {

                    $moment = new BLMoment();
                    $moment -> ID = $row['id'];

                }
                if ($moment -> modified_timestamp !== $row['modified_timestamp']) {
                    $moment -> modified_timestamp = $row['modified_timestamp'];
                    $moment -> consumer_id = $row['consumer_id'];
                    $moment -> created_timestamp = $row['created_timestamp'];
                    $moment -> traffic_source = $row['traffic_source'];
                    $moment -> twitter_handle = $row['twitter_handle'];
                    $moment -> LastUpdated = Date('U', strtotime('today'));
                    $moment -> approval = $row['approval'];
                    $moment -> popularity = $row['popularity'];
                    $moment -> submission = $row['submission'];
                    $moment -> solved = $row['solved'];
                    $moment -> daily_winner = $row['daily_winner'];
                    $moment -> favorite = $row['favorite'];
                    $moment -> Name = $row['submission'];
                    $moment -> write();
                }

            }

        }
        $firstMoment -> LastUpdated = $today;
        $firstMoment -> write();
    }

    /**
     * Author function
     * Purpose: set the Author using the Consumer ID from the Clorox db
     *
     * @param
     * @param
     * @author Luc Martin -at- clorox.com
     * @version $ID
     */
    public function Author(){

        if (empty($this->author)){
            $this->Author = Member :: get() ->filter(array('ID' => $this->consumer_id))->first();
            $this->write();
        }
        return $this->Author;
    }

    /**
     * Consumer function
     * Purpose: returns a consumer information from the Clorox DB using the ID stored in a BLMoment
     *
     * @param $item : what part of the consumer info is requested ex: firstName OR json to return the whole object as a json object
     * @param  to truncate the return (not if json) for instance ($item = firstName, 2) will result in Lu instead of Luc
     * @author Luc Martin -at- clorox.com
     * @version $ID
     */
    public function Consumer($item = '', $truncateResultTo = null){
        if(empty($this->consumer_id)){
            return;
        }
        $ret = array();

        $consumer = new CCL_PC_Model_Consumer ();
        $consumer->load($this->consumer_id);

        $ret['id'] = $this->consumer_id;
        $ret['firstName'] = $consumer -> getFirstName();
        $ret['lastName'] = $consumer -> getLastName();
        $ret['addressLine1'] = $consumer -> getAddressLine1();
        $ret['addressLine2'] = $consumer -> getAddressLine2();
        $ret['city'] = $consumer ->getCity();
        $ret['state'] = $consumer -> getState();
        $ret['postalCode'] = $consumer -> getPostalCode();
        $ret['country'] = $consumer->getCountry();
        $ret['phoneMain'] = $consumer->getPhoneMain();
        $ret['phoneMobile'] = $consumer->getPhoneMobile();
        $ret['optIn'] = $consumer->getBrandOptIn();
        $ret['phone'] = $consumer->getPhone();
        $ret['email'] = $consumer->getEmailAddress();
        $ret['momentsSubmitted'] = $this->countMomentsForUser();
        $ret['lastMomentSubmittedName'] = $this->lastMomentPublishedByUser('Name');
        $ret['lastMomentSubmittedDate'] = $this->lastMomentPublishedByUser('created_timestamp');
        $ret['registered'] = $consumer->getCreatedTimestamp();
        $ret['dob'] = $consumer->getDob();

        if($item !== 'json'){
            $ret = (!empty($truncateResultTo)) ? substr($ret[$item], 0, $truncateResultTo ) : $ret[$item];
            return $ret;
        }else{
            return json_encode($ret);
        }

    }

    /**
     * countMomentsForUser function
     * Counts how many moments this Consumer has pushed
     * @author Luc Martin -at- Clorox
     * @version $ID
     */
    public  function countMomentsForUser(){
        $user = Member::currentUser();
        if(empty($this->consumer_id)){
            return;
        }
    	//error_log('::::::::::::: count moments for user ::::::::'.$this->consumer_id);
        $count = DB::query("SELECT COUNT(`id`) FROM `BLMoment` WHERE `consumer_id` = ". $this->consumer_id)->value();
        return $count;
    }

    /**
     * lastMomentPublishedByUser function
     * Returns a part of the last moment pushed by this user
     *
     * @param $item can be any part of a BLMoment example: Name of Submission
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     */
    public  function lastMomentPublishedByUser($item){
        $moments = BLMoment::get()->filter(array('consumer_id' => $this->consumer_id))->sort("ID DESC")->limit(1);
        $ret = $moments->first()->$item;

        return $ret;
    }

    /**
     * NameJson function
     * Returns jsona encoded version of the name of that object
     *
     * @param
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     */
    public function NameJson(){
        return json_encode($this->Name);
    }

    /**
     * NameJson function
     * Returns json encoded version of the name of that object
     *
     * @param
     * @author ? -at- Clorox.com
     * @version $ID
     */
    public function submissionJson(){
        return json_encode($this->submission);
    }

    /**
     * solvedJson function
     * Returns json encoded version of the solve of that object
     *
     * @param
     * @author ? -at- Clorox.com
     * @version $ID
     */
    public function solvedJson(){
        return json_encode($this->solved);
    }

    /**
     * formattedDate function
     * Returns formatted date
     *
     * @param $dateItem // what is the date required
     * @param $format php Date format as string
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     */
    public function formattedDate($dateItem, $format){
        return Date($format, strtotime($this->$dateItem));
    }

    /**
     * momentIsVotable function
     * Returns true if the moment is eligible for vote
     *
     * @param
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     */
    public function momentIsVotable($type){
        //TODO get rules for the moment


		
        $votingController = new Voting_Controller();
        $ret = $votingController -> allowVotingForItem('Bleachable Moments', $type, $this->ID);
        return $ret;
    }

    /**
     * getNextMoment function
     * Returns the ID of the Next Moment
     *
     * @param
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     */
    public function getNextMoment(){
        $ret = BLMoment::get()->where("`ID` > ".$this->ID)->sort('ID','ASC')->limit(1)->first();
        $ret = (empty($ret->ID)) ? false : $ret->ID;
        return  $ret ;
    }

    /**
     * getPrevMoment function
     * Returns the ID of the Precedent Moment
     *
     * @param
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     */
    public function getPrevMoment(){
        $ret = BLMoment::get()->where("`ID` < ".$this->ID)->sort('ID','DESC')->limit(1)->first();
        $ret = (empty($ret->ID)) ? false : $ret->ID;
        return $ret;
    }

    /**
     * solveExists function
     * Purpose: Tests if there is a solve in the moment
     *
     * @author Luc Martin -at- Clorox.com
     * @version $ID
     */
     function solveExists(){
         $ret = ($this->solved == '' || empty($this->solved)) ? false : true;
         return $ret;
     }

     public function momentUserIsRegistered(){

         $controller  = new BLMMasterPage_Controller();
         $ret =  $controller->userIsRegistered();
		 //error_log('Is user registered? '.$ret);
		 return $ret;
     }
     public function momentUserCanVote($type){
         $controller  = new BLMMasterPage_Controller();
         $ret =  $controller->userCanVote($type);
		 //error_log('momentUserCanVote '.$ret);
		 return $ret;
     }
     public function submission(){
         $ret = str_replace('\\n', '<br/>', $this -> submission);
         $ret = str_replace('\\', '', $ret);
         return $ret;
     }
     public function solved(){
         $ret = str_replace('\\n', '<br/>', $this -> solved);
         $ret = str_replace('\\', '', $ret);
         return $ret;
     }
     public function solvedTitle($qty = 4, $addLink = false){
         $solve = $this -> solved();
         $solveAr = explode(' ', $solve);
         $ret = '';
         $iterator = 0;
         foreach ($solveAr as $key => $word) {
             if($iterator < $qty){
                 $ret .= $word.' ';
             }
             ++$iterator;
         }
         if(!empty($addLink)){
             return '<a href="/laugh/bleach-it-away/vote-for-moments/moment/idnumber/'.$this->ID.'">#'.$this->ID.' '.$ret.'...</a>';
         }
         return '#'.$this->ID.' '.$ret.'...';
     }

}
