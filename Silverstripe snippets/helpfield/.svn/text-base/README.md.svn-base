SilverStripe Inline Help Field
===================================

Adds a question mark next to the label of another field that expands to show text when clicked.  Can be used to add extra information or instructions without taking up space all the time.

Maintainer Contacts
-------------------
* Luc Martin (Lucas1313@gmail.com)

Requirements
------------
* SilverStripe 3.0

Documentation
-------------

Installation Instructions
-------------------------

1. Place the files in a directory called helpfield in the root of your SilverStripe installation
2. Visit yoursite.com/dev/build to rebuild the database

Usage Overview
--------------

Setup:
1- Create a Google table
a) first column is the ClassName of the Object to whish you want to add a Helpfield ("All") will be associated to all classes
b) Second column is the fieldsName (comma separated) where the text will appear
c) 3th to infinite column will be all List Items for the Help field
d) select: file->publish to web
e) publish as CSV
f) grab the url
g) in the _config.php file add:
define('HELP_GOOGLE_DOC_URL', 'https://docs.google.com/spreadsheet/pub?key=0AkhPuabh7KmbdHUxR2ZwLUtQQjNZcWRlZjd1a2lQWFE&single=true&gid=0&output=csv');

Add helpfield to the CMS:
1- Add a header field:  $fields -> addFieldToTab('Root.PinesolProducts', new HeaderField('PineSolProductsHeader', 'This is where you set all the Pine Sol Products'));
2- Generate the Help field:  new HelpField(__CLASS__, 'PineSolProductsHeader', 'Help for Product', '');
