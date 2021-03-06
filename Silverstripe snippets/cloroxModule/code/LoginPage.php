<?php
/**
 * LoginPage Class used to render unique 
 * Login page type in template.
 * 
 * @author Jason Ware jason.ware -at- clorox.com
 * @package cloroxModule.code
 * @version $Id: LoginPage.php 25795 2013-09-27 20:22:41Z ksmith $
 */
class LoginPage extends Page {
}
class LoginPage_Controller extends Page_Controller {
    
    public function init(){
        parent::init();
        // the validation
        Requirements::customScript('
            jQuery(document).ready(function() {
                jQuery("#Form_LoginForm").validate({
                    errorContainer: "#errorBox",
                    errorLabelContainer: "#errorBox ul",
                    wrapper: "li",
                    rules: {
                        Email: {
                            required: true,
                            email: true
                        },
                        Password: {
                            required: true
                        }
                    },
                    messages: {
                        Email: "Be sure to enter a valid email address",
                        Comments: "Please fill in your password"
                    }
                });
            });
        ');
        // the validation for the Facebook form
        Requirements::customScript('
            jQuery(document).ready(function() {
                // checkAge expects jQueryUI datepicker
                // and data-max date value auto-set on element
                jQuery.validator.addMethod("checkAge", 
                    function(value, element) { 
                        // make sure selected date is less than max DOB
                        var maxDOB = new Date( $(element).attr("data-max") );
                        var chosenDOB = value.split("/");
                        chosenDOB = new Date( chosenDOB[2]+"-"+chosenDOB[0]+"-"+chosenDOB[1] );
                        return chosenDOB.getTime() <= maxDOB.getTime();
                    }, "You must be 18 years of age to register."
                );
                jQuery("#Form_FBSignupForm").validate({
                    errorContainer: "#errorBox",
                    errorLabelContainer: "#errorBox ul",
                    wrapper: "li",
                    rules: {
                        FirstName: {
                            required: true,
                            notPlaceholder: "First Name"
                        },
                        Surname: {
                            required: true,
                            notPlaceholder: "Last Name"
                        },
                        Email: {
                            required: true,
                            email: true
                        },
                        TermsOfUse: {
                            required: true
                        },
                        Birthday: {
                            checkAge: true
                        }
                    },
                    messages: {
                        FirstName: "Please enter your first name",
                        Surname: "Please enter your last name",
                        Email: "Please enter a valid email address",
                        TermsOfUse: "Please agree to the terms of use"
                    }
                });
            });
        ');

    }
	public function processBackUrl(){

		if(isset($_REQUEST['BackURL']) && !empty($_REQUEST['BackURL'])){
    		  $this->redirect(htmlspecialchars_decode($_REQUEST['BackURL']));
    		}else{
        		$this->redirectBack();
    		}
	}
    // adding a method to retrieve the BackURL
    // for redirect hidden fields in forms
    public function BackURL() { 
        if(isset($_REQUEST['BackURL'])) {
        	$BackURL = $_REQUEST['BackURL']; 
            return $BackURL; 
        } else { 
            return Session::get('BackURL'); 
        } 
    }

    // adding a method to retrieve the BackURL, urlencoded
    public function BackURLEncoded() { 
        return urlencode($this->BackURL());
    }
    
}