# MIGallery - Image Gallery Script
PHP Photo Gallery Script with multi image uploader, image resizer, image cropper, image rotater and image sorter features.

# Overview

PHP Image Gallery Script with multi-uploading, cropping, rotating and sorting features.
This script has been developed for multiple image uploads. It can be used not only for image gallery but also for multi-image upload for many different types of projects.

Images can be cropped, rotated before uploading. It can be sorted by dragging with the mouse on computers or by finger dragging on mobile devices, and information can be added for each picture.

```
FileAPI javascript tools are used for client side cropping, rotating and uploading images 
(https://github.com/mailru/FileAPI)
```

Uploaded images can be reordered later, new images can be added between them.

After installation, a picture table and a simple gallery table associated with this table are also created in the database.

In order to manage the created image gallery, simple page templates have been prepared.

In order to be easy to integrate into projects, a simple design has been made using bootstrap.

When it is installed, this script automatically creates a gallery as seen in the demonstration in the project directory. There are 4 pages on the project directory of this gallery. These pages are prepared as templates with a simple design for convenience to developers.

```
mi-uploader.php : create and edit gallery, upload and edit images for admin or authorized users
gallery-man.php : gallery management for admin or authorized users
gallery-list.php: list of image galleries for all users
gallery-view.php: view images of gallery for all users
```

You can use these pages by appropriately incorporating them into your project and improving their designs.

It is prepared as a template available to develop for different projects.

The codes are written as clearly and comprehensibly as possible. Necessary explanations have been added to the methods. Error logging and debug mode have been added to make it easier to find errors during the development phase.

# DIRECTORY STRUCTURE

```
/project                : Your project folder
    /bootstrap-...      : bootstrap files
    /css                : css files
    /icons              : icon files
    /js                 : jquery files

    mi-install.php      : Install database tables and create CONFIG_FILE and DEFINITIONS_FILE (defined in the definitions0.php) files.
    mi-session.php      : php session management
    mi-init.php         : mysql connection
    mi-final.php        : close mysql connection
    mi-footer.php       : page footer
    mi-navbar.php       : navigation bar

    mi-uploader.php     : create and edit gallery, upload and edit images for admin or authorized users
    gallery-man.php     : gallery management for admin or authorized users
    gallery-list.php    : list of image galleries for all users
    gallery-view.php    : view images of gallery for all users
    
    /migallery
        definitions0.php: php definitions (default, you can change it manually)
        definitions1.php: php definitions (Default definitions during setup. If you do not change the default paths, the db / definitions.php file will be created after installation.)
        session.php     : php session management
        init.php        : mysql connection
        final.php       : close mysql connection

        uploader-thumb-existing.php    : print existing thumbnails during gallery editing
        uploader-thumb-template.php    : thumbnail html template of browsed and added images
        ajax-gallery-...               : Ajax request files of gallery management
        ajax-image-...                 : Ajax request files of image management

        MIGallery.class.php     : Main class of gallery and image management
        Translator.class.php    : Language translator class
        FileAPI.class.php       : File upload response class
        Pagination.class.php    : Pagination class
    
        install-ajax-...        : Ajax request files of installation

        /css                : css files
        /js                 : javascript files
        /plugins            : some javascript plugins
        /lang               : language files
            /xml            : language xml files
            /js             : language js files
        /db                 : The default directory for writable dynamic content. It can be changed during the installation.
            config.php      : After installation, config.php (default name, can be changed from the definitions0.php) file is created.
            definitions.php : After installation, definitions.php (default name, can be changed from the definitions0.php) file is created.
            error.log       : Errors are written to this file.
            /tmp            : First, images are uploaded here and created full, slide, thumb and thumb_c sizes
                /slide      : slide size with aspect ratio
                /thumb      : thumbnail size with aspect ratio
                /thumb_c    : thumbnail size with cropped
            /img            : Then images move here
                /slide
                /thumb
                /thumb_c
            /xml            : xml files of image informations. It was created to reduce database queries. Used in gallery-view.php file.
        
When the image is uploaded, first full size (not original size, maximum dimensions specified in the installation), thumbnail and slide images are created in the tmp directory. 
After the gallery is saved, the gallery id is taken and a new folder with this name is created in img directory and the images are moved here.
For example, when the file named abcdef123456789.jpg is uploaded, it is moved to the tmp directory.
Let's say gallery info is inserted to database with 128 id. After that, the file is moved to the img/128/128-abcdef123456789.jpg folder.
```

# DEBUGGING

```
If you want to turn on debug mode, set $config['debug'] = true; from CONFIG_FILE file.
```

# Features

- Responsive design 
- Compatible with mobile devices
- Automatic and easy installation
- Multi (88) language support
- Seo friendly
- Clean Code
- Debug mode and error logging
- Sorting galleries by mouse or touch
- Sorting images by mouse or touch
- Multi upload
- Add/edit image info
- Image cropping
- Image rotating
- Image sorting
- Add, substract and reorder images while editing the gallery

# Requirements

- PHP: 5.5+, 7, 8
- MySQL: 4.x, 5.x
- JQuery: 1.7+, 2, 3
- Bootstrap: 4, 5
- Browser Compatibility: Edge, Chrome, Safari, Opera, Firefox

# Instructions

```
Type {your_domain}/project/mi-install.php in the address line and install it.
```

[Live Demo](https://emlakbim.online/demo/)

