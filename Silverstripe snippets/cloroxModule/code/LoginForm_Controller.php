<?php
/**
 * LoginForm Class used to render login forms in template. 
 * 
 * LoginForm_Controller renders login form and processes actions.
 * 
 * @author Matt Ernst matt.ernst -at- clorox.com
 * @package cloroxModule.code
 * @version $Id: LoginForm_Controller.php 30123 2014-03-28 21:12:13Z ksmith $
 */

class LoginForm_Controller extends Page_Controller {
    /**
     * LoginForm function.
     * Lets you put a form on your page, using $LoginForm.
     * The list of form fields must be built here.
     * 
     * @return array of field data
     */
    public function LoginFormData() {
        // the fields
        // ...This controller handles sensitive data. Force HTTPS
        $emailField = EmailField::create("Email", "Email Address");
        $passwordField = PasswordField::create("Password", "Password");

        $fields = new FieldList($emailField, $passwordField);
        $actions = new FieldList(new FormAction("LoginAction", "Login"));
        $required = new RequiredFields();
        
        // Force autocomplete=off attribute, as per sec. compliance request
        Requirements::customScript('
            jQuery(document).ready(function() {
                jQuery("#Form_LoginForm").attr("autocomplete", "off");
            });
        ');

        return array($fields, $actions, $required);
    }

    /**
     * LoginForm function.
     * Lets you put a form on your page, using $LoginForm.
     * The list of form fields must be built here.
     * 
     * @return Object the login form data
     */
    public function LoginForm() {        
        $data = $this->LoginFormData();
        // the form
        $loginForm =  new Form($this, "LoginForm", $data[0], $data[1], 
                               $data[2]);

        return $loginForm;
    }


    /**
     * CloneUser function.
     * This function is called to duplicate a PC Consumer into a Silverstripe
     * Member if a user with a PC Consumer account is logging in to 
     * Silverstripe for the first time.
     * 
     * @param CCL_PC_Model_Consumer $consumer - PC consumer to clone into SS
     * @param string $password - password to check against account
     * @return Member
     */

    protected function CloneUser($consumer, $password) {
            $x=0;
        // validate ordinary users with Clorox-specific password hashing
        Security::set_password_encryption_algorithm('ccl');
        $hashed = CCL_PC_Model_Consumer::pwhash($password, 
                                                $consumer->getSalt());
        // if consumer authentication failed, return null instead of clone
        if ($hashed != $consumer->getPassword()) {
            return null;
        }
        $member = new Member();
        $member = $this->UpdateUser($consumer, $member, $updatePassword=true);
        // Add the member to group. (Check if it exists first)
        $group = Group::get()->filter('Title', 'Basic Members')->First();
        if( $group ) { 
            $member->Groups()->add($group);
        }

        return $member;
    }

    /**
     * UpdateUser function.
     * This function is called when the user logs in to Silverstripe in order
     * to make sure that the SS Member is synced with the authoritative data
     * from PC Consumer. If $updatePassword is true, also sync password and
     * salt. Generally $updatePassword should only be called if the SS Member
     * did not exist prior to update.
     * 
     * @param CCL_PC_Model_Consumer $consumer - PC consumer to update into SS
     * @param Member $member - Silverstripe member to update
     * @param bool $updatePassword
     * @return Member
     */

    protected function UpdateUser($consumer, $member, $updatePassword=false) {
        $x=0;
        if(isset($consumer)){
        $attributes = array('FirstName' => $consumer->getFirstName(),
                            'Surname' => $consumer->getLastName(),
                            'Email' => $consumer->getEmailAddress(),
                            'pc_consumer_id' => $consumer->getId());
        if ($updatePassword==true) {
            //set encryptor to None so it stores PW/salt data unchanged
            Config::inst()->update('PasswordEncryptor', 'encryptors',
                                   array('ccl' => array('PasswordEncryptor_None' => null)));
            $attributes['Password'] = $consumer->getPassword();
            $attributes['Salt'] = $consumer->getSalt();
            
        }
        $member->castedUpdate($attributes);
        try{
        $member->write();
        }catch(exception $e){
           //error_log('Exception'.$e);
        }
        return $member;
        }else{
            //error_log('::::::::::::::::: pc consumer unavailable, possibly missing table or info in PC tables::::::');
            return $member;
       }
    }
    
    /**
     * LoginAction function.
     * This function is called when the user submits the $LoginForm.
     * We should use this as the point where a new PC consumer is  
     * created as well.
     * 
     * @param array $data - the request
     * @param object $form  - the signup form data 
     * @param $redirect - redirect to main page at completion if true
     * @return consumer id
     */
    public function LoginAction($data, $form, $redirect=true) {
        $member = null; // declare default variable for CLSS_Member
        
        $loginStatus = false; // assume the user can't login by default
        
        // validate ordinary users with Clorox-specific password hashing
        if(defined('SS_PASSWORD_ENCRYPTION_ALGORITHM')){
                Security::set_password_encryption_algorithm(SS_PASSWORD_ENCRYPTION_ALGORITHM);  
            }else{
                Security::set_password_encryption_algorithm('ccl');
            }
            
        // ss method to prevent sql injection
        $data['Email'] = Convert::raw2sql($data['Email']); 
        
        // if the user didn't enter anything
        if( empty($data['Email']) || empty($data['Password']) ) {
            $this->HandleSignupFailure($data, $form, "Please make sure email and password fields are filled.");
            $this->redirectBack();
        }
        //error_log(' check user validation for PC consumer and SS Member');
        $validate = new ValidateUser($data['Email'],$data['Password']);
        $this->_password = $data['Password'];
        //error_log('check consumer validation');
        $consumerStatus = $validate->consumer();
        //error_log(' get $consumer object');
        $consumer = $validate->getConsumer();
        //error_log('check Member validation');
        $memberStatus = $validate->member();
        //error_log(' get $member object ');
        $member = $validate->getMember();
        //error_log(' decide what to do if both consumer and member does not validate');
        if($consumerStatus==1 && $memberStatus==1){
            $loginStatus = true;
        
        //error_log(' if consumer is valid, but member is not valid, then sync the Member with the Consumer data');
        }elseif($consumerStatus==1 && $memberStatus==0){
            $memberSync = new SyncUser($consumer, $data['Password']);
            $loginStatus = true;
        //error_log(' if the Member is valid but Consumer is not, then the consumer database is missing data!!');
        }elseif($consumerStatus==0 && $memberStatus==1){
            //error_log('pc table and member table out of sync');
        
        //error_log(' if none of these conditions are true just log it');
        }else{
            $loginStatus == false;
        }
        
        if ($loginStatus == true && $member instanceof Member) {
            // TODO process login with CWF or CCL
            //$loginStatus = new CWF_Auth;
            //$loginStatus = $loginStatus->processLoginCore($data['Email'], $data['Password']); 
        
            $member->logIn($remember=true);
            //$this->handleRedirect("success");
            if(isset($_REQUEST['BackURL'])){
                //error_log('redirect after login line 199');
                $this->redirect($_REQUEST['BackURL']);
            }else{
                $this->redirect('/');
            }
        }else{
            //error_log(' Add an error message');
            $form->addErrorMessage(
                                   "Message",
                                   "Incorrect email or password.",
                                   "bad"
                                   );
            //error_log(' Load errors into session and post back');
            Session::set(
                         "FormInfo.LoginForm_LoginForm.data", 
                         $data
                         );
            error_log(' Redirect back if possible');
            if(isset($_REQUEST['BackURL'])){
                $this->redirect('/sign-in/?BackURL='.urlencode($_REQUEST['BackURL']));
            }else{
                $this->redirect('/sign-in/');
            }
        }
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
        //$this->redirectBack();
       
        
            return Controller::redirect('/sign-up/?BackURL='.$_REQUEST['BackURL']);
    }
}
