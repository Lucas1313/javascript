<?php
/*
 * Class CLTLocationPage
 *
 * Describes the Model for a CLTLocationPage the home page for the Cleaning and Laundry tips
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class CLTUseForPage extends Page {
    static $db = array(
        'Title' => 'Text',
        'Subtitle' => 'HTMLText'
    );

    static $has_one = array('CLTAppPanel' => 'CLTAppPanel');

    static $allowed_children= array('CLTLocationPage');

    static $can_be_root = false;

    static $many_many = array(
        'TopTips' => 'CLTPanel',
        'RelatedArticles' => 'CLTPanel'
    );

    public static $many_many_extraFields = array(

        'RelatedArticles' => array('SortOrderRelatedArticles' => 'Int'),
        'TopTips' => array('SortOrderTopTips' => 'Int'),

    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');

        //***************** Description
        $fields -> removeFieldFromTab('Root.Main', 'Description');



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

        //***************** TopTips Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('TopTips', 'Top Tips of the Season'));
        $CLTPanelsField = new GridField('TopTips', 'Top Tips of the season', $this -> TopTips(),
        GridFieldConfig_Base::create() -> addComponents(
            new GridFieldSortableRows('SortOrderTopTips'),
            new GridFieldToolbarHeader(),
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

    public function RelatedArticles() {
        return $this -> getManyManyComponents('RelatedArticles') -> sort('SortOrderRelatedArticles');
    }

    public function TopTips() {
        return $this -> getManyManyComponents('TopTips') -> sort('SortOrderTopTips');
    }


}

class CLTUseForPage_Controller extends Page_Controller {

    public function init() {
    	Requirements::javascript("js/pages/CLTPageNavigation.js");
		Requirements::javascript("js/pages/CLTUseForPage.js");
		
        parent::init();
    }

}
