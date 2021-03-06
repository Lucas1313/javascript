<?php
/*
 * CssClasses_Controller
 *
 * Generates list of checkboxes / radio Groups to set classes in Templates
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: CssClasses_Controller.php 29435 2014-02-26 01:27:34Z ksmith $
 *
 * Relationships:
 * one-many =
 * many-one =
 * many-many =
 *
 */
class CssClasses_Controller extends Extension {

    /**
     *   function titleClasses
     *   Generates a list of possible classes as a CheckboxSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function titleClasses($field = null, $returnField = true) {
        $allClasses = array(
            'blockheadline' => 'Block Headline',
            'tiltUp' => 'Tilt Up',
            'tiltDown' => 'Tilt Down',
            'textBlack' => 'Black Text',
            'textYellow' => 'Yellow Text',
            'textCentBlue' => 'Dark Blue (Centennial) Text',
            'textHolidayBlue' => 'Very Dark (Holiday) Text',
            'textDkRed' => 'Dark Red Text',
            'textWhite' => 'White Text',
            'textHuge' => 'Very Large Text (90px) ',
            'textLarge' => 'Large Text (75px)',
            'textMedium' => 'Medium Text (36px)',
            'textSmaller' => 'Small Text (20px)',
            'bgBlack' => 'Black Bg',
            'bgDkgreen' => 'Dark Green Bg',
            'bgDkblue' => 'Dark Blue Bg',
            'bgBlack' => 'Black Bg',
            'bgWhite' => 'White Bg',
            'knockoutText' => 'Knockout text',
            'customKnockoutText' => 'Custom Knockout text',

        );

        asort($allClasses);
        if ($returnField) {
            $cbf = new CheckboxSetField($name = $field, $title = $field, $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    /**
     *   function subTitleClasses
     *   Generates a list of possible classes as a CheckboxSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */

    /*can't recall why we needed this. i think it was part of an early concent. should be commented out or removed --rg 2/7/14
     function subTitleClasses($returnField = true){
     $allClasses = array(
     'TitleBlue' => 'Title Blue',
     'TitleYellow' => 'Title Yellow',
     'CutOutWhite' => 'Cut Out White',
     'CutOutAngled' => 'Cut Out with Angle');
     asort($allClasses);
     if($returnField){
     $cbf = new CheckboxSetField($name = 'Subtitle_Class', $title = 'Subtitle Class', $source = $allClasses);
     return $cbf;
     }
     return $allClasses;
     }
     */

