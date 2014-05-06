<?php
/**
 * Class UseOnCsvBulkLoader
 * Parse the csv import for the USE and generate relationships
 * from the related field
 */
class ProductOnCsvBulkLoader extends CsvBulkLoader {
    // The mapping
    public $columnMap = array(
        'ID' => 'ID',
        'Name' => '->ParseName',
        'Display_Name' => 'Display_Name',
        'Code_Name' => 'Code_Name',
        'Publication_Date' => 'Publication_Date',
        'Slogan' => 'Slogan',
        'CTA_Text' => 'CTA_Text',
        'CTA_Link' => 'CTA_Link',
        'All_Product_Sub_Category' => 'All_Product_Sub_Category',
        'All_Tags_Features' => 'All_Tags_Features',
        'All_Tags_Need' => 'All_Tags_Need',
        'All_Tags_Type' => 'All_Tags_Type'
    );
    //Avoid duplicates
    public $duplicateChecks = array('ID' => 'ID');

    /**
     * function ParseRelated
     * Parse the data
     * will also create relationships for the UseOn UseInRoom and UseFor system
     *
     * @param &$obj The target Object
     * @param $val The field value to parse (cell)
     * @param $record The whole record to be parse (a row)
     * @return void
     * */
    public function ParseName(&$obj, $val, $record) {

        if (empty($record['Display_Name'])) {
            $record['Display_Name'] = $val;
            $obj->Display_Name = $val;
        }

        $strManipulator = new StringManipulator_Controller();

        $obj -> Name = $strManipulator -> removeAllSpecialCharacters($val);
        $this -> ParseRecords($obj, $val, $record);
    }

    public function ParseDisplay_Name(&$obj, $val, $record) {

        if (!empty($record['Name'])) {
            return;
        }
        else {
            $strManipulator = new StringManipulator_Controller();
            $obj -> Name = $strManipulator -> removeAllSpecialCharacters($val);
            $obj -> Display_Name = $val;
            $this -> ParseRecords($obj, $val, $record);
        }
    }

    public function ParseRecords(&$obj, $val, $record) {
        $relationshipImportController = new Relationship_Controller();
        $obj -> All_Tags_Features = $record['All_Tags_Features'];
        $obj -> All_Tags_Need = $record['All_Tags_Need'];
        $obj -> All_Tags_Type = $record['All_Tags_Type'];
        $obj -> All_Product_Sub_Category = $record['All_Product_Sub_Category'];
        $relationshipImportController -> writeRelationshipFromCsv($obj, 'All_Tags_Features', $obj -> TagFeatures(), 'TagFeatures', 'Name');
        $relationshipImportController -> writeRelationshipFromCsv($obj, 'All_Tags_Need', $obj -> TagNeed(), 'TagNeed', 'Name');
        $relationshipImportController -> writeRelationshipFromCsv($obj, 'All_Tags_Type', $obj -> TagType(), 'TagType', 'Name');
        $relationshipImportController -> writeRelationshipFromCsv($obj, 'All_Product_Sub_Category', $obj -> ProductSubCategories(), 'ProductSubCategory', 'Code_Name');
    }

}
?>