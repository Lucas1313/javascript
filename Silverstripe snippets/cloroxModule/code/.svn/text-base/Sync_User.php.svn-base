<?php
	/**
	 * 
	 */
class SyncUser {
	private $_member;
	private $_consumer;
	private $_password;
	
	function __construct($consumer=null, $password=null) {
		if($consumer==null){
			
		}else{
		    $this->setConsumer($consumer);
            $this->_password=$password;
 	     $this->CreateMemberFromConsumer();
		}
	}
	public function setMember($member){
	    if($member instanceof CLSS_Member){
	       $this->_member = $member;   
	    }else{
	        return false;
	    }
	}
    public function setConsumer($consumer){
        if($consumer instanceof CCL_PC_Model_Consumer){
           $this->_consumer = $consumer;   
        }else{
            return false;
        }
    }
    public function getMember(){
        return $this->_member;
    }
    public function getConsumer(){
        return $this->_consumer;
    }
	/**
     * CreateMemberFromConsumer function.
     * This function is called to duplicate a PC Consumer into a Silverstripe
	 * Member if a user with a PC Consumer account is logging in to 
	 * Silverstripe for the first time.
     * 
     * @param CCL_PC_Model_Consumer $consumer - PC consumer to clone into SS
	 * @param string $password - password to check against account
	 * @return Member
     */

	public function CreateMemberFromConsumer($consumer=null, $password=null) {
        // setup defaults
        ($consumer == null ? :$this->consumer = $consumer); // if $consumer is set pass it to the global variable 
        ($password == null ? :$this->password = $password); // if $password is set pass it to the global variable
        
        // assume pc_consumer is valid and ss member is not');
		$this->_member = new Member;
		
        //set encryptor to None so it stores PW/salt data unchanged');
        Config::inst()->update(
                                'PasswordEncryptor', 
                                'encryptors',
                                array('ccl' => array('PasswordEncryptor_None' => null))
                                );
        $attributes = array('FirstName' => $this->_consumer->getFirstName(),
                            'Surname' => $this->_consumer->getLastName(),
                            'Email' => $this->_consumer->getEmailAddress(),
                            'Password' => $this->_consumer->getPassword(),
                            'Salt' => $this->_consumer->getSalt(),
                            'pc_consumer_id' => $this->_consumer->getId()
                            );
        $this->_member->castedUpdate($attributes);
        if(strlen($this->_consumer->getEmailAddress())>4){
        $this->_member->write();    
        
		$group = Group::get()->filter('Title', 'Basic Members')->First();
		if( $group ) { 
            $this->_member->Groups()->add($group);
		}
		}else{
		  //  error_log('no pc consumer to create user from');
		}
	}
}