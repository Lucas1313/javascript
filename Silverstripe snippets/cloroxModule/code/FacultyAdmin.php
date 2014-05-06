<?php
class FacultyAdmin extends ModelAdmin {

    public static $managed_models = array(
        'Faculty',
     
    
    );
    // Can manage multiple models

    static $url_segment = 'faculty';
    // Linked as /admin/faculty/

    static $menu_title = 'Faculty Admin';

    public function getExportFields() {
        
        $allFaculty = Faculty::get();      
        foreach($allFaculty as $k=>$faculty){
            $faculty->write();
        }
    }

}
?>