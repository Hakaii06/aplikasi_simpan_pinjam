<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();

        return view('dashboard', [
            'memberCount' => $user->members()->count(),
            'savingCount' => $user->transactions()->where('category', 'simpanan')->count(),
            'loanCount' => $user->transactions()->where('category', 'pinjaman')->count(),
            'recentTransactions' => $user->transactions()->with('member')->latest('transaction_date')->latest()->limit(8)->get(),
        ]);
    }
}
