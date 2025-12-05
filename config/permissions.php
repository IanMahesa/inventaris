<?php

return [
    // Order of permission groups (prefix before '-') as they should appear in the Role views
    'groups_order' => [
        'dashboard',
        'role',
        'user',
        'ruang',
        'kategori',
        'aset',
        'histori',
        'rekap',
        'opruang',
        'scanqr',
        'webcam',
        'opnamhistori',
        'brglelang',
    ],

    // Order of permission items within each group (suffix after '-')
    'items_order' => [
        'dashboard'      => ['view'],
        'role'           => ['list', 'create', 'edit', 'delete', 'view'],
        'user'           => ['list', 'create', 'edit', 'delete', 'view'],
        'ruang'          => ['list', 'create', 'edit', 'delete', 'view'],
        'kategori'       => ['list', 'create', 'edit', 'delete', 'view'],
        'aset'           => ['list', 'create', 'edit', 'delete', 'view', 'qrcode', 'download'],
        'histori'        => ['list', 'create', 'edit', 'delete', 'view', 'print'],
        'rekap'          => ['view', 'print'],
        'opruang'        => ['view', 'print'],
        'scanqr'         => ['view', 'create'],
        'webcam'         => ['capture'],
        'opnamhistori'   => ['view'],
        'brglelang'      => ['view'],
    ],
];
