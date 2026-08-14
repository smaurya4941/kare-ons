<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary URL
    |--------------------------------------------------------------------------
    |
    | Full connection string in the form:
    | cloudinary://<api_key>:<api_secret>@<cloud_name>
    |
    */

    'url' => env('CLOUDINARY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Upload folder prefix
    |--------------------------------------------------------------------------
    |
    | All uploads are namespaced under this folder in the Cloudinary media
    | library so this app's assets stay separate from anything else on the
    | same Cloudinary account.
    |
    */

    'folder' => env('CLOUDINARY_FOLDER', 'kareons'),

];
