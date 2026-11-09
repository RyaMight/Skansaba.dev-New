<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Alumni Sorotan — Section "Sekilas RPL"
    |--------------------------------------------------------------------------
    |
    | Daftar ID alumni yang tampil sebagai sorotan di beranda. Urutan array
    | menentukan prioritas. Kosongkan untuk memakai fallback otomatis
    | (alumni published dengan profil paling lengkap).
    |
    | Catatan: sengaja tidak memakai kolom is_featured agar tidak perlu
    | migration (menghindari konflik merge). Nanti bila kolom is_featured
    | tersedia, cukup ubah sumber query di App\Livewire\Beranda.
    |
    */

    'featured_alumni_ids' => [],
];