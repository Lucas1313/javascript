<?php
/**
 * Class BLMFunStuffPage
 * Describes the Model for a BLMFunStuffPage
 *
 *  Purpose: FunStuff Landing Page
 * @author James Billings james.billings -at- clorox.com
 * @version $Id
 * @edit Kody Smith kody.smith -at- clorox.com
 * 
 */
class BLMFunStuffPage extends BLMMasterPage {
    static $db = array(
        'Description' => 'HtmlText',
        'TwitterCopy'=>'Text',
        'PinterestCopy'=>'Text'
    );
	static $many_many = array(
        'VideoItems' => 'VideoItem'
    );
    static $many_many_extraFields=array(
        'VideoItems'=>array(
            'SortOrderVideoItems'=>'Int'
        )
    );
	public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->removeFieldFromTab('Root.Main','Content');

        //***************** Videos
        $fields -> addFieldToTab('Root.Main', new HeaderField('Videos_for_That_Page', 'Videos in that page, usually "Bleachable Moments", and "solve" '));

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

        return $fields;
    }

    public function VideoItems() {
        return $this->getManyManyComponents('VideoItems')->sort('SortOrderVideoItems');
    }
	
}

class BLMFunStuffPage_Controller extends BLMMasterPage_Controller {

    public function init() {
    	Requirements::javascript("js/plugins/jquery.youtubewrapper.js");
    	Requirements::javascript("js/plugins/jquery.videoPlayerManager.js");
        Requirements::javascript("js/pages/blm-funstuff-page.js");
        parent::init();
    }
	

}