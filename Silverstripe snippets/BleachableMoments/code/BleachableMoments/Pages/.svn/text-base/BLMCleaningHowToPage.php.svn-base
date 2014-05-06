<?php
/**
 * Class BLMCleaningHowToPage
 * Describes the Model for a BLMFunStuffPage
 *
 *  Purpose: Cleaning How-To Page
 * @author James Billings james.billings -at- clorox.com
 * @version $Id
 */
class BLMCleaningHowToPage extends BLMMasterPage {
    static $db = array(
        'Description' => 'HtmlText',
        'TwitterCopy'=>'Text',
        'PinterestCopy'=>'Text'
    );
	static $many_many = array(
        'VideoItems' => 'VideoItem',
        'SnapGuides' => 'SnapGuide',
        'BLMGifPromos' => 'BLMGifPromo'
    );
	static $many_many_extraFields=array(
        'VideoItems'=>array(
            'SortOrderVideoItems'=>'Int'
        ),
        'BLMGifPromos'=>array(
            'SortOrderBLMGifPromos'=>'Int'
        ),
        'SnapGuides'=>array(
			'SortOrderSnapGuides'=>'Int'
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
		
		//***************** Animated Gifs
        $fields -> addFieldToTab('Root.AnimatedGifs', new HeaderField('AnimatedGifs_for_That_Page', 'Animated Gifs in that page'));

        $BLMGifPromoField = new GridField('BLMGifPromos', 'BLMGifPromos', $this -> BLMGifPromos(),
        GridFieldConfig_Base::create() -> addComponents(
            new GridFieldSortableRows('SortOrderBLMGifPromos'),
            new GridFieldToolbarHeader(),
            new GridFieldDeleteAction('unlinkrelation'),
            new GridFieldFilterHeader(),
            new GridFieldPaginator(10),
            new GridFieldEditButton(),
            new GridFieldDetailForm(),
            new GridFieldAddExistingAutocompleter(),
            new GridFieldAddNewButton()
        ));
        $fields -> addFieldToTab('Root.AnimatedGifs', $BLMGifPromoField);
		
		
		//***************** SnapGuides
		$fields -> addFieldToTab('Root.Main', new HeaderField('SnapGuide', 'SnapGuides in that page, usually "Bleachable Moments", and "solve" '));

        $SnapGuidesField = new GridField('SnapGuides', 'SnapGuides', $this -> SnapGuides(),
        GridFieldConfig_Base::create() -> addComponents(
			new GridFieldSortableRows('SortOrderSnapGuides'),
            new GridFieldToolbarHeader(),
            new GridFieldDeleteAction('unlinkrelation'),
            new GridFieldFilterHeader(),
            new GridFieldPaginator(10),
            new GridFieldEditButton(),
            new GridFieldDetailForm(),
            new GridFieldAddExistingAutocompleter(),
            new GridFieldAddNewButton()
        ));
        $fields -> addFieldToTab('Root.Main', $SnapGuidesField);
        return $fields;
    }

    public function VideoItems() {
        return $this->getManyManyComponents('VideoItems')->sort('SortOrderVideoItems');
    }
	public function BLMGifPromos() {
		return $this->getManyManyComponents('BLMGifPromos')->sort('SortOrderBLMGifPromos');
	}
	public function SnapGuides() {
        return $this->getManyManyComponents('SnapGuides');
    }

}

class BLMCleaningHowToPage_Controller extends BLMMasterPage_Controller {

    public function init() {
        Requirements::javascript("js/pages/blm-cleaninghowto-page.js");
        Requirements::javascript("js/plugins/jquery.youtubewrapper.js");
		Requirements::javascript("js/plugins/jquery.videoPlayerManager.js");
        parent::init();
    }

}