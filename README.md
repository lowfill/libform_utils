# libform_utils

This is a Elgg plugin that adds a battery of improvements to form related views.

* Aditional fields
* Validation framework

For examples take a look of http://yoursite.com/pg/libform

Aditional input fields
----------------------

* [comboselect](http://plugins.jquery.com/project/comboselect)
   converts a pulldown in a comboselect field
* [autosuggest](http://loopj.com/2009/04/25/jquery-plugin-tokenizing-autocomplete-text-entry/)
   converts any input text field in an autosuggest field. 
   Users and groups suggestion fuctions are provided by default
   
How validation framework works?
-------------------------------
This plugin uses [jQuery validator](http://bassistance.de/jquery-plugins/jquery-plugin-validation/) plugin to add validation features to default 'input' fields.

To use it, just add the 'validate' field to the input view parameters specifying 
what kind of validation you wants. 
Of course you couldn't forget to call 'input/validator' after your form to make the magic works. 
If you use the 'input/form' view and mark it with the 'validate' param it do it for you.

For some examples take a look to 'libform/examples/validation.php' view file and check the documentation
for each input file provided by this plugin.
