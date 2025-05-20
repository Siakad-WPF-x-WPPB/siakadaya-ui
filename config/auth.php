<?php

return [
    'defaults' => [
        'guard' => 'web', // Default guard untuk web (bisa jadi admin atau dosen, atau biarkan 'users' jika masih ada)
        'passwords' => 'users', // Default password broker (kita akan tambahkan juga)
    ],

    'guards' => [
        'web' => [ // Guard 'web' standar, bisa diarahkan ke 'users' jika masih ada tabel users umum, atau ke salah satu peran.
            'driver' => 'session',
            'provider' => 'users', // Default Laravel, jika Anda tidak punya tabel 'users' lagi, hapus/ganti ini
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admin', // Provider yang akan kita buat
        ],

        'dosen' => [
            'driver' => 'session',
            'provider' => 'dosen', // Provider yang akan kita buat
        ],

        // Guard untuk API Mahasiswa menggunakan Sanctum.
        // Sanctum secara default akan menggunakan provider dari guard 'web' atau defaultnya.
        // Namun, kita akan membuat login Mahasiswa secara eksplisit menggunakan model Mahasiswa.
        // 'auth:sanctum' akan bekerja dengan model yang menggunakan HasApiTokens dan tokennya valid.
        // Kita bisa mendefinisikan guard khusus jika ingin lebih eksplisit, tapi seringkali tidak perlu
        // jika model Mahasiswa adalah satu-satunya yang menggunakan HasApiTokens dan kita issue token untuknya.
        'mahasiswa_api' => [ // Guard ini kita gunakan saat login untuk API
             'driver' => 'sanctum', // Atau bisa juga 'token' jika tidak menggunakan Sanctum, tapi Sanctum lebih modern
             'provider' => 'mahasiswa',
         ],
    ],

    'providers' => [
        'users' => [ // Provider default, jika masih ada tabel users
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'admin' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'dosen' => [
            'driver' => 'eloquent',
            'model' => App\Models\Dosen::class,
        ],

        'mahasiswa' => [
            'driver' => 'eloquent',
            'model' => App\Models\Mahasiswa::class,
        ],
    ],

    'passwords' => [
        'users' => [ // Jika masih ada tabel users
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admin' => [
            'provider' => 'admin',
            'table' => 'password_reset_tokens', // Bisa share tabel ini atau buat tabel sendiri
            'expire' => 60,
            'throttle' => 60,
        ],
        'dosen' => [
            'provider' => 'dosen',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'mahasiswa' => [ // Mungkin tidak perlu reset password via web untuk mahasiswa API
            'provider' => 'mahasiswa',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];