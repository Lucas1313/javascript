<?php
/*
 * RelationshipImport_Controller
 *
 * Helper for csv imports will generate relationships using a Text with comma separated values
 * Updates the Relationships TextField when Relationship is deleted
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: Relationship_Controller.php 26917 2013-11-05 02:09:30Z ksmith $
 *
 * Relationships:
 * one-many =
 * many-one =
 * many-many =
 *
 */
class Relationship_Controller  extends Extension {
    
 
    /**
     * function updateRelationshipField
     * Method to synchronize the csv relationships field to the actual relationships in of the object
     * deletes the relationship in the text field if the object is unlinked or deleted
     * adds a relationship in the text if an object is added to the relationship
     * 
     * @param &$target, The master object that has the field
     * @param $listField, The csv field were we store the relationships as text
     * @param $targetRelationshipObj, The relationship Object
     * @param $codeName, The field we use to store the relationship
     * @param $write = false write to the object or delay the writing
     */
    public function updateRelationshipField(&$target, $listField, $targetRelationshipObj, $codeName, $write = false) {

        $allObjectsAr = array();

        foreach ($targetRelationshipObj as $k => $relationshipObj) {
            
            //error_log('Relationship with '.$relationshipObj -> $codeName);
            $allObjectsAr[] = $relationshipObj -> $codeName;
            
        }
        if ($write) {
            
            $target -> $listField = implode(',', $allObjectsAr);
            $target -> write();
            
        }
        return implode(',', $allObjectsAr);
    }

    /**
     * Method used while importing/exporting  csv in the Product Management tab, it will add all related Relationships to a text field
     * it will also add a relationship with Relationships added to the AllRelationships field from a csv import
     *
     * @param $caller DataObject -- the master Object
     * @param $listField TextField -- the field that hold the relationship references
     * @param $localRelationshipObject List -- The $caller related Objects
     * @param $relationshipObject Query DataObjects -- relate to a DataObject type
     * @param $toAdd DataObject -- The new object to add to the relationship.
     * @return void
     */
    public function writeRelationshipFromCsv(&$target, $listfield, $targetRelationshipObj, $targetObjectToAdd, $identifier = 'Code_Name') {
        //error_log('RELATIONSHIP CREATION');
        $instanceOfTargetObjectToAdd = new $targetObjectToAdd;

        // Init a new array for testing existing relationship
        $actualRelationshipsCodenamesAr = array();

        // fill up array using the codeNames from all existing relationships
        foreach ($targetRelationshipObj as $key => $value) {

            $actualRelationshipsCodenamesAr[] = $value -> $identifier;
            //error_log('Actual relationships '.$value -> $identifier);

        }

        // Get the already already related Relationship code names
        $alreadySetRelationships = explode(',', $target -> $listfield);
        //Test if there is any relationship to add
        if (count($alreadySetRelationships) > 0) {

            // Iterate trough all related Relationships
            foreach ($alreadySetRelationships as $key => $value) {

                // cleanup the strings
                $value = str_replace(' ', '', $value);

                // Check if the associated Relationship is in the local text field
                if (!in_array($value, $actualRelationshipsCodenamesAr) && !empty($value)) {

                    // now we need to add the Relationships
                    // First check if the Object exists in the database using the codeName
                    $alreadyExistingObject = $instanceOfTargetObjectToAdd -> get() -> filter(array($identifier => $value)) -> First();

                    // No result means that the Object doesn't exist
                    if (empty($alreadyExistingObject)) {
                        // create a new $relationshipObject because there is none of that name
                        $objectToAdd = new $targetObjectToAdd;
                        $objectToAdd -> codeName = $value;
                        $objectToAdd -> Name = $value;

                        // write to db
                        $objectToAdd -> write();

                        // add relationship
                        $targetRelationshipObj -> add($objectToAdd);

                    }
                    else {
                        // The Object already exists so we just add a relationship
                        $alreadyExistingObject -> write();
                        $targetRelationshipObj -> add($alreadyExistingObject);

                    }
                }
            }
        }

        $target -> $listfield = implode(', ', $actualRelationshipsCodenamesAr);
    }

}
