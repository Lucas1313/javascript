<?php
/**
 * BLMfaq Object
 *
 * Purpose:Frequently Asked Questions
 * Frequently asked questions for the promotion to address
 * common consumer issues. Questions and answers behave
 * as accordions.
 *
 * @author Luc at Clorox.com
 * @version $ID
 */
class BLMfaq extends DataObject {
    public static $db = array(
        'Question' => 'HTMLText',
        'Answer' => 'HTMLText',
        'SortOrder' => 'Date',
        'TwitterCopy'=>'Text',
        'PinterestCopy'=>'Text'
    );
    public static $has_one = array('BLMFAQPage' => 'BLMFAQPage');
    public static $has_many = array();
    public static $many_many = array();
    public static $belongs_many_many = array();

    // Fields used for the display of info in the CSV and The grids
    static $summary_fields = array(
        'ID',
        'SortOrder',
        'Question',
        'Answer'
    );

    // Searchable fields
    static $searchable_fields = array('Question');
    //the cms fields
    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields ->addFieldToTab('Root.Main', new HeaderField('faqHeader', 'Question and Answer fields</h3><p>Add a question and an Answer for each FAQ</p><h3>'));
        $question = new HtmlEditorField('Question', 'Question');
        $question ->setColumns(5);
        $question ->setRows(10);
        $fields -> addFieldToTab('Root.Main', $question);

        $answer = new HtmlEditorField('Answer', 'Answer');
        $answer -> setRows(10);
        $fields -> addFieldToTab('Root.Main', $answer);

        $fields -> addFieldToTab('Root.Main', new HeaderField('TwitterPinterestCopy', 'Twitter And Pinterest Copy</h2><p>In that section you can set relevant marketing information to pass to the social medias</p><h2>'));
        $fields -> addFieldToTab('Root.Main', new TextField('TwitterCopy', 'Twitter Copy'));
        $fields -> addFieldToTab('Root.Main', new TextField('PinterestCopy', 'Pinterest Copy'));


        return $fields;
    }

}
