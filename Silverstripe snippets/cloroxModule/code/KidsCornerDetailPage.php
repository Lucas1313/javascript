<?php
/*
 * Class KidsCornerDetailPage
 * Describes the Model for a KidsCornerDetailPage
 *
 * @author Luc Martin lmartin -at- clorox.com (Hey, I didn't do that either!)
 * @version $Id
 */
class KidsCornerDetailPage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        // 'Title' => 'HTMLText',
        'Title_1' => 'HTMLText',
        'Subtitle' => 'HTMLText',
        'Description' => 'HTMLText',
        'Content_1' => 'HTMLText',
        'Content_2' => 'HTMLText',
        'KidPanel_Class' => 'Text'
    );

function showKidPanel_Class() {
    return $this->KidPanel_Class;
}

public static $has_one = array(
    'KidsCornerPanel' => 'KidsCornerPanel'
);

public function getCMSFields() {
        $cssClasses_Controller = new CssClasses_Controller();

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        $fields -> addFieldToTab('Root.Main', $cssClasses_Controller -> KidPanel_Class('KidPanel_Class'));

        $fields -> removeFieldFromTab('Root', 'Content');

        $dateField = new DateField('Publication Date');

        //***************** Title CLASSES
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController -> titleClasses('Title1_Class'));

        //***************** Titles
        $fields -> addFieldToTab('Root.Main', new TextField('Title_1'));

        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextAreaField('Description'));

        //***************** Subtitle
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle'));

        //***************** Content_1
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Content_1'));

        //***************** Content_2
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Content_2'));

        return $fields;

    }
}

class KidsCornerDetailPage_Controller extends Page_Controller {

    // public function init() {
    //     Requirements::javascript("js/pages/our-story.js");
    //     parent::init();
    // }

}
