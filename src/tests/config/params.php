<?php
return [
    'thumbs' => [
        'icon' => [
            'w' => 50,
            'h' => 50,
            'q' => 50,
            'slug' => 'icon'
        ],
        'small' => [
            'w' => 320,
            'h' => 320,
            'q' => 50,
            'slug' => 'small'
        ],
        'low' => [
            'w' => 640,
            'h' => 640,
            'q' => 50,
            'slug' => 'low'
        ],
        'normal' => [
            'w' => 1024,
            'h' => 1024,
            'q' => 50,
            'slug' => 'normal'
        ]
    ],
    'images_ext' => [
        'jpg',
        'png',
        'bmp',
        'gif'
    ],
    'use_file_name' => true,
    'use_queue' => false,
    'file_not_founded' => '14',
    //'file_not_founded' => 'http://img.domain.loc/files/1.jpg'
];