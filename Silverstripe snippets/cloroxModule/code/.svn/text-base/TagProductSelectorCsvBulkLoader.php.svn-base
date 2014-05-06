<?php
/**
 * Class TagProductSelectorCsvBulkLoader
 * Parse the csv import for the Product selector page and generate relationships
 * from the related field
 */
class TagProductSelectorCsvBulkLoader extends CsvBulkLoader {

    // The mapping
    public $columnMap = array(
        'ID' => 'ID',
        'Substance1' => 'Substance1',
        'Substance2' => 'Substance2',
        'Substance3' => 'Substance3',
        'Substance4' => 'Substance4',
        'Substance5' => 'Substance5',
        'Substance6' => 'Substance6',
        'Substance7' => 'Substance7',
        'Substance8' => 'Substance8',

        'Name' => 'Name',
        'Products' => 'Products',
        'ProductsId' => '->Parse'
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
    public function Parse(&$obj, $val, $record) {

        $productId = $val;

        if(empty($record['Substance1']) || empty($record['Name'])){
            return false;
        }

        $product = Product::get() -> byID($productId);



        // Load the controller to manipulate the codename
        $strManipulator = new StringManipulator_Controller();

        // Check if we already have a surface
        $existingSurface = TagProductSelector::get() -> filter(array(
            'Name' => $record['Name'],
            'Tag_Type' => 'Surface'
        )) -> first();

        // If there is no surface, creates one
        if (empty($existingSurface)) {
            $existingSurface = new TagProductSelector();
            $existingSurface -> Name = $record['Name'];
            $existingSurface -> Codename = $strManipulator -> generateCodeName($record['Name']);
            $existingSurface -> Tag_Type = 'Surface';
            $existingSurface -> write();

           // error_log("WRITING SURFACE ".$record['Name']);
        }

        if (!empty($product -> ID) && !empty($record['Name'])) {
            $this -> associateProduct($obj, $val, $record, $product, $existingSurface, null);
        }

        for($n=1 ; $n<=8; $n++){

            if(!empty($record['Substance'.$n])){

               $substances[] = $record['Substance'.$n];
            }

        }
        foreach ($substances as $substance){
            // Test if we have a substance already
            $existingSubstance = TagProductSelector::get() -> filter(array(
                'Name' => $substance
            )) -> first();

            // if there is no substance, creates one
            if (empty($existingSubstance -> Name)) {

             //   error_log('substance doesnt exists '.$substance);
                $existingSubstance = new TagProductSelector();
                $existingSubstance -> Name = $substance;
                $existingSubstance -> Codename = $strManipulator -> generateCodeName($substance);
                $existingSubstance -> Tag_Type = 'Substance';
                $existingSubstance -> write();

            }



            if (!empty($product -> Title)) {
                $this -> associateProduct($obj, $val, $record, $product, null, $existingSubstance);
            }
        }

    }

    function associateProduct(&$obj, $val, $record, $product, $existingSurface, $existingSubstance) {

        $goWrite = false;

        if(!empty($existingSubstance)){
            // Check if there is a relationship for that substance in the product
            $existingSubstanceRelationship = $product -> relatedSubstances() -> filter(array('Name' => $existingSubstance -> Name)) -> first();

            // if there is none generates one
            if (empty($existingSubstanceRelationship -> Name)) {

                $product -> relatedSubstances() -> add($existingSubstance);
                // tag the product for writing
                $goWrite = true;

            }
        }
        if(!empty($existingSurface)){
        // Check if there is a relationship for the surface
            $existingSurfaceRelationship = $product -> relatedSurfaces() -> filter(array('Name' => $existingSurface -> Name)) -> first();

            // if there is none generates it
            if (empty($existingSurfaceRelationship -> Name)) {
                $product -> relatedSurfaces() -> add($existingSurface);

                // tag the product for writing
                $goWrite = true;
            }

            // If the product is tagged for it, write it
            if ($goWrite == true) {
                $product -> write();
                $obj -> ProductsId = $obj -> ProductsId.', '.$product->ID;

            }
        }
    }

}
