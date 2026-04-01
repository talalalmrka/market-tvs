<?php

return [
    'fortify' => [
        'guard' => arr_options(array_keys(config('auth.guards'))),
        'passwords' => arr_options(array_keys(config('auth.passwords'))),
    ],
    'logging' => [
        'default' => arr_options(array_keys(config('logging.channels'))),
    ],
    'media-library' => [
        'disk_name' => arr_options(array_keys(config('filesystems.disks'))),
    ],
    'queue' => [
        'default' => arr_options(array_keys(config('queue.connections'))),
    ],
];
