# Dilaz Metabox
WordPress custom metabox fields for themes and plugins.

Feel free to use this metabox plugin in your __premium__ and __commercial__ projects(themes or plugins).

![alt text](https://raw.githubusercontent.com/Rodgath/DilazResources/master/Dilaz-Metabox/main-dilaz-metabox.png "Demo Screenshot")

## How to use
1. Download and install [Dilaz Metabox](https://github.com/Rodgath/dilaz-metabox/archive/master.zip) plugin
2. Download [Dilaz Metabox Options](https://github.com/Rodgath/dilaz-metabox-Options) and add it into your WordPress project *(theme or plugin)*.

## Example of how to use Dilaz Panel in a *__theme__*
Download and install [Dilaz Demo Theme](https://github.com/Rodgath/Dilaz-Demo-Theme) to see a useful example on how to integrate this *dilaz panel* into your WordPress theme development project.

## Example of how to use Dilaz Panel in a *__plugin__*
Download and install [Dilaz Demo Plugin](https://github.com/Rodgath/Dilaz-Demo-Plugin) to see a useful example on how to integrate this *dilaz panel* into your WordPress plugin development project.

## Features
* __Fault Tolerant__ - Continues to working effectively even when a component is faulty.
* __Backward Compatible__ - Fairly interoperable with WP older legacy versions and your own option settings.
* __Extendability__ - Easy to update or create new functionality. Future growth considered.
* __Reliability__ - Full operational under stated WP conditions. No surprises.
* __Maintainability__ - Easy to maintain, update, correct defects or repair faulty parts.
* __Easy updating__ - Your settings will not be part of core files.
* __AddOns availability__ - AddOns created by ther software developers.
* __Both Plugins & Themes__ - Can be used with any WordPress theme or plugin.

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
wp-content/plugins/dilaz-metaboxes/  # → Dilaz metaboxes root directory
├── assets/                          # → Assets
│   ├── css/                         # → Stylesheets
│   ├── fonts/                       # → Fonts
│   ├── images/                      # → Images
│   └── js/                          # → JavaScripts
├── inc/                             # → Includes
│   ├── fields.php                   # → Metabox fields (never edit)
│   ├── functions.php                # → Metabox functions (never edit)
│   └── metabox-class.php            # → Metabox class (never edit)
└── dilaz-metabox.php                # → metabox access (never edit)
```

## Download

To get a local working copy of the development repository, do:

    git clone https://github.com/Rodgath/dilaz-metabox.git

Alternatively, you can download the latest development version as a tarball
as follows:

    wget --content-disposition https://github.com/Rodgath/dilaz-metabox/tarball/main

OR

    curl -LJO https://github.com/Rodgath/dilaz-metabox/tarball/main