    /**
     *   function backgroundClasses
     *   Generates a list of possible classes as a CheckboxSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function backgroundClasses($returnField = true) {
        $allClasses = array(
        'whiteLines' => 'White lines background',
        'orangeLines' => 'Orange lines background',
        'goldLines' => 'Gold lines background',
        'blueLines' => 'Blue lines background',
        'greenLines' => 'Green lines background',
        'yellowLines' => 'Yellow lines background',
        'aquaLines' => 'Aqua lines background',
        'dkblueLines' => 'Dark blue lines background',
        'ltblueLines' => 'Light blue lines background',
        'blueBottom' => 'Blue bar on bottom',
        'redLines' => 'Red lines background',
        'redBottom' => 'Red lines on bottom',
        'goldBottom' => 'Gold lines on bottom',
        'greenBottom' => 'Green lines on bottom',
        'yellowBottom' => 'Yellow lines on bottom',
        'blueBox' => 'Solid blue background',
        'greenSolidBox' => 'Green solid box'
        );
        asort($allClasses);
        if ($returnField) {
            $cbf = new DropdownField($name = 'Background_Class', $title = 'Background Class', $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    /**
     *   function allScentsClassesmo
     *   Generates a list of possible classes as a CheckboxSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function allScentsClasses($returnField = true) {
        $allClasses = array(
            'no-scent' => 'No Scent',
            'original' => 'Original',
            'lavender' => 'Lavender',
            'citrus' => 'Citrus',
            'lemon' => 'Lemon',
            'orange' => 'Orange',
            'fresh-meadow' => 'Fresh Meadow',
            'orange-fusion' => 'Orange Fusion',
            'serene-clean' => 'Serene Clean',
            'serene-clean-green' => 'Serene Clean',
            'fresh-scent' => 'Fresh Scent',
            'free-clear' => 'Free Clear',
            'rain-clean' => 'Rain Clean',
            'cool-wave' => 'Cool Wave',
            'clean-linen' => 'Clean Linen',
            'soft-scent' => 'Soft Scent',
            'orange-energy' => 'Orange Energy',
            'spring' => 'Spring',
            'forest-dew' => 'Forest Dew',
            'linen' => 'linen',
            'fresh-squeezed-lemon' => 'Fresh Squeezed Lemon',
            'radiant-clean' => 'Radiant Clean',
            'lemon-fresh' => 'Lemon Fresh',
            'classic-clean' => 'Classic Clean'
        );
        asort($allClasses);

        if ($returnField) {
            $cbf = new DropdownField($name = 'Scent', $title = "Select Related Scent from Available Scents", $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    /**
     *   function allScentsClasses
     *   Generates a list of possible classes as a CheckboxSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function ctaClasses($field = null, $returnField = true) {
        $allClasses = array(
        	'none' => 'None',
            'btnCircle' => 'Circle',
            'btnSquare' => 'Square',
            'invisibleButton'=>'Invisible Button (Adds link on transparent button)',
            'AfterVideo'=>'Post Video Link (Add link after Video played)'
        );
        asort($allClasses);

        if ($returnField) {
            $cbf = new DropdownField($name = $field, $title = "Select a style for the CTA", $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    /**
     *   function allScentsClasses
     *   Generates a list of possible classes as a CheckboxSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function bubbleClasses($returnField = true) {
        $allClasses = array(
            'dkblue' => 'Dark Blue',
            'yellow' => 'Yelow',
            'purple' => 'Purple',
            'orange' => 'Orange',
            'ltblue' => 'Light Blue',
            'green' => 'Green',
            'grey' => 'Grey'
        );
        asort($allClasses);

        if ($returnField) {
            $cbf = new DropdownField($name = 'Bubble_Class', $title = "Background color for the bubble at the top of the panel", $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    /**
     *   function circleButtonBgClasses
     *   Generates a list of possible classes as a CheckboxSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function circleButtonBgClasses($returnField = true) {
        $allClasses = array(
            'ltblue' => 'Light Blue',
            'dkRed' => 'Dark Red',
            'dkGreen' => 'Dark Green',
            'limegreen' => 'Lime Green',
            'orange' => 'Orange',
            'dkBlue' => 'Dark Blue'

        );

        asort($allClasses);
        if ($returnField) {
            $cbf = new DropdownField($name = 'Circle_Button_Bg_Classes', $title = 'Background color for circle button', $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    /**
     *   function productCategoryClasses
     *   Generates a list of possible classes as a CheckboxSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function productCategoryClasses($field = null, $returnField = true) {
        $allClasses = array(
            'Cleaning_Disinfecting' => 'Cleaning & Disinfecting',
            'Toilet_Bathroom' => 'Toilet & Bathroom',
            'Doing_Laundry' => 'Doing Laundry'
        );

        asort($allClasses);
        if ($returnField) {
            $cbf = new CheckboxSetField($name = $field, $title = 'Product belongs to Categories:', $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    /**
     *   function SpecialOffersTitleClasses
     *   Generates a list of possible classes as a ComboSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function SpecialOffersTitleClasses($field = null, $returnField = true) {
        $allClasses = array(
            'coupon' => 'Coupon',
            'contest' => 'Contest',
            'download' => 'Download',
            'sweepstakes' => 'Sweepstakes'
        );

        asort($allClasses);
        if ($returnField) {
            $cbf = new DropdownField($name = $field, $title = 'Please Select offer Type:', $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    /**
     *   function SpecialOffersTitleClasses
     *   Generates a list of possible classes as a ComboSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function PressReleaseTitleClasses($field = null, $returnField = true) {
        $allClasses = array(
            'commercials' => 'Commercials',
            'press' => 'Press',
            'media' => 'Media',
            'social' => 'Social',
            'reviews' => 'Reviews',
            'blogs' => 'Blogs'
        );

        asort($allClasses);
        if ($returnField) {
            $cbf = new DropdownField($name = $field, $title = 'Please Select Press
            Release Type:', $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    /**
     *   function SpecialOffersTitleClasses
     *   Generates a list of possible classes as a ComboSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function Social_Media_Classes($field = null, $returnField = true) {
        $allClasses = array(
        	'None' => 'None',
            'Twitter' => 'Twitter',
            'Facebook' => 'Facebook'
        );

        asort($allClasses);
        if ($returnField) {
            $cbf = new DropdownField($name = $field, $title = 'Please Select a Social Media Class for the CTA field(If required)
            Release Type:', $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    /**
     *   function SpecialOffersTitleClasses
     *   Generates a list of possible classes as a ComboSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function Square_Buttons_Colors_Class($field = null, $returnField = true) {
        $allClasses = array(
            'btnBlue' => 'Blue',
            'btnRed' => 'Red',
            'btnGreen' => 'Green'
        );

        asort($allClasses);
        if ($returnField) {
            $cbf = new DropdownField($name = $field, $title = 'Please select a color for the 3 square buttons', $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    /**
     *   function CLTPanel_Colors_Class
     *   Generates a list of possible classes as a ComboSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function CLTPanel_Colors_Class($field = null, $returnField = true) {
        $allClasses = array(
            'Blue' => 'Blue',
            'darkblue' => 'Dark Blue',
            'lightblue' => 'Light Blue',
            'Green' => 'Green',
            'Orange' => 'Orange',
            'pink' => 'Pink',
            'darkpink' => 'Dark Pink',
            'yellow' => 'Yellow',
        );

        asort($allClasses);
        if ($returnField) {
            $cbf = new DropdownField($name = $field, $title = 'Please select a color for Preview Panel', $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    /**
     *   function CLTPanel_Colors_Class
     *   Generates a list of possible classes as a ComboSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function CLTPanel_Ribbons_Class($field = null, $returnField = true) {
        $allClasses = array(
            'Tiffany' => 'Tiffany',
            'JustPinned' => 'JustPinned',
            'EditorsFavorite' => 'EditorsFavorite'
        );

        asort($allClasses);
        if ($returnField) {
            $cbf = new CheckboxSetField($name = $field, $title = 'Please select a type of ribbon that would show on that panel', $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    /**
     *   function CLTPanel_Class
     *   Generates a list of possible classes as a ComboSetField
     *   @autor Luc Martin
     *   @param $returnField default true // will return a cms field or an array if false
     */
    function CLTPanel_Class($field = null, $returnField = true) {
        $allClasses = array(

            'Tip' => 'Tip',
            'Article' => 'Article',
        );

        asort($allClasses);
        if ($returnField) {
            $cbf = new CheckboxSetField($name = $field, $title = 'Please select a Class for that Panel', $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

    function Clt_UseForClasses($field = null, $returnField = true) {
        $allClasses = array(
            'Bathroom' => 'Bathroom',
            'Kitchen' => 'Kitchen',
            'Outdoor' => 'Outdoor',
            'Nursery' => 'Nursery',
            'Multi_Room' => 'Multi Room'
        );

        asort($allClasses);
        if ($returnField) {
            $cbf = new DropdownField($name = $field, $title = 'Please select What "Use for" this room is associated with.', $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }


    function KidPanel_Class($field = null, $returnField = true){
        $allClasses = array(
            'ArtsCrafts' => 'Arts and Crafts',
            'Science'    => 'Wacky Science',
            'Cooking'    => 'Kiddie Cooking',
            'Recipes'    => 'Creative Cooking Recipes',
            'Holiday'    => 'Holiday Activities'
           );

        asort($allClasses);
        if($returnField){
            $cbf = new DropdownField($name = $field, $title = 'Select what panel you want the activity to appear in', $source = $allClasses);
            return $cbf;
        }
        return $allClasses;
    }

	function InterruptBar_Class($field = null, $returnField = true){
		$allClasses = array(
		 'interrupt‎Bar' => 'Default',
		 'dkBlue' => 'Dark Blue',
		 'ltBlue' => 'Light Blue',
		);

        return $allClasses;
	}
/**/

}
