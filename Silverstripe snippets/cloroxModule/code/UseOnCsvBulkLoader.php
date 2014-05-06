<?php
/**
 * Class UseOnCsvBulkLoader
 * Parse the csv import for the USE and generate relationships
 * from the related field
 */
class UseOnCsvBulkLoader extends CsvBulkLoader {
    // The mapping

    public $columnMap = array(
        'ID' => 'ID',
        'Title' => 'Title',
        'Related' => 'Related', // the column to be parsed as relationship
        'Display_Name' => 'Display_Name',
        'Disclaimer' => 'Disclaimer',
        'Instructions' => 'Instructions',
        'Product' => 'Product',
        'Product_Code_Name' => 'Product_Code_Name',
        'For' => 'For',
        'Room' => 'Room',
        'Image_Class'=>'Image_Class'
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
    public static function ParseRelated(&$obj, $val, $record) {

        // Set the name
        $obj -> Title = $val;
        if(empty($record['Product'])){
            return;
        }
        // Fill the display name if not filled
        if (empty($record['Display_Name'])) {
            $record['Display_Name'] = $val;
        }
        // Generate a codename if it's not in the csv
        if (empty($record['Product_Code_Name'])) {
            $stringManipulator = new StringManipulator_Controller();
            $record['Product_Code_Name'] = $stringManipulator -> generateCodeName($record['Product']);
        }

        //Test where the data is coming from for the relationships
        // First from the Related field field
        if (!empty($record -> Related)) {
            $obj -> Related = $record -> Related;
            //error_log('Build relationship from the Related field');
        }
        // Second, grouping the 'Room' 'For' and 'Product' fields
        elseif (!empty($record['For']) && !empty($record['Room']) && !empty($record['Product_Code_Name'])) {
            $obj -> Related = $record['Room'] . '_' . $record['For'] . '_' . $record['Product_Code_Name'];
            //error_log('Build relationship from the set of data in each field: Room, For, Product '.$obj -> Related);
        }
        //No relationship established. Cancel the record import
        else {
            return;
        }

        //Now we do the import
        $parents = explode('_', $obj -> Related);

        // Grab the product from the CMS
        $product = Product::get() -> filter(array('Title' =>  $record['Product']));

        $product = $product[0];

        // Use an Iterator to protect the system from looping more than once
        if(empty($product)){
            /*
            $product = new Product();
            $product->Name = $stringManipulator -> removeAllSpecialCharacters($record['Product']);
            $product->Title = $stringManipulator -> removeAllSpecialCharacters($record['Product']);
            $product->Display_Name = str_replace('Clorox', 'Clorox&reg;', $record['Product']);
            $product->Display_Name = str_replace('Clorox&reg; 2', 'Clorox 2&reg;', $product->Display_Name);
            $product->Code_Name = $record['Product_Code_Name'];
            $product->Require_Editing = true;
            $product->write();
             * */

        }
        if (!empty($product)) {

            $iterator = 0;
            $useFor = array();
            while (empty($useFor[0]) && $iterator <= 1) {
                //Test if there is already a UseFor Object with that name
                $useFor = $product -> UseFor() -> filter(array('Name' => $parents[1]));
                // If there is none Create one
                if (empty($useFor[0])) {

                    $newUsefor = new UseFor();
                    $newUsefor -> Name = $parents[1];
                    $newUsefor -> Product = $parents[2];
                    $newUsefor -> write();
                    $product -> UseFor() -> add($newUsefor);
                }
                ++$iterator;
            }
            $iterator = 0;
            while (empty($useInRoom[0]) && $iterator <= 1) {
                $useInRoom = $useFor[0] -> UseInRooms() -> filter(array('Name' => $parents[0]));
                if (empty($useInRoom[0])) {
                    $newUseInRoom = new UseInRoom();
                    $newUseInRoom -> Name = $parents[0];
                    $newUseInRoom -> Product = $parents[2];
                    $newUseInRoom -> For = $parents[1];
                    $newUseInRoom -> write();
                    $useFor[0] -> UseInRooms() -> add($newUseInRoom);
                }
                ++$iterator;
            }
            $iterator = 0;
            while (empty($UseOn[0]) && $iterator <= 1) {
                $UseOn = $useInRoom[0] -> UsesOn() -> filter(array('Name' => $obj -> Name));
                if (empty($UseOn[0])) {
                    //error_log('Hopelessly looping');
                    $useInRoom[0] -> UsesOn() -> add($obj);
                    $useInRoom[0] -> Product = $parents[2];
                    $useInRoom[0] -> For = $parents[1];
                    $useInRoom[0] -> Room = $parents[0];
                    $useInRoom[0] -> write();
                    $obj -> write();
                }
                ++$iterator;
            }
        }

    }

}
?>