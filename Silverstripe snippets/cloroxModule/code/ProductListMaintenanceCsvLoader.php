<?php
/**
 * Class ProductListMaintenanceCsvLoader
 * Performs a set of maintenances for the site Products
 * 
 */
class ProductListMaintenanceCsvLoader extends CsvBulkLoader {

    public $initDone;

    // The mapping
    public $columnMap = array(
        'Product' => 'Product',
        'Code_Name' => '->ParseName',
        'From_Api' =>'From_Api'
    );

    public function ParseName(&$obj, $val, $record) {
        
        $obj->Code_Name = $record['Code_Name'] = $val;
        
        if (empty($this -> initDone)) {
            $this -> init();
        }

        $allProducts = Product::get();
        foreach ($allProducts as $key => $product) {


            if($product -> Code_Name == $record['Code_Name'] && $record['From_Api'] == 1){
                $product -> From_Api = true;
                $product ->write();
            }
        }

        $allProducts = Product::get() -> exclude(array('Reviewed_Clean' => true));

        $stringManipulator = new StringManipulator_Controller();
        $record['Code_Name'] = $stringManipulator -> generateCodeName($val);
                
        $obj->Code_Name = $record['Code_Name'];
        
        foreach ($allProducts as $key => $product) {
            
            
            if ($product -> Code_Name == $record['Code_Name']) {
                $product -> Reviewed_Clean = true;
                $product -> Review_Delete = false;
                $product -> write();
                return;
            }

        }
        $obj->write();
    }

    public  function init() {

        $allProducts = Product::get();

        foreach ($allProducts as $key => $product) {

            $now = date('M/d/Y h-m-s', strtotime('now'));
            $product -> Reviewed_Clean = false;
            $product -> Review_Delete = false;
            $product -> Review_Notes = 'Checked the  ' . $now;
            $product -> write();
        }
        $this -> initDone = true;
    }

}
