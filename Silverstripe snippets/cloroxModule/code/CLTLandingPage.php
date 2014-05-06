<?php
/*
 * Class CLTLandingPage
 *
 * Describes the Model for a CLTLandingPage the home page for the Cleaning and Laundry tips
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class CLTLandingPage extends Page {
    static $db = array(
        
        'Subtitle' => 'HTMLText',
        'Color_Class' => 'Text'
    );

    public static $allowed_children = array('CLTUseForPage', 'CLTArticlesPage', 'CLTSearchPage', 'CLTSnapGuidePage');

    static $has_one = array(
        'CLTTagGroup' => 'CLTTagGroup',
        'CLTAppPanel' => 'CLTAppPanel',
        'DrLaundryBlogHolder' => 'DrLaundryBlogHolder'
    );


    static $many_many = array(
        'CLTPanels' => 'CLTPanel',
        'PopularTopics' => 'CLTPanel',
        'MomMomentsBlogEntry'=>'MomMomentsBlogEntry',
        'DrLaundryBlogEntry' =>'DrLaundryBlogEntry'
    );

    public static $many_many_extraFields = array(
        'CLTPanels' => array('SortOrderLandingPageCLTPanels' => 'Int'),
        'PopularTopics' => array('SortOrderPopularTopics' => 'Int'),
        'MomMomentsBlogEntry'=>array('SortOrderMomMoments'=>'Int')
    );

    public function getCMSFields() {

        $cssClasses_Controller = new CssClasses_Controller('Color_Class');

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main',$cssClasses_Controller -> CLTPanel_Colors_Class());

        $fields -> removeFieldFromTab('Root', 'Content');

        //***************** Description
        $fields -> removeFieldFromTab('Root.Main', 'Description');

        //***************** Feature Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('Articles_and_Tips', 'Articles and Tips:'));
		
        $CLTPanelsField = new GridField('CLTPanels', 'CLTPanels', $this -> CLTPanels(),
        GridFieldConfig_Base::create() -> addComponents(
            new GridFieldSortableRows('SortOrderLandingPageCLTPanels'), new GridFieldToolbarHeader(),
            new GridFieldDeleteAction('unlinkrelation'),
            new GridFieldFilterHeader(),
            new GridFieldPaginator(115),
            new GridFieldEditButton(),
            new GridFieldDetailForm(),
            new GridFieldAddExistingAutocompleter()

        ));

        $fields -> addFieldToTab('Root.Main', $CLTPanelsField);

 

     
        //***************** Popular topics
        $fields -> addFieldToTab('Root.Main', new HeaderField('PolularTopics', 'Popular Topics:'));
        $CLTPanelsField = new GridField('PopularTopics', 'PopularTopics', $this -> PopularTopics(),

        GridFieldConfig_Base::create() -> addComponents(
            new GridFieldSortableRows('SortOrderPopularTopics'), new GridFieldToolbarHeader(),
            new GridFieldDeleteAction('unlinkrelation'),
            new GridFieldFilterHeader(),
            new GridFieldPaginator(115),
            new GridFieldEditButton(),
            new GridFieldDetailForm(),
            new GridFieldAddExistingAutocompleter()

        ));
        $fields -> addFieldToTab('Root.Main', $CLTPanelsField);

        //***************** Popular topics
        $fields -> addFieldToTab('Root.Main', new HeaderField('MomMoments', 'Mom Moments:'));
        $CLTPanelsField = new GridField('MomMomentsBlogEntry', 'MomMomentsBlogEntry', $this -> MomMomentsBlogEntry(),

        GridFieldConfig_Base::create() -> addComponents(
            new GridFieldSortableRows('SortOrderMomMoments'),
            new GridFieldToolbarHeader(),
            new GridFieldDeleteAction('unlinkrelation'),
            new GridFieldFilterHeader(),
            new GridFieldPaginator(115),
            new GridFieldEditButton(),
            new GridFieldDetailForm(),
            new GridFieldAddExistingAutocompleter()

        ));
        $fields -> addFieldToTab('Root.Main', $CLTPanelsField);
	

        $fields -> addFieldToTab('Root.Main', new HeaderField('RelatedObjects', 'Related to that page'));
        $fields -> addFieldToTab('Root.Main', new DropDownField('CLTAppPanel', 'App Marketing Panel  '));
		
		$field = new DropdownField('DrLaundryBlogHolderID', 'DrLaundryBlogHolder', DrLaundryBlogHolder::get()->map('ID', 'Title'));
        $field->setEmptyString('(Select one)');
        $fields->addFieldToTab('Root.Main', $field);
		
        return $fields;

    }

    public function CLTPanels() {
        return $this -> getManyManyComponents('CLTPanels') -> sort('SortOrderLandingPageCLTPanels');
    }
	/*
	 * function getCLTPanelsByMonth($month, $year)
	 * Description: This is to get an array of panels one the given month and year
	 * Output: PanelArray  
	 * Input: $month as INT , $year as INT 
	 * 
	 * author: Kody Smith @ Clorox
	 * date: 09-23-2013
	 */
	public function getCLTPanelsByMonth($month, $year){
		$archive = DataObject::get('CLTPanel')-> where('`Created` >= STR_TO_DATE(\''.$year ."-" .$month. "-01".  '\', \'%Y-%m-%d\') AND `Created` < STR_TO_DATE(\''.$year ."-" . ($month+1) . "-01".  '\', \'%Y-%m-%d\') '); 
		$archive = $archive->filter('Panel_Class','Article');
		return $archive;
	}
	/*
	 * function CLTPanelsArchiveMonth
	 * Description: This is to get a count of how many panels fit within one month creation date
	 * Output: Month(panel count)  example  Jaunary(10)
	 * Input: $month as INT , $year as INT 
	 * 
	 * author: Kody Smith @ Clorox
	 * date: 09-19-2013
	 */
	

	public function CLTPanelsArchiveMonth($month=01,$year=2013) {
		//$archive = DataObject::get('CLTPanel')->filter(array(
    	//'Created:GreaterThan' => $month.'-01-'.$year,
    	//'Created:GreaterThan' => date("d-m-y",strtotime("01-".$month.$year)),
    	//'Created:LessThan' => (12).'-01-'.$year
    	//'Created:LessThan' => '12-01-2013'
		//));
		$archive = DataObject::get('CLTPanel')-> where('`Created` >= STR_TO_DATE(\''.$year ."-" .$month. "-01".  '\', \'%Y-%m-%d\') AND `Created` < STR_TO_DATE(\''.$year ."-" . ($month+1) . "-01".  '\', \'%Y-%m-%d\') '); 
		//$archive = $archive->filter(array(
		//	'Created:LessThan' => date("d-m-y",strtotime("01-".$month++.$year))
		//));
		$archive = $archive->filter('Panel_Class','Article');
		$monthCount = date('F',strtotime("01-".$month."-".$year))."<span class='archiveCount'>(".$archive->count().")</span>";
        return $monthCount;
    }
	
	/*
	 * function CLTPanelsArchiveYear
	 * Description: This is to get a count of how many panels fit within one year creation date
	 * Output: Year(panel count)  example  2013(10)
	 * Input: $year as INT 
	 * 
	 * author: Kody Smith @ Clorox
	 * date: 09-19-2013
	 */
 	public function CLTPanelsArchiveYear($year=2013) {
		$archive = DataObject::get('CLTPanel')->filter(array(
	    	'Created:GreaterThan' 	=>	'01-01-'.$year,
	    	'Panel_Class' 			=>	'Article'
    	//'Created:GreaterThan' => '01-01-2013',
    	//'Created:LessThan' => (12).'-01-'.$year
    	//'Created:LessThan' => '12-01-2013'
		));
		$archive = $archive->filter(array(
			'Created:LessThan'		=>	'31-12-'.$year
		));
        
        $result = new ArrayData(array(
            'year' => $year,
            'count' => $archive->count(),
        ));
        return $result;
    }
	
	/*
	 * function CLTPanelsArchive
	 * Description: This is to get an array of all of the CLTPanels to display the archive
	 * Output: Archive Array of all CLTPanels
	 * Input: $month as INT, $year as INT 
	 * 
	 * author: Kody Smith @ Clorox
	 * date: 09-19-2013
	 */
	public function CLTPanelsArchive($year=2013){
		
		//get the beginning date of what objects exist
		$beginDate = DataObject::get('CLTPanel')->sort('Created','ASC')->first()->Created;
		//get the ending date of what objects exist
		$endDate = DataObject::get('CLTPanel')->sort('Created','DESC')->first()->Created;
	 	$date = date("d-m-Y",strtotime($beginDate));
		$end_date = date("d-m-Y",strtotime($endDate));
		
		//create data array to store the array data into for looping in the template
        $dateRangeArray = array();
  
		while (strtotime($date) <= strtotime("+1 month", strtotime($end_date))) {
			$year = date ("Y", strtotime($date));
			$month = date ("m", strtotime($date));
			$dateRangeArray[] =
                    new ArrayData(array(
                            'year' => $year,
                            'month' => $month,
                            'display'=>$this->CLTPanelsArchiveMonth($month,$year),
                            'articles'=>$this->getCLTPanelsByMonth($month,$year)
                    ));
			$date = date ("Y-m-d", strtotime("+1 month", strtotime($date)));
		}
		
		//make the data into an ArrayList so that you can loop through it in SS templates
		$dateRangeArray = new ArrayList($dateRangeArray);
		return $dateRangeArray;
	}
	

    public function PopularTopics() {
        return $this -> getManyManyComponents('PopularTopics') -> sort('SortOrderPopularTopics');
    }

    public function LatestMomMomentsBlogEntry($num=1) {
        $blogpost = DataObject::get_one("MomMomentsBlogHolder"); 

		
	return ($blogpost) ? DataObject::get("MomMomentsBlogEntry", "", "Date DESC", "", $num) : false;
    }

	function LatestDrLaundryBlogEntry($num=1) { 
	    $blogpost = DataObject::get_one("DrLaundryBlogHolder"); 
	return ($blogpost) ? DataObject::get("DrLaundryBlogEntry", "", "Date DESC", "", $num) : false;
	}
}

class CLTLandingPage_Controller extends Page_Controller {

    public function init() {
    	Requirements::javascript("js/pages/CLTPageNavigation.js");
		Requirements::javascript("js/pages/CLTLandingPage.js");
		
        parent::init();
    }

}
