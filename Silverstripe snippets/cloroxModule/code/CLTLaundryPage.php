<?php
/*
 * Class CLTLocationPage
 *
 * Describes the Model for a CLTLocationPage the home page for the Cleaning and Laundry tips
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class CLTLaundryPage extends Page {
    static $db = array(

        'Subtitle' => 'HTMLText'
    );

    static $has_one = array('CLTAppPanel' => 'CLTAppPanel');

    static $many_many = array(
        'TopTips' => 'CLTPanel',
        'RelatedArticles' => 'CLTPanel'
    );

    public static $many_many_extraFields = array(
        'TopTips' => array('SortOrderTopTips' => 'Int'),
        'RelatedArticles' => array('SortOrderRelatedArticles' => 'Int')
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');

        //***************** Description
        $fields -> removeFieldFromTab('Root.Main', 'Description');


        //***************** TopTips Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('TopTips','Top Tips of the Season'));

        $CLTPanelsField = new GridField('TopTips', 'Top Tips of the season', $this -> TopTips(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderTopTips'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $CLTPanelsField);

        $fields -> addFieldToTab('Root.Main', new DropDownField('CLTAppPanel', 'App Marketing Panel'));

        //***************** TopTips Panels
        $fields -> addFieldToTab('Root.Main', new HeaderField('Related_Articles','Related Articles'));

        $CLTPanelsField = new GridField('RelatedArticles', 'Related Articles', $this -> RelatedArticles(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderRelatedArticles'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $CLTPanelsField);

        $fields -> addFieldToTab('Root.Main', new DropDownField('CLTAppPanel', 'App Marketing Panel'));

        return $fields;

    }



    public function TopTips() {
        return $this -> getManyManyComponents('TopTips') -> sort('SortOrderTopTips');
    }

    public function RelatedArticles() {
        return $this -> getManyManyComponents('RelatedArticles') -> sort('SortOrderRelatedArticles');
    }

}

class CLTLaundryPage_Controller extends Page_Controller {

    public function init() {

        Requirements::javascript("js/pages/CLTipsPages.js");
        parent::init();
    }

}
