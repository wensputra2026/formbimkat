<?php

return [
    'brand' => [
        'name' => 'Pendataan Guru Kemenag',
        'short' => 'PG',
        'description' => 'Kementerian Agama RI',
    ],

    'navigation' => [
        [
            'section' => 'UTAMA',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'layout-dashboard'],
            ],
        ],
        [
            'section' => 'DATA GURU',
            'items' => [
                [
                    'label' => 'Data Guru',
                    'icon' => 'users',
                    'children' => [
                        ['label' => 'Semua Data Guru', 'url' => '/admin/guru?tab=semua', 'icon' => 'list'],
                        ['label' => 'Data Menunggu Verifikasi', 'url' => '/admin/guru?tab=pending', 'icon' => 'shield-check'],
                        ['label' => 'Data Disetujui', 'url' => '/admin/guru?tab=approved', 'icon' => 'files'],
                        ['label' => 'Data Ditolak', 'url' => '/admin/guru?tab=rejected', 'icon' => 'folder-kanban'],
                        ['label' => 'Arsip Data', 'url' => '/admin/guru?tab=trash', 'icon' => 'database'],
                    ],
                ],
            ],
        ],
        [
            'section' => 'MASTER DATA',
            'items' => [
                ['label' => 'Master Sekolah', 'route' => 'admin.sekolah.index', 'icon' => 'database'],
                ['label' => 'Master Pangkat/Gol.', 'route' => 'admin.pangkat.index', 'icon' => 'tags'],
            ],
        ],
        [
            'section' => 'LAPORAN & AUDIT',
            'items' => [
                ['label' => 'Laporan & Export Excel', 'route' => 'admin.report.index', 'icon' => 'chart-no-axes-combined'],
                ['label' => 'Riwayat Login', 'route' => 'admin.audit.login', 'icon' => 'user-round'],
                ['label' => 'Log Aktivitas', 'route' => 'admin.audit.activity', 'icon' => 'files'],
            ],
        ],
        [
            'section' => 'SISTEM',
            'items' => [
                ['label' => 'Pengaturan Aplikasi', 'route' => 'admin.setting.index', 'icon' => 'settings'],
            ],
        ],
    ],
];
