<?php
/**
 * forgotPassword Class used to render unique 
 * forgotPassword page type in template.
 * 
 * @author Matthew Madrone matthew.madrone -at- clorox.com
 * @package cloroxModule.code
 */
class ForgotPassword extends Page {

	static $singular_name = "Forgot Password Page";

}
class ForgotPassword_Controller extends Page_Controller {

    public static $allowed_actions = array('ForgotPasswordForm');
    
    public function init(){
		// This controller handles sensitive data. Force HTTPS
        parent::init();
        Requirements::customScript('
            jQuery(document).ready(function() {
                jQuery.validator.addMethod("notPlaceholder", function(value, element, param) { return this.optional(element) || value !== param;}, "Please provide a non-default value.");
                jQuery("#Form_ForgotPasswordForm").validate({
                    errorContainer: "#errorBox",
                    errorLabelContainer: "#errorBox ul",
                    wrapper: "li",
                    rules: {
                        Email: {
                            required: true,
                            email: true
                        }
                    },
                    messages: {
                        Email: "Please enter a valid email address"
                    }
                });
				
				jQuery("#Form_ForgotPasswordForm").attr("autocomplete", "off");
            });
        ');
    }
    
    /**
	 * ForgotPasswordForm function.
	 * This creates the forgot password form
	 * 
	 * @return void
	 */
	public function ForgotPasswordForm(){
		// check to see if a backURL exists
		if(isset($_REQUEST['BackURL'])){
			$ForgotPasswordAction = $_REQUEST['BackURL'];
		}else{
			$ForgotPasswordAction = "ForgotPasswordAction";
		}
        // the fields
        $emailField = EmailField::create("Email", "Email Address");

        // the form
        $forgotPasswordForm =  new Form(
            $this, "ForgotPasswordForm", 
            // build one FieldList with the initial form fields
            // http://doc.silverstripe.org/framework/en/reference/form-field-types
            new FieldList(
                $emailField
            ), 
           
            // one fieldlist holds the call to the form action (method)
            // and the text on the signup button
            new FieldList(
                
                // List the action buttons here
                new FormAction("ForgotPasswordAction", "Change password")

            ), 
            // List the required fields here: "Email", "FirstName"
            new RequiredFields(
			   "Email"
            )
        );
            
        return $forgotPasswordForm;

    	
	}
    
    /**
	 * pc_forgot_password_process function.
	 *
	 * This processes the forgot_password_form and 
	 * sends the user an email with their token  
	 * 
	 * @return void
	 */
	public function ForgotPasswordAction($data, $form){

    	 // what we expect from FB
    		$allowedData = array(
    			'Email'
    			);
    		
    		// map only these onto a new array	
            foreach( $_REQUEST as $key=>$value ){
                if( in_array( $key, $allowedData )){
                    $requestData[$key] = convert::raw2sql($_REQUEST[$key]);   
                }
            }

    	
    	$email = $requestData['Email'];
		
		
		// js validation should never allow this 
    	if( empty($email)){
        	$this->HandleSignupFailure($data, $form, "You did not enter an email address.");
    	}
		
		// let's make sure they are entering an email 
		// that exists in the DB
		$member = Member::get()->filter('Email', "$email")->First();

		// user could exist only as PC Consumer -- if so, clone into Member
		if ( !isset($member) ) {
			$consumer = CCL_PC_Model_Consumer::findByEmail($email);
			if (!empty($consumer)) {
				$member = $this->CloneUser($consumer);
			}
		}
	
		if( !isset($member) ){
         	$this->HandleSignupFailure($data, $form, "The email address '{$email}' was not found.");
        }else{
            
			$member->generateAutologinHash();
    	    
    	    // Otherwise this worked. We need to send
    	    // an email and then redirect to the thanks page
    	    $greeting = $member->getName();
    		$link = WEBROOT.'/Security/changepassword?h='.$member->AutoLoginHash;
    		$copyrightYear = date("Y");
    		
    		require_once($GLOBALS['appPath'] . '/code/Templates/passwordResetEmailText.php');
    		$mail = new Mail();
    		$mail->setFrom(SEND_FROM_EMAIL, SEND_FROM_NAME);
    		$mail->addTo($email, $greeting);
    		$mail->setSubject("Reset Your Clorox Password");
    		$mail->setBodyText($plain_text);
    		$mail->send();
			
			if(isset($_REQUEST['backURL'])){
				$this->redirect('/forgot-password-thanks/?backURL='.$_REQUEST['backURL']);
			}else{
				$this->redirect('/forgot-password-thanks/');
			}
            
        }
	}

	/**
     * CloneUser function.
     * This function is called to duplicate a PC Consumer into a Silverstripe
	 * Member if a user with a PC Consumer account but no SS Member entry
	 * is requesting a password reset.
	 * 
	 * N.B.: this is basically a copy-paste from the registration controller
	 * function of the same name. Code could be factored out into a separate
	 * module but we prefer to avoid deep inheritance.
     * 
     * @param CCL_PC_Model_Consumer $consumer - PC consumer to clone into SS
	 * @return Member
     */

	protected function CloneUser($consumer) {
		// validate ordinary users with Clorox-specific password hashing
		Security::set_password_encryption_algorithm('ccl');

		$member = new Member();
		$member = $this->UpdateUser($consumer, $member);
		
		// Add the member to group. (Check if it exists first)
		$group = Group::get()->filter('Title', 'Basic Members')->First();

		if( $group ) { 
            $member->Groups()->add($group);
		}

		return $member;
	}

	/**
     * UpdateUser function.
     * This function is called when to make sure that the SS Member is synced 
	 * with the authoritative data from PC Consumer. If $updatePassword is 
	 * true, also sync password and salt. Generally $updatePassword should 
	 * only be called if the SS Member did not exist prior to update.
	 * 
	 * N.B.: this is basically a copy-paste from the registration controller
	 * function of the same name. Code could be factored out into a separate
	 * module but we prefer to avoid deep inheritance.
     * 
     * @param CCL_PC_Model_Consumer $consumer - PC consumer to update into SS
	 * @param Member $member - Silverstripe member to update
	 * @return Member
     */

	protected function UpdateUser($consumer, $member) {
		$attributes = array('FirstName' => $consumer->getFirstName(),
							'Surname' => $consumer->getLastName(),
							'Email' => $consumer->getEmailAddress(),
                            'pc_consumer_id' => $consumer->getId());
		//set encryptor to None so it stores PW/salt data unchanged
		Config::inst()->update('PasswordEncryptor', 'encryptors',
							   array('ccl' => array('PasswordEncryptor_None' => null)));
		$attributes['Password'] = $consumer->getPassword();
		$attributes['Salt'] = $consumer->getSalt();
		$member->castedUpdate($attributes);
		$member->write();

		return $member;
	}
	
	/**
     * HandleSignupFailure function.
     * This function is called when a validation check fails on the back end.
     * 
     * @param array $data - the request
     * @param object $form  - the signup form data 
	 * @param string $message - the failure message to return in UI
     * @return void
     */
	protected function HandleSignupFailure($data, $form, $message) {
		$form->addErrorMessage("Message", $message, "bad");
		
		// Load errors into session and post back
		Session::set(
					 "FormInfo.RegistrationForm_RegistrationForm.data", 
					 $data
					 );

		// Redirect back to form
		$this->redirectBack();
	}

}