# Dilaz-Metaboxes
WordPress metaboxes for themes and plugins.

## Features
* Extendability - Easy to update or create new functionality 
* Easy updating - Your settings will not be part of core files
* AddOns availability - AddOns created by other developers

## Metabox Fields
* Text
* Password
* Hidden
* Paragraph
* URL
* Email 
* Number 
* Stepper
* Code
* Textarea
* WordPress Editor
* Radio Select
* Checkbox
* Multicheck/Multiple Checkboxes
* Dropdown Select
* Multiselect/Multiple Select
* Post Select
* Term/Taxonomy/Category Select
* User Select
* Timezone Select
* Image Select
* Color Picker
* Multiple Color Picker
* Date Select
* Date Select - *(From-To)*
* Month Select
* Month Select - *(From-To)*
* Time Select
* Time Select - *(From-To)*
* Date & Time Select
* Date & Time Select - *(From-To)*
* Slider Select
* Range Slider Select
* File Upload - *Image, Audio, Video, Document, Spreadsheet, Interactive, Text, Archive, Code*
* Button & Buttonset
* Switch Buttons


## File Structure
```
your-directory/your-metaboxes-folder/    # → Root of your metaboxes
├── assets/                              # → Assets
│   ├── css/                             # → Stylesheets
│   ├── fonts/                           # → Fonts
│   ├── images/                          # → Images
│   └── js/                              # → JavaScripts
├── inc/                                 # → Includes
│   ├── config-sample.php                # → Sample config file - Rename to config.php
│   ├── fields.php                       # → Metabox fields (never edit)
│   ├── functions.php                    # → Metabox functions (never edit)
│   └── metabox-class.php                # → Metabox class (never edit)
├── options/                             # → Metabox options
│   ├── custom-options-sample.php        # → Sample custom options - Rename to custom-options.php
│   ├── default-options.php              # → Default options (never edit)
│   └── options-sample.php               # → Sample options file - Rename to options.php
└── metabox.php                          # → metabox access (never edit)
```



