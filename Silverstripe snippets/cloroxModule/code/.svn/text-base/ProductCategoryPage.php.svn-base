<?php
class ProductCategoryPage extends Page {

    static $allowed_children = array('SingleProductPage');

    static $db = array(
        'IconSource' => 'Text',
        'CategoryName' => 'Text'
    );

    static $has_many = array('Products' => 'Product');
    static $many_many = array('AlsoLikesItems' => 'AlsoLikeItem');

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $allChildrenPages = $this -> listChildrenPages($fields);

        $fields -> removeFieldfromTab('Root.Main', 'Content');
        $fields -> removeFieldfromTab('Root.Main', 'CategoryName');

        // Create a gridfield to hold the Also like relationship
        $fields -> addFieldToTab('Root.Main', new GridField('AlsoLikesItems', 'AlsoLikesItems', $this -> AlsoLikesItems(), GridFieldConfig_RelationEditor::create()));

        return $fields;
    }

    /**
     * Method to generate a tree of all the products and ProductSubCategory in the category
     *
     * @param $fields CMSFields
     * @return void
     */
    public function listChildrenPages($fields) {

        $pageIDs = $this -> getDescendantIDList();

        foreach ($pageIDs as $key => $pageId) {
            $childrenPage = SingleProductPage::get() -> filter(array('ID' => $pageId));
            $allProducts = $childrenPage[0] -> Product();
            $prodAr = '';
            $fields -> addFieldToTab('Root.Main', new LiteralField($childrenPage[0] -> Title, '<div class="border round-corners" style="border: 1px solid #999; border-radius:8px; padding:15px; margin-bottom:20px;"><h4>' . $childrenPage[0] -> Title . '</h4>'));
            foreach ($allProducts as $key => $product) {

                $fields -> addFieldToTab('Root.Main', new LiteralField($childrenPage[0] -> Title, '<div style="font-size:12px; font-weight:normal; margin:0 0 5pxpx 20px;">' . $product -> Name . '</div>'));
                $prodObject = Product::get() -> filter(array('ID' => $product -> ID));

                $ProductSubCategory = $prodObject[0] -> returnProductSubCategory();
                $ProductSubCategoryRelationship = $prodObject[0] -> returnSubProducsRelationship();
                //$fields->addFieldToTab('Root.Main', new CheckboxSetField('ProductSubCategory', $ProductSubCategoryRelationship));

                if (!empty($ProductSubCategory[0])) {
                    foreach ($ProductSubCategory as $k => $subProduct) {
                        $fields -> addFieldToTab('Root.Main', new LiteralField($childrenPage[0] -> Title, '<div style="font-size:11px; font-weight:normal; margin: 5px 0 0 40px;">' . $subProduct -> Name . '</div>'));
                    }
                }

            }
            $fields -> addFieldToTab('Root.Main', new LiteralField($childrenPage[0] -> Title, '</div>'));

        }
        return $pageIDs;
    }

}

class ProductCategoryPage_Controller extends Page_Controller {

}
?>