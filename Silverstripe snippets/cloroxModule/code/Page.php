<?php
class Page extends SiteTree {

	public  $PageController;

	public static  $db = array(
		'Disclaimer' => 'HTMLText',
		'NoHeaderFooter' => 'Boolean',
		'RobotsNoIndex' => 'Boolean',
		'RobotsNoFollow' => 'Boolean',
		'enableCannocalLink' => 'Boolean'

	);

	public static  $has_one = array(
		'CannocalLink' => 'SiteTree'
	);
	public static  $many_many = array(
		'InterruptBars' => 'InterruptBar'
	);
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields -> addFieldToTab('Root.Main', new TextAreaField('Disclaimer', 'Disclaimer'));

		$fields -> addFieldToTab('Root.Robots', new CheckboxField('RobotsNoIndex','Prevent Indexing on this page: ', 1));
		$fields -> addFieldToTab('Root.Robots', new CheckboxField('RobotsNoFollow','Prevent Robots from following Links on this page: ', 1));
		//$fields -> addFieldToTab('Root.Robots', new TextAreaField('CannocalLink','Related Page (Cannocal page)'));
		$fields -> addFieldToTab('Root.Robots', new TreeDropdownField('CannocalLinkID', 'Link To This Page','SiteTree'));
		$fields -> addFieldToTab('Root.Robots', new CheckboxField('enableCannocalLink', 'Enable Link to This Page'));

		//***************** Feature Panels
		$fields -> addFieldToTab('Root.Main', new HeaderField('InterruptBars', 'InterruptBars:'));

		$interruptBarsField = new GridField('InterruptBars', 'InterruptBars', $this -> InterruptBars(),
		GridFieldConfig_Base::create() -> addComponents(
			new GridFieldDeleteAction('unlinkrelation'),
			new GridFieldEditButton(),
			new GridFieldAddExistingAutocompleter()));

		$fields -> addFieldToTab('Root.Main', $interruptBarsField);

