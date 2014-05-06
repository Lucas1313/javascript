<?php
/**
 * Class ProductSubCategoryOnCsvBulkLoader
 * Parse the csv import for the USE and generate relationships
 * from the related field
 */
class ProductSubCategoryOnCsvBulkLoader extends CsvBulkLoader {
    // The mapping
    public $columnMap = array(
        'ID'=>'ID',
        'Name' => 'Name',
        'Display_Name' => 'Display_Name',
        'Description' => 'Description',
        'Legal_Name' => 'Legal_Name',
        'Code_Name' => 'Code_Name',
        'Remote_Index_Id' => 'Remote_Index_Id',
        'Sort_Order_Sub_Cat' => 'Sort_Order_Sub_Cat'
    );
    //Avoid duplicates
    public $duplicateChecks = array('ID' => 'ID');

 
}
