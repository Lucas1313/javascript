<?php
/**
 * UserCommon class exposes static methods to handle user account
 * cloning and updates.
 *
 * @author Matt Ernst matt.ernst -at- clorox.com
 * @package cloroxModule.code
 */

class UserCommon {

    /**
     * UpdateUser function.
     * This function is called when the user logs in to Silverstripe in order
     * to make sure that the SS Member is synced with the authoritative data
     * from PC Consumer. If $updatePassword is true, also sync password and
     * salt. The $updatePassword should only be true if the SS Member
     * did not exist prior to update.
     *
     * @param CCL_PC_Model_Consumer $consumer - PC consumer to update into SS
     * @param Member $member - Silverstripe member to update
     * @param bool $updatePassword
     * @return Member
     */

    public static function UpdateUser($consumer, $member, $updatePassword=false) {
        $attributes = array(
            'FirstName' => $consumer->getFirstName(),
            'Surname' => $consumer->getLastName(),
            'Email' => $consumer->getEmailAddress(),
            'pc_consumer_id' => $consumer->getId()
        );

        if ($updatePassword) {
            //set encryptor to None so it stores PW/salt data unchanged
            Config::inst()->update(
                'PasswordEncryptor', 'encryptors',
                array(
                    'ccl' => array(
                        'PasswordEncryptor_None' => null
                    )
                )
            );

            $attributes['Password'] = $consumer->getPassword();
            $attributes['Salt']     = $consumer->getSalt();
        }

        $member->castedUpdate($attributes);
        $member->write();
        
        return $member;
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

    public static function CloneUser($consumer, $password) {
        $hashed = CCL_PC_Model_Consumer::pwhash($password, $consumer->getSalt());

        // if consumer authentication failed, return null instead of clone
        if ($hashed != $consumer->getPassword()) {
            return null;
        }
        
        $member = new Member();
        $member = UserCommon::UpdateUser($consumer, $member, 
										 $updatePassword=true);
        
        // Add the member to group. (Check if it exists first)
        $group = Group::get()->filter('Title', 'Basic Members')->First();
        
        if( $group ) {
            $member->Groups()->add($group);
        }
        
        return $member;
    }

}