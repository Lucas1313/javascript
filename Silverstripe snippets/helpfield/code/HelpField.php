<?php
/**
 * HelpField Module
 * will add a Help system to a header field
 *
 * @author Luc Martin
 * @version $ID
 */
class HelpField extends LiteralField {

    protected $targetField;
    protected $content;
    protected $objectClass;

    // initialize the class
    function __construct($name, $args) {

        $this -> objectClass = $args[0];
        $this -> targetField = $args[1];

        $this -> content = $args[2];
        //error_log('here is the content '.$this->buildField());
        parent::__construct($name,$this->buildField());
    }

    // add the Javascript libraries
    static function include_files() {

        Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
        Requirements::javascript(THIRDPARTY_DIR . '/jquery-livequery/jquery.livequery.js');

        Requirements::javascript('helpfield/javascript/helpfield.js');
        Requirements::css('helpfield/css/helpfield.css');
    }

    // the view for the field
    //TODO use a template to render the view
    function buildField() {
        HelpField::include_files();
        return '<div id="' . $this -> targetField . '_HelpFieldSeed" class="helpFieldSeed"><span class="grayHelpBox">Help for: ' . $this -> objectClass . '  >  ' . $this -> helpFor . ' </span><ul class="helpText">' . htmlentities($this -> content) . '' . $this -> get_field_text() . '</ul></div>';
    }


    /**
     *  function get_field_text
     *  Method to get a csv from Google doc as set in the _config file
     *  parses the doc and return a set of List Items
     */
    function get_field_text() {
        // init
        $ret = '';
        $row = 1;

        // get the doc from Google
        if (($handle = fopen(HELP_GOOGLE_DOC_URL, "r")) !== FALSE) {

            // parse the csv
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                // Iterate the rows
                $num = count($data);
                for ($c = 0; $c < $num; $c++) {

                    // cleanup the data from empty spaces
                    $rowObjectClass = $rowFieldName = str_replace(' ', '', $data[0]);

                    // replaces the all class with the actual class
                    $rowObjectClass = $rowFieldName = str_replace('all', $this -> objectClass, $rowObjectClass);

                    // generates array from the data
                    $objectClassAr = explode(',', $rowObjectClass);

                    // cleanup the field names from empty spaces
                    $rowFieldName = str_replace(' ', '', $data[1]);
                    $fieldNameAr = explode(',', $rowFieldName);

                    // initialize the return instructions
                    $rowInstructions = '';

                    // Grab all the cells with instruction data
                    for ($n = 2; !empty($data[$n]); $n++) {
                        $rowInstructions .= '<li>' . $data[$n] . '</li>';
                    }

                    // if the class and the field names are fitting return the values
                    if (in_array($this -> objectClass, $objectClassAr) && in_array($this -> targetField, $fieldNameAr)) {
                        $ret = $rowInstructions;
                    }
                }
                $row++;
            }
            // close the file free memory
            fclose($handle);
        }
        // return the list items
        return $ret;
    }

}
