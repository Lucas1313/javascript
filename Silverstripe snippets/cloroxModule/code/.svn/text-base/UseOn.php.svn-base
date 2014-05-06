<?php
class UseOn extends DataObject {
    static $db = array(
        'Title' => 'Text',
        'Display_Name' => 'Text',
        'Related' => 'Text',
        'Instructions' => 'HtmlText',
        'Disclaimer' => 'HtmlText',
        'Product' => 'HtmlText',
        'For' => 'Text',
        'Room' => 'Text',
        'Product_Code_Name' => 'Text',
        'Code_Name' => 'Text',
        'Image_Class' => 'Varchar',
    );

    static $has_one = array(
        'InstructionImage' => 'Image',
        'Icon' => 'Image'
    );

    static $belong_many_many = array('UseInRoom' => 'UseInRoom');

    public static $summary_fields = array(
        'ID' => 'ID',
        'Title' => 'Name',
        'Display_Name' => 'Display_Name',
        'Related' => 'Related',
        'Product' => 'Product',
        'Room' => 'Room',
        'For' => 'UseFor',
        'Disclaimer' => 'Disclaimer',
        'Instructions' => 'Instructions',
        'Product_Code_Name' => 'Product_Code_Name',
        'Image_Class' => 'Image_Class'
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root.Main', 'Content');

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextField('Product'));
        $fields -> addFieldToTab('Root.Main', new TextField('For'));
        $fields -> addFieldToTab('Root.Main', new TextField('Room'));
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));
        $fields -> addFieldToTab('Root.Main', new TextField("Image_Class", "Image_Class"));
        $fields -> addFieldToTab('Root.Main', new TextField('Product_Code_Name'));
        $fields -> addFieldToTab('Root.Main', new LiteralField('Code_Name', '<div id="Code_Name" class="field"><label class="left" for="middleColumn">Code Name</label><div class="middleColumn"><div class="readOnlyText" style="font-weight: bold; font-size: 12px; height: 100%; padding: 8px 0 8px 7px; width: 500px; border: 1px solid #999; border-radius: 5px;">' . $this -> Code_Name . '</div></div></div>'));
        $fields -> addFieldToTab('Root.Main', new LiteralField('Related', '<div id="Related" class="field"><label class="left" for="middleColumn">Related</label><div class="middleColumn"><div class="readOnlyText" style="font-weight: bold; font-size: 12px; height: 100%; padding: 8px 0 8px 7px; width: 500px; border: 1px solid #999; border-radius: 5px;">' . $this -> Related . '</div></div></div>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));

        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'InstructionImage', $title = 'Upload the Instructions Image'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Icon', $title = 'Upload the Icon'));

        return $fields;
    }

    public function iconURL() {
        $strManipulator = new StringManipulator_Controller();
        return $strManipulator -> generateCodeName($this -> Title);
    }

    public function onBeforeWrite() {

        $strManipulator = new StringManipulator_Controller();
        $test = $this -> ID;

        if (empty($this -> Display_Name)) {
            $this -> Display_Name = $this -> Name;
        }
        if (empty($this -> Title)) {
            $this -> Title = $this -> Display_Name;
        }

        $this -> Code_Name = $strManipulator -> generateCodeName($this -> Name . '_' . $this -> Related);

        parent::onBeforeWrite();

    }



    /**
     * function cleanImageClass
     * will generate a usable filename for the image
     */
    public function cleanImageClass() {

        $str = str_replace('+', '-', $this -> Image_Class . '.png');
        $str = str_replace('/', '-', $str);
        $str = str_replace('(', '-', $str);
        $str = str_replace(')', '-', $str);
        $str = str_replace(' ', '-', $str);
        $str = str_replace('--', '-', $str);
        $str = str_replace('--', '-', $str);
        $str = str_replace('-', '-', $str);
        $str = str_replace('-.png', '.png', $str);
        $str = strtolower($str);
        return $str;
    }

    /**
     * Function Code_Name
     * Description will generate a unique codename for the useOn Object
     */
    public function Code_Name() {
        $strManipulator = new StringManipulator_Controller();

        $Code_Name = $strManipulator -> generateCodeName($this -> Product).
                     $strManipulator -> generateCodeName($this -> For).
                     $strManipulator -> generateCodeName($this -> Room).
                     $strManipulator -> generateCodeName($this -> Title);

        return $Code_Name;
    }

}
