<?php
/**
 * RateAndReview
 * This provides functionality needed on the rate and review
 * landing page when a consumer attempts to write a review
 * 
 * @author Jason Ware jason.ware -at- clorox.com
 * @package cloroxModule.code
 * @version $Id: RateAndReview.php 30123 2014-03-28 21:12:13Z ksmith $
 */
class RateAndReview extends Page {
}
class RateAndReview_Controller extends Page_Controller {

    /* *
     * function bvUserAuthString.
     * this is used to handle auth and all. It returns   
     * the encoded user string for bv if the user is 
     * logged in, or redirects them to log in.
     *
     * output used in the bv form as follows:
     <script type="text/javascript">
        $BV.ui("submission_container", {
            userToken: "{$bvUserAuthString}"
        });
     </script>
     *
     * @param int $currentUserID
     * @return string $encUser
     */
    function bvUserAuthString(){

		$bv_product_id = null;
		if (array_key_exists('bvproductid', $_GET)) {
			$bv_product_id = $_GET['bvproductid'];
		}

        // Is a member logged in?
        if( $currentUserID = Member::currentUserID() ) {
       	    // yes!
		
       	    $encUser = bvModule_Controller_HelperMethods::reviewUserAuthString($currentUserID, $bv_product_id);  
       	    return $encUser;   
       	}else{
			// No :-(  better log them in:
			if(isset($_REQUEST['BackURL'])){
                $this->redirect('/sign-in/?BackURL='.urlencode($_REQUEST['BackURL']));
            }else{
                $this->redirect('/sign-in/');
            }
			//Controller::redirect('/sign-in/?BackURL='.urlencode($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']));
		}
	}
}