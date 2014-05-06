<?php
/*
 * StringManipulator_Controller
 *
 * Controller that manipulates Strings
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: StringManipulator_Controller.php 29240 2014-02-18 22:54:47Z lmartin $
 *
 * Relationships:
 * one-many =
 * many-one = Products
 * many-many =
 *
 */
class StringManipulator_Controller extends Extension {
    /**
     * function generatecodeName
     * Method to Generate a code name for the Product
     * Necessary because some products are not coming from the API
     * This method should remove all special characters and spaces then remove all cap letters
     *
     * @access public
     * @return String a string without spaces or special characters
     */
    public function generateCodeName($str) {

        $str = $this -> removeAllSpecialCharacters($str);
       
        $str = preg_replace('/([^A-Za-z0-9])/', "", $str);
        $str = strtolower($str);

        return $str;
    }

    /**
     * function cleanupNamesFromApi
     *
     *
     * Used to break the API Product names into Product -> ProductSubCategory relationship
     *
     * @access public
     * @param $str String
     * @return array using Product as an index and ProductSubCategory as items
     */
    //TODO would be very sweet to built a Regex that does all this in one swing
    public function breakupProductName($str) {

        // Replace all separators used in the db by a "parentheses"
        // prepares the array for an explode
        // we need to replace all the silly characters used in the API by a single consistent one
        //TODO ask Rubein for API naming conventions

        $str = str_replace('-', '(', $str);
        $str = str_replace(',', '(', $str);
        $str = str_replace('[', '(', $str);
        $str = str_replace(')', '', $str);
        $str = str_replace(']', '', $str);

        $exploded = explode('(', $str);

        $legalName = $this -> cleanupSpecialChar($exploded[0]);
        //The product name will always be the first item
        $productName = $this -> removeAllSpecialCharacters($exploded[0]);

        //Replace all parentheses
        $tempName = str_replace('(', ' ', $str);
        $ProductSubCategoryDisplayName = $this -> removeAllSpecialCharacters($tempName);

        return array(
            'productName' => $productName,
            'ProductSubCategoryDisplayName' => $ProductSubCategoryDisplayName,
            'legalName' => $legalName
        );
    }

    /**
     * function cleanupSpecialChar
     *
     * Generates a product name with special characters
     * Will runn only if the urlencoded "&reg;" is present
     * Necessary due to inconsistencies in the database API
     * Some names have mixed encoded and decoded special characters
     *
     * @access public
     * @param $str String
     * @return String (Cleaned up from special Character if it was necessary)
     */
    public function cleanupSpecialChar($str) {

        $found = strchr($str, '&reg;');

        if ($found == true) {
            $str = html_entity_decode($str);
        }
        return $str;
    }
    /**
     * function generateUrlCompatibleName
     *
     * Generates a URL without special characters
     * Will repace spaces for dashes
     *
     * @access public
     * @param $str String
     * @return String (Cleaned up from special Character and spaces)
     */
    public function generateUrlCompatibleName($str){
        $str = $this->cleanupSpecialChar($str);
        $str = str_replace(' ','-',$str);
        return $str;
    }
    /**
     * Method to normalize and remove special characters from a string
     * Necessary because some products names coming from the API have special characters and others don't
     *
     *
     * @param $str String
     * @access public
     * @return String
     */
    //TODO would be very sweet to built a Regex that does all this in one swing

    public function removeAllSpecialCharacters($str) {
        //make sure that all special characters are decoded
        //necessary because in the Clorox DB some names already have special characters and other are already decoded
        $str = html_entity_decode($str);

        //Re-encode special characters
        $str = htmlentities($str);

        //Cleanup, kill them all
        $str = str_replace('&reg;', ' ', $str);
        $str = str_replace('&trade;', ' ', $str);
        $str = str_replace(' ', ' ', $str);
        $str = str_replace('-', ' ', $str);
        $str = str_replace('&ndash;', ' ', $str);
        $str = str_replace('&amp;', ' ', $str);
        $str = str_replace('&lt;sub&gt;1&lt;/sub&gt;','1',$str);
        // replace double spaces by single spaces, this for search function in the datagrids
        $str = str_replace('  ', ' ', $str);
        $str = str_replace('  ', ' ', $str);
        $str = str_replace('   ', ' ', $str);
        $str = str_replace('    ', ' ', $str);
        return $str;
    }

    public function init() {

        parent::init();

    }

}
