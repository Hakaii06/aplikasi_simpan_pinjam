<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');
        abort_if($category !== null && ! in_array($category, ['simpanan', 'pinjaman'], true), 404);

        $transactions = Auth::user()->transactions()
            ->with('member')
            ->when($category, fn ($query) => $query->where('category', $category))
            ->latest('transaction_date')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('transactions.index', compact('transactions', 'category'));
    }

    public function create(Request $request): View
    {
        $category = $request->query('category', 'simpanan');
        abort_unless(in_array($category, ['simpanan', 'pinjaman'], true), 404);

        return view('transactions.create', [
            'transaction' => new Transaction([
                'category' => $category,
                'type' => $category === 'pinjaman' ? 'pencairan' : 'setoran',
                'transaction_date' => now()->toDateString(),
            ]),
            'members' => Auth::user()->members()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        Auth::user()->transactions()->create($validated);

        return redirect()->route('transaksi.index', ['category' => $validated['category']])
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Transaction $transaction): View
    {
        $this->ensureOwner($transaction);

        return view('transactions.edit', [
            'transaction' => $transaction,
            'members' => Auth::user()->members()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->ensureOwner($transaction);
        $validated = $this->validatedData($request);
        $transaction->update($validated);

        return redirect()->route('transaksi.index', ['category' => $validated['category']])
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $this->ensureOwner($transaction);
        $transaction->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'member_id' => ['required', 'integer', Rule::exists('members', 'id')->where('user_id', Auth::id())],
            'category' => ['required', Rule::in(['simpanan', 'pinjaman'])],
            'type' => ['required', 'string', Rule::in(['setoran', 'penarikan', 'pencairan', 'angsuran'])],
            'amount' => ['required', 'numeric', 'decimal:0,2', 'gt:0', 'max:9999999999999.99'],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $types = $validated['category'] === 'simpanan'
            ? ['setoran', 'penarikan']
            : ['pencairan', 'angsuran'];

        if (! in_array($validated['type'], $types, true)) {
            throw ValidationException::withMessages([
                'type' => 'Jenis transaksi tidak sesuai dengan kategori yang dipilih.',
            ]);
        }

        return $validated;
    }

    private function ensureOwner(Transaction $transaction): void
    {
        abort_unless($transaction->user_id === Auth::id(), 404);
    }
}
