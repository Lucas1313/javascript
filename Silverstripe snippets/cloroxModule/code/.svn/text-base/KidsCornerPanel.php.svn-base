<?php
/*
 * KidsCornerPanel
 *
 * Describes the Model for a KidsCornerPanel
 *
 * @author Luc Martin lmartin -at- clorox.com (I didn't do that!!! Luc Ross did it!)
 * @version $Id: IcktionaryItem.php 22363 2013-05-19 20:43:22Z jware $
 *
 * Relationships:
 *
 */
class KidsCornerPanel extends FeaturePanel {
    public static $db = array(
        'KidPanel_Class' => 'Text'
    );

    public static $belong_many_many = array(
        'KidsCornerPage' => 'KidsCornerPage'
    );


    public function getCMSFields() {
            $cssClasses_Controller = new CssClasses_Controller();

            $fields = parent::getCMSFields();
            $fields -> addFieldToTab('Root.Main', $cssClasses_Controller -> KidPanel_Class('KidPanel_Class'));

            return $fields;
    }

    function getKidActivities() {
        $KidsCornerDetailPages = KidsCornerDetailPage::get() -> filter(
            array('KidPanel_Class' => $this -> KidPanel_Class));
        //     error_log('hi' .  $this -> KidPanel_Class);
        //     foreach ($KidsCornerDetailPages as $k => $value) {
        //     error_log($value -> Title);
        // }

            return $KidsCornerDetailPages;
    }
}
