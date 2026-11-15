<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('transactions')->where('amount', '>', 9999999999.99)->exists()) {
            throw new \RuntimeException('Nilai transaksi terlalu besar untuk dikonversi ke rupiah.');
        }

        DB::table('transactions')->update([
            'amount' => DB::raw('amount * 1000'),
        ]);
    }

    public function down(): void
    {
        DB::table('transactions')->update([
            'amount' => DB::raw('amount / 1000'),
        ]);
    }
};
