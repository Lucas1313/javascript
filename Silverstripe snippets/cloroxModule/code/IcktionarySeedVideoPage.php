<?php
/**
 * Class IcktionarySeedVideoPage extends Page
 * Definition: Seed Video Page for the Icks
 * @author James Billing and Luc Martin lmartinatclorox.com
 * @version $Id
 */
class IcktionarySeedVideoPage extends Page {
    static $db = array(
        'Subtitle' => 'HtmlText',
        'Description' => 'HtmlText'
    );

    static $many_many = array(
        'VideoItems' => 'VideoItem',
        'RelatedVideos' => 'VideoItem'
    );

    public static $many_many_extraFields=array(
        'VideoItems'=>array(
            'SortOrderVideoItems'=>'Int'
        ),
        'RelatedVideos'=>array(
            'SortOrderRelatedVideos'=>'Int'
        )
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->removeFieldFromTab('Root.Main','Content');

        //***************** Videos
        $fields -> addFieldToTab('Root.Main', new HeaderField('Videos_for_That_Page', 'Videos in that page, usually "Ick", and "solve" '));

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


        //*****************Related Videos
        $fields -> addFieldToTab('Root.Main', new HeaderField('Related_Videos_for_That_Page', 'Related Videos'));

        $videoField = new GridField('RelatedVideos', 'RelatedVideos', $this -> RelatedVideos(),
        GridFieldConfig_Base::create() -> addComponents(
            new GridFieldSortableRows('SortOrderRelatedVideos'),
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

	public function LatestVideoItem() {
		return $this->getManyManyComponents('RelatedVideos', "", "Headline ASC", "")->limit(1);
	}
    public function VideoItems() {
        return $this->getManyManyComponents('VideoItems')->sort('SortOrderVideoItems');
    }
    public function RelatedVideos() {
        return $this->getManyManyComponents('RelatedVideos')->sort('SortOrderRelatedVideos');
    }


}

class IcktionarySeedVideoPage_Controller extends Page_Controller {
    function init() {

        Requirements::javascript("js/plugins/jquery.youtubewrapper.js");
        Requirements::javascript("js/pages/IckVideoPage.js");
        Requirements::javascript("js/pages/icktionary.js");
        parent::init();
    }
}
