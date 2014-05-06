<?php
class ProductPickerPage extends Page {

    static $db = array(
        'Title' => 'Text',
        'SubTitle' => 'Text'
    );
    static $has_many = array(
        'Product' => 'Product'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->removeFieldFromTab('Root.Main', 'Content');

        return $fields;
    }
    function Products(){
        $tagType = 'All_Tags_Features';
        $tag = 'Concentrated';
        if(isset($_REQUEST)){

            foreach ($_REQUEST as $key => $value) {
                $_REQUEST[$key] = Convert::raw2sql($value);
            }
            if(isset($_REQUEST['tagtype']) && isset($_REQUEST['tag'])){

                $tagType = $_REQUEST['tagtype'];
                $tag = $_REQUEST['tag'];
            }
        }

        return Product::get() ->filter(array($tagType.':PartialMatch' => $tag));
    }
}

class ProductPickerPage_Controller extends Page_Controller {

    function init() {
        parent::init();

    }
}