		return $fields;
	}


	/****************************
	 * Translate php defines into
	 * vars available in the template
	 *********************************/
	function webroot() {
		if(WEBROOT){

		return WEBROOT;
		}else{
			return $_SERVER['SERVER_NAME'];
		}
	}
	/**
	 *	function environmentType()
	 *
	 * 	This is an environment check to test what environment that the current system is on.
	 * 	this is also programmed in page.ss to allow on the fly environment checking...
	 * 	add  http://clorox.com?env=1 to check what environment the site is using in the address bar
	 */
	function environmentType(){
		if(isset($_REQUEST['env'])){
			return SS_ENVIRONMENT_TYPE;
		}else{
			return null;
		}
	}
	/**
	 * function sectionName()
	 * description:  this returns the defined name of this major section of the website.
	 * 				the reason for this function is to have custom defined CSS files that
	 * 				will override the standard files... without touching the original styling
	 * @author: kody smith -at- clorox.com
	 */
	function sectionName(){
		return 'Root';
	}
	function globalNavMenu($category) {
		return Product::get()->filter(array('Add_To_Global_Nav'=>true, 'Default_In_Product_Page_'.$category=>true))->sort('SortOrderNav');
	}
	function facebookAppId() {
		return FACEBOOK_APP_ID;
	}

	function minPath() {
		return MIN_PATH;
	}

	function minDebug() {
		return MIN_DEBUG;
	}

	function tags($tagType) {
		if($tagType == 'all'){
			$retAr = array();
			$ret = DB::query('SELECT *
								FROM (
									SELECT `ID`,`Name`,`Description`,`ClassName`,`Created`,`LastEdited`,`Link_Title`,`Link_Url` FROM TagNeed
									UNION ALL
									SELECT `ID`,`Name`,`Description`,`ClassName`,`Created`,`LastEdited`,`Link_Title`,`Link_Url` FROM TagType
									UNION ALL
									SELECT `ID`,`Name`,`Description`,`ClassName`,`Created`,`LastEdited`,`Link_Title`,`Link_Url` FROM TagFeatures
								) as TAGS
								ORDER BY Name');

			foreach($ret as $val){
				$o = new TagType();
				$o->ID = $val['ID'];
				$o->Name = $val['Name'];
				$o->Description = $val['Description'];
				$o->Link_Title = $val['Link_Title'];
				$o->Link_Url = $val['Link_Url'];
				$o->ClassName = $val['ClassName'];
				$retAr[] = $o;
			}
			return new ArrayList($retAr);

		}
		$t = $tagType::get();
		return $t;
	}

	function gaCode() {
		return GOOGLE_ANALYTICS;
	}

	function tmsTopEmbed() {
		return TMS_TOP_EMBED;
	}
	function tmsTopFBEmbed() {
		return TMS_TOP_FACEBOOK_EMBED;
	}
	function bvURL(){
		return 	BV_API_URL;
	}
	function bvRatings(){
		return BV_RATINGS;
	}
	/****************************
	 * End PHP defines translation
	 *********************************/

	function dollarSign() {
		return "$";
	}

	/**
	 * PrepRegForm function.
	 * This allows us to set requirements for standard and custom registration
	 * forms.
	 */
	function PrepRegForm() {
		Requirements::javaScript(FRAMEWORK_DIR . '/javascript/DateField.js');
		// the validation for the Facebook form
		Requirements::customScript('
			jQuery(document).ready(function() {
				// checkAge expects jQueryUI datepicker
				// and data-max date value auto-set on element
				jQuery.validator.addMethod("checkAge",
					function(value, element) {
						// make sure selected date is less than max DOB
						var maxDOB = parseISO8601( $(element).attr("data-max") );
						var chosenDOB = value.split("/");
						chosenDOB = parseISO8601( chosenDOB[2]+"-"+chosenDOB[0]+"-"+chosenDOB[1] );
						return chosenDOB.getTime() <= maxDOB.getTime();
					}, "You must be 18 years of age to register."
				);
				jQuery("#Form_FBSignupForm").validate({
					errorContainer: "#errorBox",
					errorLabelContainer: "#errorBox ul",
					wrapper: "li",
					rules: {
						FirstName: {
							required: true,
							notPlaceholder: "First Name"
						},
						Surname: {
							required: true,
							notPlaceholder: "Last Name"
						},
						Email: {
							required: true,
							email: true
						},
						TermsOfUse: {
							required: true
						},
						Birthday: {
							checkAge: true
						}
					},
					messages: {
						FirstName: "Please enter your first name",
						Surname: "Please enter your last name",
						Email: "Please enter a valid email address",
						TermsOfUse: "Please agree to the terms of use"
					}
				});
			});
		');
		// the validation for standard signup form
		Requirements::customScript('
			jQuery(document).ready(function() {
				jQuery.validator.addMethod("notPlaceholder", function(value, element, param) { return this.optional(element) || value !== param;}, "Please provide a non-default value.");

				// checkAge expects jQueryUI datepicker
				// and data-max date value auto-set on element
				jQuery.validator.addMethod("checkAge",
					function(value, element) {
						// make sure selected date is less than max DOB
						var maxDOB = parseISO8601( $(element).attr("data-max") );
						var chosenDOB = value.split("/");
						chosenDOB = parseISO8601( chosenDOB[2]+"-"+chosenDOB[0]+"-"+chosenDOB[1] );
						return chosenDOB.getTime() <= maxDOB.getTime();
					}, "You must be 18 years of age to register."
				);

				jQuery("#Form_SignupForm").validate({
					errorContainer: "#errorBox",
					errorLabelContainer: "#errorBox ul",
					wrapper: "li",
					rules: {
						FirstName: {
							required: true,
							notPlaceholder: "First Name"
						},
						Surname: {
							required: true,
							notPlaceholder: "Last Name"
						},
						Gender: {
							required: true
						},
						Email: {
							required: true,
							email: true
						},
						ReEnterEmail: {
							required: true,
							email: true,
							equalTo: "#Form_SignupForm_Email"
						},
						Password: {
							required: true,
							rangelength: [6, 20]
						},
						regPasswordVerify: {
							required: true,
							equalTo: "#Form_SignupForm_Password"
						},
						TermsOfUse: {
							required: true
						},
						Postcode: {
							required: true
						},
						Birthday: {
							checkAge: true
						}
					},
					messages: {
						FirstName: "Please enter your first name",
						Surname: "Please enter your last name",
						Email: "Please enter a valid email address",
						Password: "Please enter a password (between 6 and 20 characters)",
						regPasswordVerify: "Passwords must match",
						TermsOfUse: "Please agree to the terms of use",
						Postcode: "Please enter a ZIP or postal code",
						ReEnterEmail: "Email addresses must match"
					}
				});
			});
		');

	}

	/**
	 * ShowRegForm function.
	 * This allows us to use the registration form in any template
	 *
	 * @return object RegistrationForm_Controller
	 */
	function ShowRegForm() {
		$this->PrepRegForm();
		return new RegistrationForm_Controller();
	}


	/**
	 * ShowLoginForm function.
	 * This allows us to use the login form in any template
	 *
	 * @return object LoginForm_Controller
	 */
	function ShowLoginForm() {
		return new LoginForm_Controller();
	}

	/**
	 * isLoggedIn function.
	 * This returns a true or false if the user is loggedin
	 *
	 * @return object boolean true/false
	 */
	function isLoggedIn() {
		if(Member::currentUserID()){
			return true;
		}else{
			return false;
		}
	}


	/**
	 * CloroxHasIdentity function.
	 * Checks if current user is signed in with a CWF identity.
	 *
	 * N.B. This code works if here, but not if in the RegistrationForm class
	 * descended from Page.
	 *
	 * @return true if identity available
	 */

	public function CloroxHasIdentity() {
		$auth = Auth::getInstance();
		$loggedin = $auth -> hasIdentity();
		return $loggedin;
	}

	/**
	 * Get the URI for the current page
	 * @return array An array containing each part of the URI
	 */
	public function parseURI() {
		return explode('/', strtolower(substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 1)));
	}

	/**
	 * Determine the body id based on the URI
	 * @return string The body id
	 */
	public function bodyId() {
		// Get the URI components
		$uri = $this -> parseURI();

		while (count($uri) > 0) {
			// The id should be the last segment of the URI
			$id = array_pop($uri);

			// The id should not be blank or be a number
			if (is_numeric($id) || $id == '') {
				continue;
			}
			else {
				break;
			}
		}

		// If the $id is not set, return 'home' as its the homepage. Otherwise return the id
		return empty($id) ? 'home' : $id;
	}

	/*
	 * Generate a string of classes for the body tag
	 * @return string A space separated class list
	 */
	public function bodyClass() {
		// Get the URI components
		$uri = $this -> parseURI();

		// An array to hold the list of classes as they are generated
		$bodyClass = array();

		// Loop through the $uri to construct the classes
		while (count($uri) > 0) {
			// The next class to add to the list
			$class = array_shift($uri);

			// The class should not be blank or be a number or have already been added
			if (is_numeric($class) || $class == '' || in_array($class, $bodyClass)) {
				continue;
			}

			// Safe to add to the list
			$bodyClass[] = $class;
		}

		// Combine the classes into one space separated string. If there are no segments, its the homepage
		return count($bodyClass) > 0 ? implode(" ", $bodyClass) : 'home';
	}

	public function checkPos($Pos, $max, $relation = 'smaller') {
		if ($relation == 'smaller') {
			if ($Pos < $max) {
				return true;
			}
			else {
				return false;
			}
		}
		elseif ($relation == 'equal') {
			if ($Pos == $max) {
				return true;
			}
			else {
				return false;
			}
		}
		elseif ($relation == 'greater') {
			if ($Pos > $max) {
				return true;
			}
			else {
				return false;
			}
		}

	}

	/**
	 * Name: allUsedPressTags
	 *
	 * Description: gather all used Press tags
	 * @ID
	 * @autor Luc Martin
	 **/

	public function allUsedPressTags() {

		// Get all the press items
		$pressItems = pressItem::get();

		// get ready to associate the class name with the Human readeable name
		$cssClasses_Controller = new CssClasses_Controller();
		// human readable associative array
		$allClasses = $cssClasses_Controller -> PressReleaseTitleClasses(null, false);

		// return variable as string
		$ret = '';

		// all tags
		$tagAr = array();

		// iterate throught all existing tags
		foreach ($pressItems as $key => $item) {

			// test for duplicates
			if (!in_array($item -> PressReleaseType_Class, $tagAr)) {

				// no duplicate push
				$tagAr[] = $item -> PressReleaseType_Class;
			}
		}

		// sort
		asort($tagAr);

		// write visual elements -- sorry MVC police!
		$n = 0;
		if (!empty($tagAr[0])) {
			foreach ($tagAr as $key => $class) {
				if (!empty($allClasses[$class])) {
					$ret .= '<div class="PressReleaseTypeTagWrapper">';
					$ret .= '<input id="PressReleaseTypeTag' . ++$n . '" type="checkbox" class="PressReleaseTypeTag" name="tag-PressReleaseTypeTag" value="' . $class . '" checked />';
					$ret .= '<label for="PressReleaseTypeTag' . $n . '">' . $allClasses[$class] . '</label>';
					$ret .= '</div>';
				}

			}
			// tadam
			return $ret;
		}
		return false;
	}

	/* Function isMobile()
	 * Definition: this calls the Mobile_Detect Module in Silver Stripe and returns if this is a mobile device or not based on what is defined in that module.
	 *
	 *
	 */
	function isMobile(){
		return Mobile_Detect_Controller::isMobile();
	}
	/**
	 * function BackURL()
	 *
	 */
	public function BackURL() {
		if(isset($_REQUEST['BackURL'])) {
			$Link = $_REQUEST['BackURL'];
		} else {
			$Link = Session::get('BackURL');
		}
		return urlencode($Link);
	}
	public function CurrentURI(){
		return $_SERVER['REQUEST_URI'];
	}
	function preprocess($item){

		$allNotes = NoteType::get();

		foreach ($allNotes as $key => $note) {
			//error_log('there is a note '.$note->Name);
			$terms = explode('|',$note->SearchTerms);
			foreach ($terms as $key => $term) {
				if(!empty($term)){
					$match = substr_count($item, $term);

					if($match > 0){
						$note->notifyEditor($note->Name, $this->Link(), $term, $match);
					}
					//error_log('Match '.$match);
					$search = $term;
					$admin = Permission::check('ADMIN');
					if($note->RemoveOccurencesOnVisual !== 1 && !$admin){
						//$item = str_replace($search, '', $item);
					}
				}
			}

		}

		return $item;
	}
	public function allPagesToCache(){
		 // Get each page type to define its sub-urls
		$urls = array(
			'/',
			'/products/',
			'/admin/pages/',
			'/admin/pages/getsubtree/',
			'/laugh/bleach-it-away/',
			'/clorox-sitemap/',
			);

		// memory intensive depending on number of pages
	   // $pages = SiteTree::get();
		// add any custom URLs which are not SiteTree instances
		//$urls[] = "sitemap.xml";

		return $urls;
	}

}

class Page_Controller extends ContentController {

	/**
	 * An array of actions that can be accessed via a request. Each array element should be an action name, and the
	 * permissions or conditions required to allow the user to access it.
	 *
	 *
	 * <code>
	 * array (
	 *   'action', // anyone can access this action
	 *   'action' => true, // same as above
	 *   'action' => 'ADMIN', // you must have ADMIN permissions to access this action
	 *   'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
	 * );
	 * </code>
	 *
	 * @var array
	 */
	public static  $allowed_actions = array();

	public function init() {
		parent::init();

	}

	public function index() {

		// error_log('page.php'. Director::is_ajax());

		// setting a variable if the page request is
		// intended to be an ajax call
		if (Director::is_ajax()) {

			$this -> isAjax = true;

			return $this -> processAjaxRequest();
		}
		else {

			return Array();
			// execution as usual in this case...
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

		if (!isset($_REQUEST)) {
			return json_encode(array(
				'response' => 'The request was empty',
				'error' => '001 Request Empty'
			));
		}

		foreach ($_REQUEST as $key => $value) {
			$_REQUEST[$key] = Convert::raw2sql($value);
		}

		$type = $_REQUEST['type'];
		switch($type) {
			case 'addUserClick' :
				return $this -> addUserClick();
				break;
			case 'lazyLoad' :
				return $this -> generateLazyLoadPage();
				break;
			case 'filteredPages' :
				return $this -> filteredPages();
				break;
			case 'notifyEditor' :
				return $this -> notifyEditor();
				break;
		}
	}


	/**
	 * function filteredPages
	 * Purpose: Generates a list of elements using a filter to filter objects on a page
	 *
	 * @author Luc Martin at Clorox
	 * @version $ID
	 *
	 */
	public  function filteredPages() {
		//error_log('insideFilteredPages');

		if (isset($_REQUEST['filterBy'])) {
			$filterBy = $_REQUEST['filterBy'];
		}
		if (isset($_REQUEST['filterValue'])) {
			$filterValue = $_REQUEST['filterValue'];
		}
		if (isset($_REQUEST['objectType'])) {
			$dataObject = $_REQUEST['objectType'];
			$renderWithTemplate = 'Ajax' . $dataObject;
		}
		else {
			return 'no object type';
		}
		if (isset($_REQUEST['sortOrder'])) {
			$sortOrder = $_REQUEST['sortOrder'];
		}
		else {
			$sortOrder = "ASC";
		}

		//error_log('filter by:'.$filterBy.' filter value: '.$filterValue);
		//$returnObjects = $dataObject::get()->sort($filterBy, $filterValue);
		switch($filterBy) {
			case "Date" :
				$returnObjects = $dataObject::get() -> sort($filterBy, $sortOrder);
				break;
			default :
				$returnObjects = $dataObject::get() -> filter($filterBy, $filterValue);
		}

		$ret = '';
		foreach ($returnObjects as $key => $object) {
			$ret .= $object -> renderWith($renderWithTemplate);
		}
		return $ret;
	}

	/**
	 * generateLazyLoadPage function will generate a page following
	 * jQuery lazyLoad request
	 *
	 * @author Luc Martin at Clorox
	 * @version $ID
	 *
	 */
	public function generateLazyLoadPage() {
		if(!empty($_REQUEST['restrAccess'])){
			// Only admin
			$access = false;
			foreach($_REQUEST['lev'] as $v){
			   $access = (Permission::check($v) == true || $access == true) ? true : false;
			}
			if ($access == false) {
				return json_encode(array('error' => 'You need to be admin to save that data'));
			}
		}
		//error_log('LAZY LOADER MESSAGE');
		// The object to retrieve
		$dataObject = $_REQUEST['lazyLoadObject'];

		// The sort order for the object to get
		$sortOrder = $_REQUEST['sortOrder'];
		$sortDirection = (isset($_REQUEST['sortDirection'])) ? $_REQUEST['sortDirection'] : 'ASC';
		// default Sort
		if (!isset($sortOrder)) {
			$sortOrder = 'ID';
		}

		// How many elements do we need to load?
		$qtyOfElementsToGet = $_REQUEST['lazyLoadItemCountRequest'];

		// how many objects have been loaded already
		$startLoopAt = $_REQUEST['lazyLoaditemCount'];

		// If the object is a part of the page AND other pages,
		// we need to specify the specific page
		if (isset($_REQUEST['targetPage']) && isset($_REQUEST['pageTitle'])) {

			$targetPage = $_REQUEST['targetPage'];
			$pageTitle = $_REQUEST['pageTitle'];
		}


		// the template to render the object with
		// for instance if your object is FooObject, the template will be
		// themes/yourTheme/templates/Layout/AjaxLazyFooObject.ss
		$renderWithTemplate = (!empty($_REQUEST['renderWithTemplate'])) ? $_REQUEST['renderWithTemplate'] : 'AjaxLazy' . $dataObject;

		// Test if we are looking to a specific page
		if (!empty($targetPage)) {
			// We are TODO so we search for the page and filter with the page title
			$page = $targetPage::get() -> first();

			$allObjects = $page -> $dataObject()-> limit($qtyOfElementsToGet, $startLoopAt) -> sort($sortOrder,$sortDirection);

		}
		else {
			// We are requiring all object of the class
			if(!empty($_REQUEST['filter']) || !empty($_REQUEST['filterBy'])){

				$filter = $_REQUEST['filterBy'] || $_REQUEST['filter'];

				$allObjects = ($filter == 1) ? $dataObject::get()-> limit($qtyOfElementsToGet, $startLoopAt) -> sort($sortOrder,$sortDirection) : $dataObject::get()-> filter($filter)->limit($qtyOfElementsToGet, $startLoopAt) -> sort($sortOrder,$sortDirection);

				 // specific for the showdown report in the BLmanagement page
				 switch($_REQUEST['filter']){
					case 'Showdown':
						$filter = '`selectedForWeeklyShowdown` = true AND  `selectedForWeeklyShowdownUsers` = true';
						$allObjects = BLMoment::get()->where($filter)->sort('ID','DESC');
						PC::debug('FILTERING USING SHOWDOWN');
					break;
				}

			}elseif(!empty($_REQUEST['excludeBy'])){

				$allObjects = $dataObject::get()-> exclude($filter)->limit($qtyOfElementsToGet, $startLoopAt) -> sort($sortOrder,$sortDirection);
			}
			else{
				$allObjects = $dataObject::get()-> limit($qtyOfElementsToGet, $startLoopAt) -> sort($sortOrder,$sortDirection) ;
			}

		}

		// Return value init
		$ret = '';

		// Iterator is the quantity of loops we are operating in this call

		$iterator = $startLoopAt;
		$theEnd = true;
		// Iterate through all objects
		foreach ($allObjects as $key => $object) {
			$theEnd = false;
			$object->Exclude_From_Menu = (!empty($object->Exclude_From_Menu)) ? $object->Exclude_From_Menu : 0;

		   // error_log('$iterator '.$iterator.' '.$object->ID.' '.$object->Exclude_From_Menu);

			// making sure that we are grabbing the objects starting from the initial load up to the limit
			if ($object -> Exclude_From_Menu != true) {
				// error_log('adding element '.$iterator);
				$object -> Iterator = $iterator;
				//error_log('running through objects');
				$ret .= $object -> renderWith($renderWithTemplate);

			}
			if ($object -> Exclude_From_Menu != 1) {
				//error_log('iterator goes up '.$object->Exclude_From_Menu);
				++$iterator;
			}

		}
		$ret .= '<script>lazyLoaderGlobalObject.actualItemLoaded = '.$iterator.'; console.log(lazyLoaderGlobalObject);</script>';
		// error_log($ret);
		// No element is necessary
		if ($theEnd == true) {
			return 'end';
		}
		return $ret;
	}

	/**
	 * function addUserClick()
	 * This method is used to add a "count" to a user click button
	 * Page gets a ajax request
	 *
	 * @author Luc Martin at Clorox
	 * @version $ID
	 *
	 */
	public  function addUserClick() {

		$destination = $_REQUEST['destination'];

		switch($destination) {
			case 'homeTopSlide' :
				$id = $_REQUEST['id'];
				$homeTopSlide = HomeTopSlide::get() -> filter(array('ID' => $id)) -> first();

				if (!empty($homeTopSlide)) {

					if (empty($homeTopSlide -> User_Click_Counter)) {
						$homeTopSlide -> User_Click_Counter = 0;
					}

					$homeTopSlide -> User_Click_Counter = $homeTopSlide -> User_Click_Counter + 1;
					$homeTopSlide -> write();
					return json_encode(array(
						'response' => 'User Click Added to ' . $homeTopSlide -> Title,
						'count' => $homeTopSlide -> User_Click_Counter
					));
				}
				return json_encode(array('response' => 'No slide with that ID ' . $id));
				break;
			case 'ickItem' :
				$id = $_REQUEST['id'];
				$IcktionaryItem = IcktionaryItem::get() -> filter(array('ID' => $id)) -> first();

				if (!empty($IcktionaryItem)) {

					if (empty($IcktionaryItem -> User_Click_Counter)) {
						$IcktionaryItem -> User_Click_Counter = 0;
					}

					$IcktionaryItem -> User_Click_Counter = $IcktionaryItem -> User_Click_Counter + 1;
					$IcktionaryItem -> write();
					return json_encode(array(
						'response' => 'User Click Added to ' . $IcktionaryItem -> Title,
						'count' => $IcktionaryItem -> User_Click_Counter
					));
				}
				return json_encode(array('response' => 'No Ick with that ID ' . $id));
				break;
			case 'Good_Tip_Count' :
			case 'Ick_Count' :
			case 'Quick_Tip_Count' :
			case 'Just_For_Mom_Count' :
			case 'Fun_Count' :
				$id = $_REQUEST['id'];
				$cltPanel = CLTPanel::get() -> filter(array('ID' => $id)) -> first();

				if (!empty($cltPanel)) {

					if (empty($cltPanel -> $destination)) {
						$cltPanel -> $destination = 0;
					}

					$cltPanel -> $destination = $cltPanel -> $destination + 1;
					$cltPanel -> write();

					return json_encode(array(
						'response' => 'User Click Added to ' . $cltPanel -> Name,
						'count' => $cltPanel -> $destination
					));
				}
				return json_encode(array('response' => 'No slide with that ID ' . $id));
				break;
		}
	}

	public  function doClick() {

	}

	/**
	 * public function ickTags
	 * Method to get all ick tags for a category
	 *
	 * @author Luc Martin at Clorox
	 * @version $ID
	 *
	 */
	public function ickTags($tag) {
		// setup array
		$allTagsAr = array();

		// gat all icks
		$icks = IcktionaryItem::get();

		// iterate
		foreach ($icks as $key => $ick) {

			// get specific tag
			$tags = $ick -> TagGeneral() -> filter(array('Tag_Type' => $tag));

			// iterate and build the return values
			foreach ($tags as $key => $tagItem) {
				// title
				$tagTitle = $tagItem -> Title;

				$allTagsAr[$tagTitle]['title'] = $tagItem -> Title;
				// title
				$allTagsAr[$tagTitle]['data'] = $tagItem -> ID;
				//ID
				$allTagsAr[$tagTitle]['link'] = '#' . str_replace(' ', '', $tagItem -> Title);
				// hash tag
				$allTagsAr[$tagTitle]['type'] = 'TagGeneral';
				// tag type
			}

		}
		// sort the array
		asort($allTagsAr);

		// return string
		$ret = '';

		// build the view (Sorry MVC Police!)
		foreach ($allTagsAr as $key => $value) {
			// list item tag
			$ret .= '<span class="' . $tag . ' ickResults"><a data-id="' . $value['data'] . '" data-type="' . $value['type'] . '"  href="' . $value['link'] . '">' . $value['title'] . '</a></span>';
		}
		// bye
		return $ret;
	}

	/**
	 * public function ickContributors
	 * Method to get all ick tags for a category
	 * @author Luc Martin at Clorox
	 * @version $ID
	 */
	public function ickContributors() {
		// setup array
		$contributorsAr = array();

		// get the authors
		$authors = IckAuthor::get();
		// get the illustrators
		$illustrators = IckIllustrator::get();

		// iterate through the authors
		foreach ($authors as $key => $author) {
			if (!$author -> Exclude_From_Menu) {
				$authorTitle = $author -> Name . ' (Author)';
				// name
				$authorId = str_replace(' ', '', $author -> Name) . 'author';
				// array headers
				$contributorsAr[$authorId]['title'] = $authorTitle;
				// title
				$contributorsAr[$authorId]['link'] = '#' . str_replace(' ', '', $author -> Name);
				// link
				$contributorsAr[$authorId]['data'] = $author -> ID;
				// ID
				$contributorsAr[$authorId]['type'] = 'IckAuthor';
				// type
			}
		}
		foreach ($illustrators as $key => $illustrator) {
			if (!$illustrator -> Exclude_From_Menu) {
				$illustratorId = str_replace(' ', '', $illustrator -> Name) . 'illustrator';
				// name
				$illustratorTitle = $illustrator -> Name . ' (Illustrator)';
				// array headers
				$contributorsAr[$illustratorId]['title'] = $illustratorTitle;
				// title
				$contributorsAr[$illustratorId]['link'] = '#' . str_replace(' ', '', $illustrator -> Name);
				// link
				$contributorsAr[$illustratorId]['data'] = $illustrator -> ID;
				// ID
				$contributorsAr[$illustratorId]['type'] = 'IckIllustrator';
				// type
			}

		}
		// sort the array
		asort($contributorsAr);

		// init return
		$ret = '';

		// build the view (Sorry MVC Police!)
		foreach ($contributorsAr as $key => $value) {
			// build th list item
			$ret .= '<span class="IckContributors ickResults"><a data-id="' . $value['data'] . '"  data-type="' . $value['type'] . '" href="' . $value['link'] . '">' . $value['title'] . '</a></span>';
		}

		// bye
		return $ret;
	}

	public function getIckLetter() {

		$itsTheFirstLetter = 1;
		$initDone = false;
		$oldLetter = '';
		$icks = IckSinglePage::get() -> sort('Title ASC');

		$ret = '';

		foreach ($icks as $key => $ickPage) {
			$ickItem = $ickPage -> IcktionaryItem() -> first();
			if (!empty($ickItem)) {
				$exclude = $ickItem -> Exclude_From_Menu;

				if (!$exclude) {
					$title = $ickPage -> Title;
					$firstLetter = strtolower(substr($title, 0, 1));
					$ickPageLink = $ickPage -> Link();

					$ret .= '<span class="results' . strtoupper($firstLetter) . ' ickResults"><a href="' . $ickPageLink . '">' . $title . '</a></span>';

					$oldLetter = $firstLetter;
				}
			}
		}

		$ret .= '</span>';

		return $ret;
	}

	/**
	 * CountActiveIcks function
	 * Returns the quantity of icks available for the menu
	 */
	public function countActiveIcks() {
		$allIcks = IcktionaryItem::get() -> exclude('Exclude_From_Menu', 1);
		$count = count($allIcks);
		return $count;
	}

	/**
	 * FilteredIckItems function
	 * Will return Icks ordered
	 * If there is a request for tags it will filter Icks using the request
	 *
	 * @author Luc Martin
	 * @ID
	 */
	public function FilteredIckItems($sort = 'SortOrder', $direction = 'ASC') {
		$allowedTypes = array(
			'IckIllustrator',
			'IckAuthor',
			'TagGeneral'
		);
		foreach ($_REQUEST as $key => $value) {
			$_REQUEST[$key] = Convert::raw2sql($value);
		}

		if (isset($_REQUEST['sort'])) {
			$sort = $_REQUEST['sort'];
		}
		if (isset($_REQUEST['direction'])) {
			$direction = $_REQUEST['direction'];
		}
		else {
			$direction = 'ASC';
		}

		if (!isset($_REQUEST['tagType']) || empty($_REQUEST['tagType']) || !in_array($_REQUEST['tagType'], $allowedTypes)) {
			return $this -> IckItems() -> sort(array($sort => $direction));
		}

		$tagType = $_REQUEST['tagType'];
		$id = $_REQUEST['id'];

		$Icks = IcktionaryItem::get() -> innerJoin("IcktionaryItem_" . $tagType, '"IcktionaryItem_' . $tagType . '"."IcktionaryItemID" = "IcktionaryItem"."ID"') -> where("IcktionaryItem_" . $tagType . "." . $tagType . "ID = " . $id) -> exclude('Exclude_From_Menu', 1) -> sort(array($sort => $direction));

		$ret = array();
		foreach ($Icks as $key => $ick) {

			if (!$ick -> Exclude_From_Menu) {
				$ret[] = $ick;
			}
		}
		return $Icks;

	}

	/**
	 * function generateCacheKey
	 *
	 * description: generate cache key to refresh cache on demand
	 * @author Luc Martin
	 * @ID
	 */
	public function generateCacheKey() {
		$key = '';
		foreach ($_REQUEST as $key => $value) {
			$_REQUEST[$key] = Convert::raw2sql($value);
			$key .= $_REQUEST[$key];
		}
		if (!isset($_REQUEST['id'])) {
			$_REQUEST['id'] = Date('U', strtotime('now'));
		}
		$key = $_REQUEST['id'] . Date('U', strtotime('today'));
		return $key;

	}

	public function getUrl() {
		return $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
	public function getAllProducts(){
		$productArray = Product::get();
		//$productArray = new ArrayList($productArray);
		return $productArray;
	}
	public function timestamp(){
		return Date('y/d/m/h/i/s');
	}
	/**
	 * ProductsSelected function
	 * Purpose: generates a list of products filtered and ordered
	 * using the GET request
	 * @author Luc Martin at Clorox
	 * @version $ID
	 */
	public function ProductsSelected($category = null) {
		$now = Date('U', strtotime('now'));
		// loads the string manipulator
		$stringController = new StringManipulator_Controller();

		// init results
		$resultArray = array();

		// make sure that we are generating a sort order that exists
		$acceptibleSortOrder = array(
			'sortordercleaningdisinfecting' => 'SortOrderCleaningDisinfecting',
			'sortorderlaundry' => 'SortOrderLaundry',
			'sortorderbathroom' => 'SortOrderBathroom'
		);

		// Clean up the request
		foreach ($_REQUEST as $key => $value) {
			$_REQUEST[$key] = Convert::raw2sql($value);
		}
		if(!empty($category)){
			$_REQUEST["show"] = $category;
		}
		// Check if we are looking to a specific product list
		if (isset($_REQUEST["show"])) {
			// the filter
			$show = $_REQUEST["show"];

			// flatten the request into a codename
			$showCodeName = $stringController -> generateCodeName($show);
			// query the array for a legal sort order
			$sortOrderIndex = 'sortorder' . strtolower($showCodeName);
			$sortOrder = $acceptibleSortOrder[$sortOrderIndex];

			// default if it's not legal
			if (!in_array($sortOrder, $acceptibleSortOrder) || empty($acceptibleSortOrder[strtolower($sortOrder)])) {
				$sortOrder = 'SortOrderProducts';
			}
			// the actual query
			$products = Product::get() -> filter(array('Hide_In_Product_Page' => false)) -> sort(array($sortOrder => 'ASC'));

			// generates the return
			foreach ($products as $product) {
				if(!empty ($product->Publication_Date)){
					$publicationDate =  Date('U', strtotime($product->Publication_Date));

					if(class_exists('PC')) PC::debug('NOW IS: '.$now);
					if(class_exists('PC')) PC::debug('PUBLICCATION  IS: '.$publicationDate);

					if($publicationDate >= $now){
						continue;
					}
				}

				$usesFor = $product -> UseFor();
				foreach ($usesFor as $useFor) {
					$codeName = $stringController -> generateCodeName($useFor -> Display_Name);
					if ($codeName == 'cleaningsanitizingdisinfecting') {
						$codeName = 'cleaningdisinfecting';
					}

					$subject = $codeName;
					$tmp = str_replace('-', '|', $show);
					$pattern = '/(' . $tmp . ')/i';
					$matchResult = preg_match($pattern, $subject, $matches);
					//what product is added to output
					if ($matchResult === 1) {
						$resultArray[] = $product;
					}
					if ($showCodeName == 'bathroom') {
						$useinRooms = $useFor -> goodForBathrooms();

						if (!empty($useinRooms) && $useinRooms == 1) {
							$resultArray[] = $product;
						}

					}
				}
			}

		}


		if (count($resultArray) == 0) {
			$products = Product::get() -> filter(array('Hide_In_Product_Page' => false)) -> sort(array('SortOrderProducts' => 'ASC'));

			foreach ($products as $product) {
				if(!empty ($product->Publication_Date)){
					$publicationDate =  Date('U', strtotime($product->Publication_Date));

					if(class_exists('PC')) PC::debug('NOW IS: '.$now);
					if(class_exists('PC')) PC::debug('PUBLICATION  IS: '.$publicationDate);

					if($publicationDate >= $now){
						if(class_exists('PC')) PC::debug('JUMPING  IS: '.$product->Name);

						continue;
					}
				}
				$resultArray[] = $product;
			}

		}
		$return = new ArrayList($resultArray);
		return $return;
	}


	public function noHeader($value = false){
		$noHeader = $value;
		return $noHeader;
	}

	/*
	 * function getEnvironment()
	 * Description:  This looks at what environment is currently set in the environment / defines
	 *
	 * Notes:  This function was created in addition to the modifications done to _ss_environment.php
	 * to output the current environment for debug purposes
	 *
	 */
	 function getEnvironment(){
		return CURRENT_ENVIRONMENT."-Mode:".SS_ENVIRONMENT_TYPE;
	 }
	 function showEnvironment(){
		if(isset($_REQUEST['env'])){
			return true;
		}else{
			return false;
		}

	 }
	public function ssRedirect($url="/"){
		$this->redirect($url);
	}

	/**
	 * @method featuredIckWord
	 * Purpose: to get the current featured ick word to display in Navigation, or other areas of the site.
	 *
	 * @author Kody
	 * @version $ID
	 */
	public function featuredIckWord(){
		$featuredIck = IcktionaryItem::get() -> exclude('Exclude_From_Menu',1) -> sort('SortOrder')->limit('1')->first();
		return $featuredIck->Display_Name;
	}

	/**
	 * @method interrupterBarRandom
	 * Purpose: To randomize interrupt bars get random interrupt bars that are assigned to the
	 * 			current page.
	 *
	 * @author Kody
	 * @version $ID
	 */
	public function interrupterBarRandom() {
		$interruptBar = $this->InterruptBars();

		//$objectArray = InterruptBar::get()->filter(array('ID'=>$interruptBar));
		$randomizer = new Randomizer;
		$randomizer -> setClassName('InterruptBar');
		$randomizer -> setDisplayCount(1);
		$randomizer -> selectRandom($interruptBar);
		return $randomizer -> result();
	}

	public function contactUsForm() {
		return CONTACT_US_FORM;
	}
}
