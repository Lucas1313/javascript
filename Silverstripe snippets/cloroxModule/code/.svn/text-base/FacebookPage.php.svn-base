<?php
/*
 * Class FacebookPage
 *
 * Allows integration with facebook display of pages without the clorox headers
 *
 * @author Kody Smith -at- clorox.com
 * @version $Id
 */
class FacebookPage extends Page {
    static $db = array(
      
    );

 
	static $has_one = array(
		
	);
    static $has_many = array(
    );

    static $many_many = array(
		'pageReference' => 'Page',

    );

    public static $many_many_extraFields = array(
    );

	public function getCMSFields() {
        $fields = parent::getCMSFields();
		$fields -> addFieldToTab('Root.Main', new CheckboxField('NoHeaderFooter','Remove header / footer: ', 1));
		$pageReferenceField = new GridField('pageReference', 'page', $this -> pageReference(),
        GridFieldConfig_Base::create() -> addComponents(
            new GridFieldDeleteAction('unlinkrelation'),
            new GridFieldFilterHeader(),
            new GridFieldPaginator(115),
            new GridFieldEditButton(),
            new GridFieldDetailForm(),
            new GridFieldAddExistingAutocompleter()

        ));

        $fields -> addFieldToTab('Root.Main', $pageReferenceField);
		$fields -> removeFieldFromTab('Root.Main', 'Content');
		
        return $fields;
    }
	public function pageReference() {
        return $this -> getManyManyComponents('pageReference');
    }
	public function getSpecialOffers(){
		return DataObject::get('SpecialOffersPage');	
	}
	public function getPageName(){
		return print_r($this -> getManyManyComponents('pageReference')->first()->ClassName,1);
	}
	public function getPageID(){
		return print_r($this -> getManyManyComponents('pageReference')->first()->ID,1);
	}
	public function getPageOffers(){
		//return DataObject::get_by_id($this->getPageName(), $this->getPageID())->getSpecialOffers();
		return DataObject::get_by_id($this->getPageName(), $this->getPageID())->getSpecialOffers();
		//$featuredEventPage= DataObject::get('EventPage', "`FeaturedHomePage` = 1", 'Created DESC', null, $number); 
		//return print_r($this -> getManyManyComponents('pageReference')->first()::get(),1);
	}
}

class FacebookPage_Controller extends Page_Controller {

    public function init() {
		
        parent::init();
    }

}
