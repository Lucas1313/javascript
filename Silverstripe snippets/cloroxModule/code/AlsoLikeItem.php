<?php
/**
 * Class AlsoLikeItem extends DataObject
 *
 * Data Object that represents the also like for all the items in the site
 * The also like is generated at item creation, it uses the Image of each items
 * Managed by Also like item Controller in the Clorox Module
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: AlsoLikeItem.php 22643 2013-05-29 23:14:50Z jware $
 *
 * $belong_many_many = 'ProductsPages'
 *                      'SingleProductPages'
 *SingleTipPages'
 *TipCategoryPages'
 *ProductCategoryPages'
 *LaughCategoryPage'
 *LaughPages'
 *Welcome'
 */
class AlsoLikeItem extends DataObject {

    static $AlsoLikeItem_Controller;

    static $db = array(
        'RelatedObjCategory' => 'Text',
        'RelatedObjectId' => 'Int',
        'Name' => 'HtmlText',
        'Title' => 'HtmlText',
        'Description' => 'HtmlText',
        'LinkTitle' => 'Text',
        'LinkUrl' => 'Text',
        'Product_Image' => 'Text',
        'hasPage'=>'Boolean',
        'SortOrder' =>'Int'
    );

    public static $default_sort='SortOrder';

    static $has_one = array(
        'Product' => 'Product',
        'Image' => 'Image',
        'IcktionaryItem' => 'IcktionaryItem',
        'PressReleasePage' => 'PressReleasePage',
        'PressItemPage' => 'PressItemPage'
    );

    static $belong_many_many = array(
        'ProductsPages' => 'ProductsPage',
        'SingleProductPages' => 'SingleProductPage',
        'SingleTipPages' => 'SingleTipPage',
        'TipCategoryPages' => 'TipCategoryPage',
        'ProductCategoryPages' => 'ProductCategoryPage',
        'LaughCategoryPage' => 'LaughCategoryPage',
        'LaughPages' => 'LaughPage'
    );

    static $summary_fields = array(
        'RelatedObjCategory',
        'RelatedObjectId',
        'Name',
        'Title',
        'Description',
        'LinkTitle',
        'LinkUrl'
    );

    static $searchable_fields = array(
        'RelatedObjCategory',
        'Name',
        'Title',
        'Description',
        'LinkUrl'
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        /** RelatedObjCategory: The type of object this also like is related to**/
        $fields -> addFieldToTab('Root.Main', new TextField('RelatedObjCategory'));

        /** The title to be displayed **/
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        /** The CMS Searchable name **/
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));

        /** Description **/
        $fields -> addFieldToTab('Root.Main', new TextAreaField('Description'));

        /** Image upload **/
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Image', $title = 'Upload the Product Image'));

        /**  The feedback for the also like image that is a cropped image generated automaticaly **/
        $fields -> addFieldToTab('Root.Main', new LiteralField('Product_Image', '<div  class="field">This is the Image for the also like for this Product<div style="font-weight: bold;
                width: 30%;
                height: 100%;
                margin: 10px 0 20px 10px;
                float: left;">Image Path: /' . $this -> Product_Image . '</div><img style="margin:0 0 0 50px"src="' . $this -> Product_Image . '"></div>'));

        /** Link to the related Object **/
        $fields -> addFieldToTab('Root.Main', new TextField('LinkTitle'));
        $fields -> addFieldToTab('Root.Main', new TextField('LinkUrl'));

        return $fields;

    }

    /**
     * function Description truncates the Description to a given amount of characters and adds a ... at the end
     */
    public function DescriptionLimitedChar($charsCount = null){

        if(empty($this->Description)){
            return;
        }

        // we need to break the string in single words
        $descAr = explode(' ',$this->Description);

        // init count
        $count = 0;

        // init return value
        $ret = '';

        // Iterate through the array
        foreach ($descAr as $key => $value) {
                if(!empty($value)){
                // count the character length
                $wordLenght = strlen($value);

                // test if we are still within the character limit
                if($count + $wordLenght < $charsCount){
                    // we are so we add to the count
                    $count += $wordLenght;

                    // add the word to the return value
                    $ret .= $value.' ';
                }else{
                    // test if this is the last word
                    if($value !== $descAr[count($descAr)-1]){

                    // we are over the limit test if the last character is a punctuation
                        if(substr($ret , -2, 1) == "," || substr($ret , -2, 1) == "." || substr($ret , -2, 1) == ";") {
                            // if it is substract it
                            $ret = substr_replace($ret , '',-2);
                        }else{
                            //if it's not remove the blank space
                            $ret = substr_replace($ret ,'', -1, 1);
                        }

                        // add ellipsis
                        $ret .= '...';

                        // break the loop
                        break;
                    }

                }
            }
        }

        return $ret;
    }
    public function init() {

        $this -> AlsoLikeItem_Controller = new AlsoLikeItem_Controller();

    }

}
