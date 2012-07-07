/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

// Register a templates definition set named "default".
CKEDITOR.addTemplates( 'default',
{
    // The name of sub folder which hold the shortcut preview images of the
    // templates.
    imagesPath : CKEDITOR.getUrl( CKEDITOR.plugins.getPath( 'templates' ) + 'templates/images/' ),

    // The templates definitions.
    templates :
    [
    {
        title: 'Blog Posting',
        image: 'template2.gif',
        description: 'A template with a <!--READMORE--> marker after the first paragraph.',
        html:
        '<h3>' +
    'Claritatem parum soluta'+
    '</h3>' +
    '<p>'+
    'Ex feugait processus est veniam sit. Qui ut typi consequat nobis elit. Liber facer elit delenit nunc consequat. Parum augue in minim vero amet. Te qui ut per molestie notare.'+
    '</p><!--READMORE-->'+
    '<p>'+
    'Ex feugait processus est veniam sit. Qui ut typi consequat nobis elit. Liber facer elit delenit nunc consequat. Parum augue in minim vero amet. Te qui ut per molestie notare.'+
    '</p>'
    },
    {
        title: 'Headline, Text & Image (left)',
        image: 'template1.gif',
        description: '',
        html:
        '<h1>' +
    'Claritatem parum soluta'+
    '</h1>' +
    '<p>' +
    '<img class="imageOnLeft" width="200" src="/p2/p2File/image/id/0/preset/small" />' +
    'Ut illum consequat vero delenit minim. Est sollemnes quis quinta nunc ut. Ii imperdiet claritas nobis laoreet quis. Nulla congue veniam hendrerit molestie id. Aliquam est id feugiat minim dolor.' +
    '</p><p>'+
    'Saepius zzril tincidunt me decima enim. Claritatem parum soluta fiant quam claram. Hendrerit veniam amet sollemnes nam nibh. Et ea iusto claram Investigationes quis. Est blandit modo ut et facilisi.'+
    '</p><p>'+
    'Ex feugait processus est veniam sit. Qui ut typi consequat nobis elit. Liber facer elit delenit nunc consequat. Parum augue in minim vero amet. Te qui ut per molestie notare.'+
    '</p><p>'+
    '</p>'
    },
    {
        title: 'Headline, Text & Image (right)',
        image: 'template1.gif',
        description: '',
        html:
        '<h1>' +
    'Claritatem parum soluta'+
    '</h1>' +
    '<p>' +
    '<img class="imageOnRight" width="200"  src="/p2/p2File/image/id/0/preset/small" />' +
    'Id nunc possim non est claram. Tation ad consuetudium illum nam processus. Qui hendrerit consequat consequat facilisi legentis. Luptatum me imperdiet vel Investigationes feugait. Eodem nihil claritatem liber veniam ut. '+
    '</p><p>'+
    'Nostrud euismod typi duis quod te. Suscipit adipiscing id mutationem qui congue. Habent lobortis consequat notare in iis. Seacula lius velit eu litterarum vel. Imperdiet iriure parum aliquam ut claritatem. '+
    '</p><p>'+
    'Litterarum eorum eodem wisi erat consectetuer. Suscipit eodem est amet veniam eum. Nobis dolore hendrerit duis in nulla. Eodem accumsan nisl litterarum claritas quinta. Imperdiet euismod processus saepius duis minim. '+
    '</p>'
    },
    {
        title: 'Two Columns with Headline',
        image: 'template2.gif',
        description: 'A template that defines two colums, each one with a title, and some text.',
        html:
        '<h1>' +
    'Claritatem parum soluta'+
    '</h1>' +
    '<table class="twoColumns" cellspacing="0" cellpadding="0" style="width:100%" border="0">' +
    '<tr valign="top">' +
    '<td style="width:100px">' +
    '<img  width="216" class="imageOnLeft"  src="/p2/p2File/image/id/0/preset/small" />' +
    '</td>' +
    '<td style="">' +
    '<h3>Title 1</h3>' +
    '<p>'+
    'Ex feugait processus est veniam sit. Qui ut typi consequat nobis elit. Liber facer elit delenit nunc consequat. Parum augue in minim vero amet. Te qui ut per molestie notare.'+
    '</p>'+
    '</td>' +
    '</tr>' +
    '<tr valign="top">' +
    '<td style="width:100px">' +
    '<img  width="216" class="imageOnLeft"  src="/p2/p2File/image/id/0/preset/small" />' +
    '</td>' +
    '<td style="">' +
    '<h3>Title 1</h3>' +
    '<p>'+
    'Ex feugait processus est veniam sit. Qui ut typi consequat nobis elit. Liber facer elit delenit nunc consequat. Parum augue in minim vero amet. Te qui ut per molestie notare.'+
    '</p>'+
    '</td>' +
    '</tr>' +
    '</table>' +
    '<p>' +
    'More text goes here.' +
    '</p>'
    },
   {
        title: 'FAQ',
        image: 'template2.gif',
        description: 'A question with an answer.',
        html:
    '<p>'+
    '<strong>Q: What\'s the question?</strong>'+
    '<br/>'+
    '</p>'
    },

    ]
});
