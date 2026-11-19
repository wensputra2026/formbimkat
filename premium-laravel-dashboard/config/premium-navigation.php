<?php

return [
    'brand' => [
        'name' => env('APP_NAME', 'Nama Aplikasi'),
        'short' => 'PA',
        'description' => 'Workspace Administrasi',
    ],

    'navigation' => [
        [
            'section' => 'UTAMA',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'layout-dashboard'],
            ],
        ],
        [
            'section' => 'DATA & OPERASIONAL',
            'items' => [
                [
                    'label' => 'Data Master',
                    'icon' => 'database',
                    'children' => [
                        ['label' => 'Data Referensi', 'route' => 'dashboard', 'icon' => 'files'],
                        ['label' => 'Kategori', 'route' => 'dashboard', 'icon' => 'tags'],
                    ],
                ],
                [
                    'label' => 'Pengelolaan',
                    'icon' => 'folder-kanban',
                    'children' => [
                        ['label' => 'Daftar Data', 'route' => 'dashboard', 'icon' => 'list'],
                        ['label' => 'Tambah Data', 'route' => 'dashboard', 'icon' => 'plus-circle'],
                    ],
                ],
                ['label' => 'Laporan', 'route' => 'dashboard', 'icon' => 'chart-no-axes-combined'],
            ],
        ],
        [
            'section' => 'ADMINISTRASI',
            'items' => [
                [
                    'label' => 'Pengguna & Akses',
                    'icon' => 'users',
                    'roles' => ['SUPER_ADMIN', 'ADMIN'],
                    'children' => [
                        ['label' => 'Pengguna', 'route' => 'dashboard', 'icon' => 'user-round'],
                        ['label' => 'Peran & Hak Akses', 'route' => 'dashboard', 'icon' => 'shield-check'],
                    ],
                ],
                ['label' => 'Pengaturan', 'route' => 'dashboard', 'icon' => 'settings'],
            ],
        ],
    ],
];
