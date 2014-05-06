<?php
/*
 * Class HistoryYearItem
 *
 * Describes the Model for a HistoryYearItem 
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: HistoryYearItem.php 18878 2013-02-23 09:21:31Z jware $
 *
 * Relationships:
 *
 * 
 *
 */
class HistoryYearItem extends DataObject {
    static $db = array(
        'Name' => 'Text',
        'Title'=>'Text',
        'Subtitle' => 'Text',
        'Description' => 'Text'
    );
    static $has_one = array('Image' =>'Image', 'HistoryPanel' => 'HistoryPanel');

    public static $summary_fields = array('Name' => 'Name', );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle'));
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Image', $title = 'Upload the Image related to that Year'));


        return $fields;
    }

}
?>