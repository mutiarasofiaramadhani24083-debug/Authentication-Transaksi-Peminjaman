<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class KodeBukuFormat implements Rule
{
    /**
     * Tentukan apakah nilai validasi memenuhi syarat.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Format: BK-[A-Z]{2,4}-[nomor 3 digit]
        return preg_match('/^BK-[A-Z]{2,4}-\d{3}$/', $value);
    }

    /**
     * Ambil pesan error jika validasi gagal.
     *
     * @return string
     */
    public function message()
    {
        return 'Format kode buku tidak valid! Harus: BK-XXX-000 (contoh: BK-PROG-001)';
    }
}