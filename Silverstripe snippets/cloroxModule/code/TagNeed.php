<?php
/*
 * TagNeed extends DataObject
 *
 * Describes the Model for a TagNeed
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: TagNeed.php 25995 2013-10-09 23:54:04Z ksmith $
 *
 * Relationships:
 * HasOne =
 * HasMany =
 * many-many =
 * belong-many-many = Product
 *
 */
class TagNeed extends DataObject {
    static $db = array(
        'Name' => 'Text',
        'Description' => 'Text',
        'Link_Title' => 'Text',
        'Link_Url' => 'Text'
    );

    static $belong_many_many = array('Products' => 'Product', 'IcktionaryItems'=>'IcktionaryItem');

    static $summary_fields = array(
        'Name',
        'Description',
        'Link_Title',
        'Link_Url',
        'ID'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextAreaField('Description'));
        $fields -> addFieldToTab('Root.Main', new TextField('Link_Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Link_Url'));
        return $fields;
    }

}
