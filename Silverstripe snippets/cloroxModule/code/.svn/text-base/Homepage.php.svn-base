<?php

class Homepage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        'Panel_A_Title' => 'Text',
        'Panel_A_description' => 'Text'
    );

    static $many_many = array(

        "HomePanelAData" => "HomePanelA",
        "IcktionaryData" => "Icktionary",
        "RatingsReviewsData" => "RatingsReviews",
        "TipsAndTricksData" => "TipsAndTricks",
        "CausesAndEverythingWeLoveData" => "CausesAndEverythingWeLove"
    );

    public function getpanelA() {

    }

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');

        $dateField = new DateField('Publication Date');

        $PanelASlides = new GridField('Slides', // Field name
        'Slides', // Field title
        $this -> HomePanelAData(), // List of all Panel 1 slides
        GridFieldConfig_RelationEditor::create());

        $IcktionarySlides = new GridField('Slides Icktionary', // Field name
        'Slides Icktionary', // Field title
        $this -> IcktionaryData(), // List of all Icktionary slides
        GridFieldConfig_RelationEditor::create());

        $RatingsReviews = new GridField('Ratings and Reviews', // Field name
        'Ratings and Reviews', // Field title
        $this -> RatingsReviewsData(), // List of all Ratings_Reviews slides
        GridFieldConfig_RelationEditor::create());

        $TipsAndTricksSlides = new GridField('TipsAndTricks.', // Field name
        'TipsAndTricks.', // Field title
        $this -> TipsAndTricksData(), // List of all Ratings_Reviews slides
        GridFieldConfig_RelationEditor::create());

        $CausesAndEverythingWeLoveColumns = new GridField('CausesAndEverythingWeLove', // Field name
        'CausesAndEverythingWeLove', // Field title
        $this -> CausesAndEverythingWeLoveData(), // List of all Ratings_Reviews slides
        GridFieldConfig_RelationEditor::create());

        // Create a tab named "Ingredients" and add our field to it
        $fields -> addFieldToTab('Root.Home Top Slides', $PanelASlides);
        $fields -> addFieldToTab('Root.Icktionary Slides', $IcktionarySlides);
        $fields -> addFieldToTab('Root.Ratings Reviews', $RatingsReviews);
        $fields -> addFieldToTab('Root.Tips & Tricks', $TipsAndTricksSlides);
        $fields -> addFieldToTab('Root.Causes & Everything We Love', $CausesAndEverythingWeLoveColumns);

        return $fields;

    }

}

class Homepage_Controller extends Page_Controller {

    public function init() {
        parent::init();
    }

}
