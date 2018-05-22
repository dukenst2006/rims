<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 5/18/2018
 * Time: 11:22 AM
 */
/**
 * Image Settings
 *
 * Holds all image based settings such as path, size, types, ratio
 */

return [
    'path' => [

        /**
         * Avatar
         */
        'avatar' => [
            'absolute' => public_path($relative = 'img/avatars'),
            'relative' => $relative
        ],

        /**
         * Portfolio
         */
        'portfolio' => [
            'absolute' => public_path($relative = 'img/portfolio'),
            'relative' => $relative
        ],
    ],


];
