<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        $members = Auth::user()->members()->withCount('transactions')->latest()->paginate(10);

        return view('members.index', compact('members'));
    }

    public function create(): View
    {
        return view('members.create', ['member' => new Member]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        Auth::user()->members()->create($validated);

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function edit(Member $member): View
    {
        $this->ensureOwner($member);

        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $this->ensureOwner($member);
        $member->update($this->validatedData($request, $member));

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $this->ensureOwner($member);
        $member->delete();

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Member $member = null): array
    {
        return $request->validate([
            'member_number' => ['required', 'string', 'max:50', Rule::unique('members', 'member_number')->ignore($member)],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:2000'],
            'phone' => ['required', 'string', 'max:30'],
        ]);
    }

    private function ensureOwner(Member $member): void
    {
        abort_unless($member->user_id === Auth::id(), 404);
    }
}
