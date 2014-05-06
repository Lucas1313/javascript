<?php
/*
 * Class BLMRegistrationPage
 * Provides controller for sharing a moment while registering/logging in
 *
 * @author Matt Ernst matt.ernst -at- clorox.com
 * @version $Id
 */

class BLMRegistrationPage extends BLMMasterPage {

    public function ShowBLRegForm() {
        $this->PrepRegForm();
        return new BLMRegistrationPage_Controller();
    }

}

class BLMRegistrationPage_Controller extends RegistrationForm_Controller {
    public function init() {
        Requirements::javascript("js/pages/blm-registration-page.js");
        parent::init();
    }
    public function sectionName(){
        return 'BLM';
    }
    /**
     * LoginForm function.
     * Provide a login form with hidden, pre-populated moment field.
     *
     * @author matt.ernst -at- clorox.com
     * @version $ID
     * @return Object the login form data
     */

    public function LoginForm() {
        $C = new LoginForm_Controller();
        // formdata is a 3 entry array: fields for the form, actions, and
        // required form fields
        $formdata = $C->LoginFormData();

        $moment = HiddenField::create('bleachableMoment');
        $moment->setValue($this->MomentData());
        $formdata[0]->push($moment);

        $loginForm = new Form($this, 'LoginForm', $formdata[0], $formdata[1],
                               $formdata[2]);

        return $loginForm;

    }

    /**
     * SignupForm function.
     * Provide a signup form with hidden, pre-populated moment field.
     *
     * @author matt.ernst -at- clorox.com
     * @version $ID
     * @return Object the signup form data
     */

    public function SignupForm() {
        // formdata is a 3 entry array: fields for the form, actions, and
        // required form fields
        $formdata = $this->SignupFormData();
        $moment = HiddenField::create('bleachableMoment');
        $moment->setValue($this->MomentData());
        $formdata[0]->push($moment);

        $signupForm = new Form($this, 'SignupForm', $formdata[0], $formdata[1],
                               $formdata[2]);

        // don't allow selection of under-18 birthdate
        $bd = $formdata[0]->fieldByName('Birthday');
        $signupForm->minBirthDate = $bd->getConfig('max');

        return $signupForm;

    }

    /**
     * FBSignupForm function.
     * Provide a Facebook signup form with hidden, pre-populated moment field.
     *
     * @author matt.ernst -at- clorox.com
     * @version $ID
     * @return Object the signup form data
     */

    public function FBSignupForm() {
        // formdata is a 3 entry array: fields for the form, actions, and
        // required form fields
        $formdata = $this->FBSignupFormData();
        $moment = HiddenField::create('bleachableMoment');
        $moment->setValue($this->MomentData());
        $formdata[0]->push($moment);

        $signupForm = new Form($this, 'FBSignupForm', $formdata[0], 
                               $formdata[1], $formdata[2]);

        // don't allow selection of under-18 birthdate
        $bd = $formdata[0]->fieldByName('Birthday');
        $signupForm->minBirthDate = $bd->getConfig('max');

        return $signupForm;

    }

    /*
     * MomentData function
     * returns moment data passed as a query string parameter to the page
     *
     * @author matt.ernst -at- clorox.com
     * @version $ID
     */
    public function MomentData() {
        if (isset($_GET['moment'])) {
            return $_GET['moment'];
        }

        return '';
    }

    /*
     * HandleMoment function
     * Submits user's moment and redirects to the thanks page with status set
     *
     * @author matt.ernst -at- clorox.com
     * @version $ID
     */
    public  function HandleMoment($data, $consumer_id) {
        $moment = '';
        if (isset($data['bleachableMoment'])) {
            $moment = $data['bleachableMoment'];
        }

        // need to get a separate controller since this inherits from reg form
        $C = new BLMMasterPage_Controller();
        $status = $C->createSingleMoment($moment,
                                         $consumer_id);

        $dest = '/laugh/bleach-it-away/thanks';
        $this->redirect($dest . '?status=' . $status);
        
    }

    /**
     * SignupAction function.
     * Sign up a user and simultaneously save a Bleachable Moment.
     * Redirect the user to the BM-specific thanks page on success.
     *
     * @param array $data - the request
     * @param object $form  - the signup form data
     * @param $thanks redirect to main thanks page if true
     * @author matt.ernst -at- clorox.com
     * @version $ID
     * @return void
     */

