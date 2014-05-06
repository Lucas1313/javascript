<?php
class PanelPage extends Page{
    static $db = array(
        'Header' => 'HTMLText',
        'Subheader'=>'HTMLText',
        'Publication Date' => 'Date',
        'Panel_A_Title' => 'Text',
        'Panel_A_description' => 'Text'
    );

   // static $has_one = array('PanelPageSlideShow'=> 'UniversalSlideShow');

    static $has_one = array(
        'PanelPageCarousel_1'=> 'Carousel',
        'PanelPageCarousel_2'=> 'Carousel',
        'PanelPageCarousel_3'=> 'Carousel',
        'PanelPageCarousel_4'=> 'Carousel',

    );

    static $many_many = array(
        'PanelPageFeaturePanels' => 'FeaturePanel',
        "RatingsReviewsData" => "RatingsReviews",
        "TipsAndTricksData" => "TipsAndTricks"
    );

    public static $many_many_extraFields = array(
        'PanelPageFeaturePanels' => array('SortOrderPanelPageFeaturePanels' => 'Int')
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');
        $fields -> removeFieldsFromTab('Root', array('TipsAndTricks'));

        $fields -> addFieldToTab('Root.Main',new TextField('Header', 'Header'));
        $fields -> addFieldToTab('Root.Main',new TextField('Subheader', 'Subheader'));

        $dateField = new DateField('Publication Date');

        //************** feature panels
        $FeaturePanelField = new GridField('PanelPageFeaturePanels', 'PanelPageFeaturePanels', $this -> PanelPageFeaturePanels(),
        GridFieldConfig_RelationEditor::create() -> addComponents(
        	new GridFieldSortableRows('SortOrderPanelPageFeaturePanels'),
            new GridFieldAddExistingAutocompleter(),
        	new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);

        $fields -> addFieldToTab('Root.Main',new TextAreaField('Disclaimer', 'Disclaimer'));

        return $fields;

    }

    public function PanelPageFeaturePanels() {
        return $this -> getManyManyComponents('PanelPageFeaturePanels') -> sort('SortOrderPanelPageFeaturePanels');
    }

    /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {
        parent::onBeforeWrite();
    }
}
class PanelPage_controller extends Page_Controller{

}
