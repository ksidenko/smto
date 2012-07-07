<?php
defined('DS') or define('DS',DIRECTORY_SEPARATOR);
return array(
    'aliases' => array(
        'lib' => 'application.vendors'
    ),

    'import' => array(
        'application.modules.p2.behaviors.*',
        'application.modules.p2.components.*',
        'application.modules.p2.components.cellmanager.*',
        'application.modules.p2.extensions.*',
        'application.modules.p2.models.*', // srbac
        'zii.widgets.jui.*', // TODO - hotfix
    ),
    'params' => array(

        /* Data paths and urls: */
        'protectedRuntimePath'  => 'runtime', // processed (i.e. image resize) non-public files, relative to app.basePath
        'protectedDataPath'     => 'data', // where to store (i.e. upload) uploaded files, relative to app.basePath
        'publicRuntimePath'     => '/runtime', // same as above but directory is public (faster, if requested directly), relative to app.basePath
        'publicRuntimeUrl'      => '/runtime', // baseUrl to the directory above
        'publicDataPath'        => '../data', // public upload, be careful, relative to app.basePath
        'publicDataUrl'         => '/data', // baseUrl to the path above

        /* phundament 2 settings */
        #'p2.databaseName' => 'p2.', // if you do cross-database joins with active record, insert your current datbase name here, eg. 'p2.'; all classes derived from P2ActiveRecord will return the table name with database prefix.
        
        /* cell manager */
        // widgets
        'p2.cellManager.availableWidgets' => array(
            'p2.widgets.html.P2HtmlWidget'         => "HTML",
            'p2.widgets.blog.P2BlogWidget'         => "Blog",
            'p2.widgets.submenu.P2SubMenuWidget'   => "Sub Menu",
        ),
        // dialog
        'p2.cellManager.dialog.width'=> '800px',
        'p2.cellManager.dialog.height' => 'auto',
        'p2.cellManager.dialog.position' => 'top',
        'p2.cellManager.dialog.modal' => true,
        'p2.cellManager.dialog.autoOpen'=>false,

        'p2.feed.entry.url' => array(
            'route' => '/site/blog',
        ),

        /* info (meta data) */
        'p2.info.types' => array(
            // types for HTML
            'P2Html' => array(
                'blog'=>'Blog/News',
                'mobile'=>'Mobile'), // type: unknow will be always available
        ),
        
        /* pages */
        'p2.page.availableLayouts' => array(
            'application.views.layouts.main' => 'main'
        ),
        'p2.page.availableViews' =>array(
            '/cms/default' => 'Default',
            '/cms/simple'  => 'Simple',
        ),

        /* files (media) */
        'p2.file.importPaths' => array('data/p2FileImport'),
        'p2.file.imagePresets' => array(
            'default' => array(
                'name' => 'Default 300x300',
                'commands' => array(
                    'resize' => array(300,300),
                    'quality' => 85
                ),
                'savePublic' => true,
                'type' => 'jpg'                            
            ),
            'original' => array(
                'name' => 'Original File & Size',
                'originalFile' => true,
            ),
            'fckbrowse' => array(
                'commands' => array(
                    'resize' => array(150,150),
                    'quality' => 75,
                ),
                'type' => 'png'
            ),
        ),

        /* app settings */
        
        /* 'languages' => array(
            'en_us' => 'English'),*/

        /* Editor may be used without a p2 controller, so we have to define it here: */
        'ckeditor' => array(
            'type'=>'fckeditor',
            'height' => 400,
            'filebrowserWindowWidth' => '990',
            'filebrowserWindowHeight' => '730',
            'resize_minWidth' => '150',

            /* Toolbar */
            'toolbar_Custom' => array(
                array('Templates','-','Maximize','Source','ShowBlocks','-','Undo','Redo','-','PasteText','PasteFromWord'),
                array('JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','NumberedList','BulletedList','Outdent','Indent'),
                array('Table','Blockquote','CreateDiv'),
                '/',
                array('Image','Flash','-','Link','Unlink'),
                array('Bold','Italic','Underline','-','UnorderedList','OrderedList','-','RemoveFormat'),
                array('Format','-','Styles')),
            'toolbar' => "Custom",

            /* Settings */
            'startupOutlineBlocks' => true,
            'pasteFromWordRemoveStyle' => true,
            'pasteFromWordKeepsStructure' => true,
            'templates_replaceContent' => false,
            'forcePasteAsPlainText' => true,

            /* IMPORTANT!!! Editor CSS will not be published as asset, we need relative links there
             * FIXME: ckeditor-3.0.1 can not handle bodyIds or baseUrl */
            'contentsCss' => 'themes/classic/ckeditorBase.css',
            'bodyId' => 'ckeditor',
            'bodyClass' => 'ckeditor',

            /* Assets will be published with publishAsset() */
            'templates_files' => 'modules/p2/config/cktemplates.js',
            'stylesCombo_stylesSet' => 'modules/p2/config/ckstyles.js',

            /* URLs will be parsed with createUrl() */
            'filebrowserBrowseUrl' => '/p2/p2File/ckbrowse',
            'filebrowserImageBrowseUrl' => '/p2/p2File/ckbrowseimage',
            'filebrowserFlashBrowseUrl' => '/p2/p2File/ckbrowseflash',
        //'filebrowserUploadUrl' => null, // can not use, pre-resizing of images
        ),
    /* Complete ckeditor-3.0.1 config:
          * [
         *     ['Source','-','Save','NewPage','Preview','-','Templates'],
         *     ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
         *     ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
         *     ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
         *     '/',
         *     ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
         *     ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
         *     ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
         *     ['Link','Unlink','Anchor'],
         *     ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
         *     '/',
         *     ['Styles','Format','Font','FontSize'],
         *     ['TextColor','BGColor'],
         *     ['Maximize', 'ShowBlocks','-','About']
         * ];
    */
    ),

    /* app module config */
    'modules' => array(
        'p2' => array(
            'import'=>array(
                'application.modules.p2.components.cellmanager.*',
            ),
            'params' => array(),
            'components' => array(
                'mailer' => array(
                    'class' => 'p2.components.P2Mailer',
                ),
            ),
        ),
        'srbac' => array(
            "userclass"=>"P2User", // Your application's user class (default: User)
            "userid"=>"id", // Your users' table user_id column (default: userid)
            "username"=>"name", // your users' table username column (default: username)
            // If in debug mode (default: false)
            // In debug mode every user (even guest) can admin srbac, also
            //if you use internationalization untranslated words/phrases
            //will be marked wi1th a red star
            "debug"=>false,
            "pageSize"=>30, // The number of items shown in each page (default:15)
            "superUser" =>"admin", // The name of the super user
            "layout"=>"column1",
            ),
    ),

    'components' => array(
        'user'=>array(
        // enable cookie-based authentication
            'class'=>'P2WebUser',
            'allowAutoLogin'=>true,
        ),
        'cache' => array(
            'class' => 'CDummyCache',
        ),
        'authManager'=>array(
            'class'=>'CDbAuthManager', // The type of Manager (Database)
            'connectionID'=>'db', // The database connection used
            'itemTable'=>'p2_auth_item', // The itemTable name (default:authitem)
            'assignmentTable'=>'p2_auth_assignment', // The assignmentTable name (default:authassignment)
            'itemChildTable'=>'p2_auth_item_child', // The itemChildTable name (default:authitemchild)
        ),
        'image'=>array(
            'class'=>'application.modules.p2.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
            'params'=>array('directory'=>'/opt/local/bin'),
        ),
    ),
);

?>
