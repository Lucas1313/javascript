<?php
/**
 * class ValidateUser
 */
class ValidateUser{
    private $_member;
    private $_consumer;
    private $_password;
    private $_email;
    
    function __construct($email=null, $password=null) {
        if(!empty($email)){
            $this->_email = $email;
            $this->_password = $password;
            $status = $this->consumer();
            //consumer validate'.$status);
            $status = $this->member();
            //member validate'.$status);
        }
    }
    public function setMember($member){
        $this->_member=$member;
    }
    public function setConsumer(){
        $this->_consumer=$consumer;
    }
    public function getConsumer(){
        return $this->_consumer;
    }
    public function getMember(){
        if($this->_member instanceof Member){
            return $this->_member;
        }elseif($this->_email != null){
            $this->_member = Member::get()->filter('Email', $this->_email)->First();
        }elseif($this->_consumer instanceof CCL_PC_Model_Consumer){
            $this->_member = Member::get()->filter('pc_consumer_id', $this->_consumer->getId())->First();
        }else{
            return false;
        }
    }
    /**
     * ValidateUser->consumer() function 
     * 
     * Description:  this is to check an email / password combo against PC tables
     * TODO:: document this function
     */

    public function consumer($email=null, $password=null, $returnType=null) {
        // setup defaults
        ($email == null ? :$this->_email = $email); // if $email is set pass it to the global variable 
        ($password == null ? :$this->_password = $password); // if $password is set pass it to the global variable
        
        //does the consumer exist?
        // check to see if the Consumer exists');
        $this->_consumer = CCL_PC_Model_Consumer::findByEmail($this->_email);
        if($this->_consumer instanceof CCL_PC_Model_Consumer){
            // if a consumer is returned then continue');
        }else{
            //!instanceof CCL_PC_Model_Consumer');
            return 0;
        }
        //since it exists does the password match?');
        
        //hash user submit password');
        $hashed = CCL_PC_Model_Consumer::pwhash($this->_password, $this->_consumer->getSalt());
        
        // check the consumer password against the hash value');
        if($this->_consumer->getPassword() == $hashed){
            $status = 1;  // password matches! success!');
        }else{
            $status = 0;  // password does not match! Fail!');
            //hash value='.$hashed);
            //$this->_consumer->getPassword()='.$this->_consumer->getPassword());
        }
        // since the user exists and the password matches then success! '.$status);
        
        // return true or id?');
        if($returnType =='id'){
            $status = $this->_consumer->getId();
        }
        return $status;
    }

    /**
     * ValidateUser->member() function 
     * 
     * Description:  this is to check an email / password combo against member tables
     * TODO:: document this function
     */
    public function member($email=null, $password=null) {
        //setup member setup defaults');
        ($email == null ? :$this->_email = $email); // if $email is set pass it to the global variable 
        ($password == null ? :$this->_password = $password); // if $password is set pass it to the global variable
        
        // check to see if the Member exists
        if($this->getMember() == false){
          return 0;  
        }
        
        $userValidation = $this->_member->checkPassword($this->_password);
        // return status of SS validation
        //user validation 2 '. $userValidation->valid());
        
        
        if( $userValidation->valid() == 1){
            return 1;
        }else{
            return 0;
        }
    }
}