    public function SignupAction($data, $form, $thanks=false, $regSource="BleachableMoments") {
         // ss method to prevent sql injection
        $email = Convert::raw2sql($data['Email']); 
        //error_log('General Signup Action hit');
        // if the user already exists we need to throw an error
        if( $this->AccountExists($email) ) {
            $this->HandleSignupFailure($data, $form, "Sorry, that email address already exists. Please choose another.");
            $this->redirectBack();
        }

        // the Silverstripe date form returns an array with named keys -- 
        // cobble it into a form strtotime understands.
        $dob = explode('/',trim($data['Birthday']));
        $dob = $dob[2].'-'.$dob[0].'-'.$dob[1];
        // if there's amnything wrong with DOB
        // kick back to form
        if(!$this->UserIs18($dob)){
            $this->HandleSignupFailure($data, $form, "You must be 18 years of age to register.");
            return 0;
        }

        $consumer = new CCL_PC_Model_Consumer();
        $consumer->setFirstName($data['FirstName']);
        $consumer->setLastName($data['Surname']);
        $consumer->setRegPassword($data['Password']);
        $consumer->setGender($data['Gender']);
        $consumer->setEmailAddress($data['Email']);
        $consumer->setTermsOfUse($data['TermsOfUse']);
        $consumer->setPostalCode($data['Postcode']);
        $consumer->setSource($regSource);
        $consumer->setDob($dob);
        try{
            $consumer_id = $this->CreateUser($consumer, $data, $form, $thanks);
            error_log('$consumer_id'.$consumer_id);
        }catch(exception $e){
            error_log('unable to create user');
            
        }
        $validate = new ValidateUser($data['Email'],$data['Password']);
        //check Member validation
        $memberStatus = $validate->member();
        // get $member object 
        $this->_member = $validate->getMember();
        if($this->_member instanceof Member){
            $this->_member->logIn();
        }
         if(!isset($data['BackURL']) && empty($data['bleachableMoment'])){
                $this->redirect('/laugh/bleach-it-away/thanks#blm');
            }elseif(isset($_REQUEST['BackURL']) && empty($data['bleachableMoment'])){
                //$this->redirect("/".urldecode($_REQUEST['BackURL']));
                $this->redirect('/laugh/bleach-it-away/thanks#blm');
            }elseif(!empty($data['bleachableMoment'])){
                $this->HandleMoment($data, $consumer_id);
            }else{
                $this->redirect('/laugh/bleach-it-away/thanks#blm');
            }
    }

    /**
     * FBSignupAction function.
     * Sign up a Facebook user and simultaneously save a Bleachable Moment.
     * Redirect the user to the BM-specific thanks page on success.
     *
     * @param array $data - the request
     * @param object $form  - the signup form data
     * @param $thanks redirect to main thanks page if true
     * @author matt.ernst -at- clorox.com
     * @version $ID
     * @return void
     */

    public function FBSignupAction($data, $form, $thanks=false, $regSource="BleachableMoments") {
        $consumer_id = parent::FBSignupAction($data, $form, false, $regSource);

        if ($consumer_id) {
            if(isset($_REQUEST['BackURL']) && $_REQUEST['BackURL']){
                $this->redirect($_REQUEST['BackURL']);
            }else{
                $this->HandleMoment($data, $consumer_id);
            }
        }
    }

    /**
     * LoginAction function.
     * This function is called when the user submits the $LoginForm and
     * simultaneously saves a Bleachable Moment.
     *
     * @param array $data - the request
     * @param object $form  - the signup form data
     * @param $redirect - dummy parameter
     * @author matt.ernst -at- clorox.com
     * @version $ID
     * @return void
     */
    public function LoginAction($data, $form, $redirect=false) {
        //error_log('handle LoginForm_Controller in login action');
        //error_log('login action'.$x++);*/
        $member = null; // declare default variable for CLSS_Member
        
        $loginStatus = false; // assume the user can't login by default
        
        // validate ordinary users with Clorox-specific password hashing
        if(defined('SS_PASSWORD_ENCRYPTION_ALGORITHM')){
                Security::set_password_encryption_algorithm(SS_PASSWORD_ENCRYPTION_ALGORITHM);  
            }else{
                //Security::set_password_encryption_algorithm('ccl');
            }
            Security::set_password_encryption_algorithm('ccl');
        // ss method to prevent sql injection
        $data['Email'] = Convert::raw2sql($data['Email']); 
        
        // if the user didn't enter anything
        if( empty($data['Email']) || empty($data['Password']) ) {
            $this->HandleRegistrationFailure($data, $form, "Please make sure email and password fields are filled.");
            break;
        }
        // check user validation for PC consumer and SS Member
        $validate = new ValidateUser($data['Email'],$data['Password']);
        $this->_password = $data['Password'];
        //check consumer validation
        $consumerStatus = $validate->consumer();
        // get $consumer object
        $consumer = $validate->getConsumer();
        //check Member validation
        $memberStatus = $validate->member();
        // get $member object 
        $member = $validate->getMember();
        // decide what to do if both consumer and member does not validate
        if($consumerStatus==1 && $memberStatus==1){
            $loginStatus = true;
            
        // if consumer is valid, but member is not valid, then sync the Member with the Consumer data
        }elseif($consumerStatus==1 && $memberStatus==0){
            $memberSync = new SyncUser($consumer, $data['Password']);
            $loginStatus = true;
        // if the Member is valid but Consumer is not, then the consumer database is missing data!!
        }elseif($consumerStatus==0 && $memberStatus==1){
            //error_log('pc table and member table out of sync');
            $loginStatus == false;
        // if none of these conditions are true just log it
        }else{
            $loginStatus == false;
        }
        
        if ($loginStatus == true) {
            // TODO process login with CWF or CCL
            //$loginStatus = new CWF_Auth;
            //$loginStatus = $loginStatus->processLoginCore($data['Email'], $data['Password']); 
            if(isset($data['bleachableMoment']) && strlen($_REQUEST['bleachableMoment'])>=10){
                $this->HandleMoment($data, $consumer->getId());
            }
            $member->logIn($remember=true);
            //$this->handleRedirect("success");
            if(isset($_REQUEST['BackURL'])){
                
                $this->redirect(urldecode($_REQUEST['BackURL']));
            }else{
                //$this->redirect('/');
                $this->redirect('/laugh/bleach-it-away/');
            }
        }else{
            // Add an error message
            $form->addErrorMessage(
                                   "Message",
                                   "Incorrect email or password.",
                                   "bad"
                                   );
            // Load errors into session and post back
            Session::set(
                         "FormInfo.LoginForm_LoginForm.data", 
                         $data
                         );
            // Redirect back if possible
            $this->redirect("/laugh/bleach-it-away/sign-up");
        }       
        
        
    }
}
