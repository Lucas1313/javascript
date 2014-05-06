<?php
/*
 * Class CLTLocationPage
 *
 * Describes the Model for a CLTLocationPage the home page for the Cleaning and Laundry tips
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class CLTLocationPage extends Page {

    static $db = array(
        'Display_Name'=> 'HTMLText',
        'Subtitle' => 'HTMLText'
        );

    static $has_one = array('CLTAppPanel' => 'CLTAppPanel');

    static $can_be_root = false;

    static $many_many = array(
        'Tips' => 'CLTPanel',
        'Articles' => 'CLTPanel',
        'RelatedArticles' => 'CLTPanel',
    );

    public static $many_many_extraFields = array(
        'Tips' => array('SortOrderTips' => 'Int'),
        'Articles' => array('SortOrderArticles' => 'Int'),
        'RelatedArticles' => array('SortOrderRelatedArticles' => 'Int')
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');

        //***************** Description
        $fields -> removeFieldFromTab('Root.Main', 'Description');

		//***************** Subtitle 
		//************** Sub text or Call to Action Text for CLT Location panels
		$fields -> addFieldToTab('Root.Main', new TextField('Subtitle', 'Subtitle'));
		
        //***************** Articles Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('ArticlesPanel', 'Articles'));
        $ArticlesField = new GridField('Articles', 'Articles', $this -> Articles(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderArticles'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $ArticlesField);

         //***************** Locations Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('TipsPanel', 'Tips'));
        $TipsField = new GridField('Tips', 'Tips', $this -> Tips(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderTips'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $TipsField);


        //***************** RelatedArticles Panels
        
        $fields -> addFieldToTab('Root.Main', new HeaderField('RelatedArticlesPanels', 'Related Tips and Articles'));
		
        $CLTPanelsField = new GridField('RelatedArticles', 'Related Articles', $this -> RelatedArticles(),
        GridFieldConfig_Base::create() -> addComponents(
            new GridFieldSortableRows('SortOrderRelatedArticles'), new GridFieldToolbarHeader(),
            new GridFieldDeleteAction('unlinkrelation'),
            new GridFieldFilterHeader(),
            new GridFieldPaginator(115),
            new GridFieldEditButton(),
            new GridFieldDetailForm(),
            new GridFieldAddExistingAutocompleter()

        ));
        $fields -> addFieldToTab('Root.Main', $CLTPanelsField);



        $fields -> addFieldToTab('Root.Main', new DropDownField('CLTAppPanel', 'App Marketing Panel'));

        return $fields;

    }

    public function Articles() {
        return $this -> getManyManyComponents('Articles') -> sort('SortOrderArticles');
    }

    public function Tips() {
        return $this -> getManyManyComponents('Tips') -> sort('Display_Name');
    }

    public function RelatedArticles() {
        return $this -> getManyManyComponents('RelatedArticles') -> sort('SortOrderRelatedArticles');
    }


}

class CLTLocationPage_Controller extends Page_Controller {

    public function init() {
    	
		 Requirements::javascript("js/pages/CLTPageNavigation.js");
    	 Requirements::javascript("js/pages/CLTipsPages.js");
        parent::init();
    }

}
