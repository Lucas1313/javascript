<?php
/*
 * Class BLMThanksPage
 * Provides controller for showing thanks message after moment submission.
 *
 * @author Matt Ernst matt.ernst -at- clorox.com
 * @version $Id
 */

class BLMThanksPage extends BLMMasterPage {

    /**
     * function status
     * Purpose: return status of user's submission attempt to control rendering
	 * of thanks page.
	 * A user who has never before submitted gets thanks and a coupon.
	 * A user who has submitted recently gets a message that they need to wait.
	 * A user who has submitted before but not recently gets thanks but no coupon.
     */
	public function status(){
        // clean up the request for injection
	    foreach ($_REQUEST as $key => $value) {

            $_REQUEST[$key] = Convert::raw2sql($value);

        }
        // returns the status on the thanks page
		if (isset($_REQUEST['status'])) {

			$status = $_REQUEST['status'];
			if ($status == 'never') {
				return 'coupon';
			}
			else if ($status == 'old') {
				return 'nocoupon';
			}
			else if ($status == 'recent') {
				return 'oops';
			}
		}
	}

}

class BLMThanksPage_Controller extends BLMMasterPage_Controller {

    public function init() {
        parent::init();
    }

}
