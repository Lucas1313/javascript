<?php

class CloroxAjax extends Page {

    function sayHello() {
        return 'I am the class CloroxAjax saying hello to you as you asked in the $_GET';
    }

}

class CloroxAjax_Controller extends Page_Controller {
        
    // index runs if no other function is being called - it is like a second init()
    function index() {
    
        if ($this -> isAjax) {
            
            
            $cloroxAjax = new CloroxAjax();
            
            if(empty($_GET['ajaxRequest'])) return;
            
            switch ($_GET['ajaxRequest']) {
                
                case 'test' :
                    
                    return json_encode($cloroxAjax->sayHello());
                    
                break;
            }
          

        }
    }
}
?>