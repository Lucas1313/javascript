<?php
/*
 * Class BLMHolidayPage
 * Describes the Model for a BLMHolidayPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class BLMHolidayPage extends BLMMasterPage {
     static $db = array(
        'PublicationDate' => 'Date',
        'Description' => 'HtmlText'
    );
	static $many_many = array(
        'VideoItems' => 'VideoItem'
    );
    static $many_many_extraFields=array(
        'VideoItems'=>array(
            'SortOrderVideoItems'=>'Int'
        )
    );
    public static $belongs_many_many = array();

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        $fields -> removeFieldFromTab('Root', 'Content');

        $dateField = new DateField('PublicationDate');
		$videoField = new GridField('VideoItems', 'VideoItems', $this -> VideoItems(),
		    GridFieldConfig_Base::create() -> addComponents(
		        new GridFieldSortableRows('SortOrderVideoItems'),
		        new GridFieldToolbarHeader(),
		        new GridFieldDeleteAction('unlinkrelation'),
		        new GridFieldFilterHeader(),
		        new GridFieldPaginator(10),
		        new GridFieldEditButton(),
		        new GridFieldDetailForm(),
		        new GridFieldAddExistingAutocompleter(),
		        new GridFieldAddNewButton()
			));
        $fields -> addFieldToTab('Root.Main', $videoField);

        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));

        //***************** Feature Panels
        //$FeaturePanelField = new GridField('manymany', 'manymany', $this -> manymany(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        //$fields -> addFieldToTab('Root.Main', $FeaturePanelField);
        return $fields;

    }
}

class BLMHolidayPage_Controller extends BLMMasterPage_Controller {

    public function init() {
        Requirements::javascript("js/pages/blm-holiday-page.js");
        Requirements::javascript("js/plugins/jquery.youtubewrapper.js");
        Requirements::javascript("js/plugins/jquery.videoPlayerManager.js");
        parent::init();
    }

}