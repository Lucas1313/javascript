<?php
Class Container extends DataObject {
    static $db = array(
        'Name' => 'Text',
        'Code_Name'=>'Text',
    );
    static $has_one = array(
        "ProductSubCategory" => "ProductSubCategory",
        'ContainerImage' => 'Image',
        
    );

    public static $summary_fields = array(
        'Name' => 'Name',
        'Code_Name'=>'Code_Name',
        'ContainerImage' => 'ContainerImage',
    );

    public function getCMSFields() {
        
        $fields = parent::getCMSFields();
        $fields -> removeFieldfromTab('Root.Main', 'ProductSubCategoryID');
        
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new LiteralField('Code_Name', '<div id="Code_Name" class="field"><label class="left" for="middleColumn">Code Name</label><div class="middleColumn"><div class="readOnlyText" style="font-weight: bold; font-size: 12px; height: 100%; padding: 8px 0 8px 7px; width: 500px; border: 1px solid #999; border-radius: 5px;">' . $this -> Code_Name . '</div></div></div>'));
        
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'ContainerImage', $title = 'Upload the Image for that Container'));
       
        $ProductSubCategory = ProductSubCategory::get() -> filter('ID', $this -> ProductSubCategory() -> ID);
        $gridFieldConfig = GridFieldConfig::create() -> addComponents(new GridFieldToolbarHeader(), new GridFieldSortableHeader(), new GridFieldDataColumns(), new GridFieldEditButton(), new GridFieldDetailForm());
        $ProductSubCategoryField = new GridField('Product', 'Associated to Product Sub Category', $ProductSubCategory, $gridFieldConfig);
        $fields -> addFieldToTab('Root.Main', $ProductSubCategoryField);
        
        return $fields;
    }
    /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {
    
    // generate the Code_Name from the ProductName
        $strManipulator = new StringManipulator_Controller();
        $ProductSubCategory = ProductSubCategory::get() -> filter('ID', $this -> ProductSubCategory() -> ID);
        foreach ($ProductSubCategory as $key => $value) {
            $this -> Code_Name = $strManipulator -> generatecodeName($value->Name.$value->Scent.$this -> Name);
        }
        
        parent::onBeforeWrite();
    }
    
}
