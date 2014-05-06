<?php
/**
 * Class ProductSubCategoryOnCsvBulkLoader
 * Parse the csv import for the USE and generate relationships
 * from the related field
 */
class IngredientOnCsvBulkLoader extends CsvBulkLoader {
    // The mapping
    public $columnMap = array(
         'ID' => 'ID',
        'Name' => 'Name',
        'Description' => 'Description',
        'Code_Name' => 'Code_Name'
    );
    //Avoid duplicates
    public $duplicateChecks = array('ID' => 'ID');
}
