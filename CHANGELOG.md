# CHANGELOG

## Version 3

###### v3.3.0
```
= ADDED - NEW FEATURE: Info block field
= FIXED - Conditional field output escape using wp_keses_post()
```

###### v3.2.12
```
= ADDED - Recursive function to check fields for a specific type
```

###### v3.2.11
```
= FIXED - Remove hidden overflow from option group item
```

###### v3.2.1
```
= FIXED - Color picker layout
```

###### v3.2.0
```
= ADDED - NEW FEATURE: Option-group field
```

###### v3.1.22
```
= FIXED - Ensure $input is never null
= FIXED - Ensure not to use null values in strtotime(). PHP 8.1+ no longer allows that.
```

###### v3.1.21
```
= FIXED - 'select2single' field layout padding issue
```

###### v3.1.2
```
= FIXED - Compiled CSS
```

###### v3.1.1
```
= FIXED - 'select2multiple' field layout
```

###### v3.1.0
```
= FIXED - Date time field
= IMPORVED - File upload field attachment ID and URL handling
= ADDED - Support for SVG image file upload
```

###### v3.0.0
```
= ADDED - PHP Namespace to fix conflict issues when multiple themes/plugins are using Dilaz Matabox in the same WP installation
```

## Version 2

###### v2.5.83
```
= FIXED - Layout issues
= IMPORVED - Select2 input fields layout
= ADDED - Append plugin's custom classes to admin body tag.
= ADDED - Gulp bundler
```
###### v2.5.82
```
= FIXED - 'Missing parameter' warning by adding optional argument similar to constructor
= FIXED - Move 'loadGoogleFonts' function to main class
= FIXED - Save Google fonts only, ignore other fonts
= FIXED - If key not found, return the original array: This removes the possibility of $key_offset being a bool value
= ADDED - Trigger a Warning to notify devs about the invalid key used in custom field insertion
```
###### v2.5.81
```
= FIXED - Color picker layout
= FIXED - metabox tabs layout
= UPDATED - Material design icons to v6.5.95
= UPDATED - Add compatibility to WordPress 6.x
```
###### v2.5.8
```
= UPDATED - changed update checker path
```
###### v2.5.7
```
= FIXED: Fatal error: Uncaught Error: [] operator not supported for strings in PHP 7.1
= FIXED: fieldRadioImage: input field has 2 class attributes
= ADDED: Typography field
```
###### v2.5.6
```
= ADDED - Update checker
```
###### v2.5.5
```
= ADDED - Default standard options for option fields
```
###### v2.5.4
```
= FIXED - 'multitext' field sanitize option bug
= FIXED - 'multiselect' field sanitize option bug
= FIXED - 'queryselect' field sanitize option bug
= FIXED - 'range' field sanitize option bug
= FIXED - 'multicheck' field sanitize option bug
= FIXED - 'repeatable' field sanitize option bug
= FIXED - 'multicolor' field sanitize option bug
= FIXED - 'upload' field sanitize option bug
```
###### v2.5.3
```
= ADDED - WP Colorpicker alpha channel
= IMPROVED - Color sanitize for all color fields
```
###### v2.5.2
```
= REMOVED - Font Awesome webfont icons for tab icons
= ADDED - Material Design webfont icons for tab icons
```
###### v2.5.1
```
= IMPROVED - Scripts and styles version caching
```
###### v2.5.0
```
= ADDED - compatibility with WordPress version 5.x
= FIXED - metabox tab navigation minimum height error
= FIXED - checkbox CSS styling
= FIXED - jQuery UI sortable elements flying off screen with revert=true
```
###### v2.4.3
```
= FIXED - remove uploaded media file item '.dilaz-mb-remove-file'
```
###### v2.4.2
```
= FIXED - Multiple files field 'drag and drop' not working
```
###### v2.4.1
```
= FIXED - repeatable field
= IMPROVED - repeatable field
```
###### v2.4.0
```
= IMPROVED - Method, variable and array naming conventions for consistency purposes
= FIXED - Metabox options (with metabox prefix) but are outside the options file will no longer be deleted.
```
###### v2.3
```
= ADDED - Repeatable field
```
###### v2.2
```
= ADDED - Multitext field
= FIXED - text field display error
```
###### v2.1
```
= ADDED - $metabox_args as the only parameter in 'DilazMetabox' class
= ADDED - $prefix, $meta_box and $parameters as parameters in 'Dilaz_Meta_Box' class
= ADDED - preparePrefix() method in 'DilazMetaboxFunction' class
= IMPROVED - 'dilaz_meta_box_filter' and added 3 parameters
= REMOVED - 'inc/config-sample.php' file
= REMOVED - 'options/' directory
= REMOVED - 'options/custom-options-sample.php' file
= REMOVED - 'options/default-options.php' file
= REMOVED - 'options/options-sample.php' file
```
###### v2.0
```
Now its a plugin.
```
## Version 1
###### v1.0
```
Initial release.
