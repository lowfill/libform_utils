libform_utils
=============

This is a Elgg plugin that adds a battery of improvements to form related views.
  - aditional fields
  - validation framework

For examples take a look of http://yoursite.com/pg/libform

Aditional input fields
----------------------
- comboselect
   http://plugins.jquery.com/project/comboselect
   converts a pulldown in a comboselect field
- autosuggest
   http://loopj.com/2009/04/25/jquery-plugin-tokenizing-autocomplete-text-entry/
   converts any input text field in an autosuggest field. 
   Users and groups suggestion fuctions are provided by default
   
How validation framework works?
-------------------------------
Using the jQuery validator plugin (http://bassistance.de/jquery-plugins/jquery-plugin-validation/)
this plugin add validation features to default 'input' fields.

His use is straightforward just add the 'validate' field to the input view parameters specifying 
what kind of validation do you have and thats it!. 
Of course you couldn't forget to call 'input/validator' after your form to make the magic works. 
If you use the 'input/form' view and mark it with the 'validate' param it do it for you.

For some examples take a look to 'libform/examples/validation.php' view file and check the documentation
for each input file provided by this plugin.

Roadmap
=======
http://www.pivotaltracker.com/projects/72492
