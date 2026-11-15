<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpanPinjamTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_access_the_dashboard(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Admin Koperasi',
            'email' => 'admin@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->get(route('dashboard'))->assertOk()->assertSee('Ringkasan');
    }

    public function test_user_can_manage_members_and_members_belong_to_that_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->get(route('anggota.create'))->assertOk()->assertSee('Tambah anggota');

        $this->post(route('anggota.store'), [
            'member_number' => 'AG-001',
            'name' => 'Siti Aminah',
            'address' => 'Bandung',
            'phone' => '08123456789',
        ])->assertRedirect(route('anggota.index'));

        $member = Member::firstOrFail();
        $this->assertSame($user->id, $member->user_id);
        $this->get(route('anggota.index'))->assertOk()->assertSee('Siti Aminah');
        $this->get(route('anggota.edit', $member))->assertOk()->assertSee('Ubah anggota');

        $this->put(route('anggota.update', $member), [
            'member_number' => 'AG-001',
            'name' => 'Siti A.',
            'address' => 'Bandung',
            'phone' => '08123456789',
        ])->assertRedirect(route('anggota.index'));
        $this->assertDatabaseHas('members', ['id' => $member->id, 'name' => 'Siti A.']);

        $this->delete(route('anggota.destroy', $member))->assertRedirect(route('anggota.index'));
        $this->assertDatabaseMissing('members', ['id' => $member->id]);
    }

    public function test_user_can_manage_transactions_and_cannot_use_another_users_member(): void
    {
        $user = User::factory()->create();
        $member = Member::create([
            'user_id' => $user->id,
            'member_number' => 'AG-002',
            'name' => 'Budi',
            'address' => 'Jakarta',
            'phone' => '08123456780',
        ]);
        $this->actingAs($user);
        $this->get(route('transaksi.create', ['category' => 'pinjaman']))->assertOk()->assertSee('Pencairan pinjaman');

        $data = [
            'member_id' => $member->id,
            'category' => 'simpanan',
            'type' => 'setoran',
            'amount' => 1000000,
            'transaction_date' => '2026-11-12',
            'description' => 'Setoran awal',
        ];
        $this->post(route('transaksi.store'), $data)->assertRedirect(route('transaksi.index', ['category' => 'simpanan']));
        $transaction = Transaction::firstOrFail();
        $this->assertSame($user->id, $transaction->user_id);
        $this->assertSame('1000000.00', $transaction->amount);
        $this->get(route('transaksi.index'))->assertOk()->assertSee('Setoran awal')->assertSee('Rp 1.000.000');
        $this->get(route('transaksi.edit', $transaction))->assertOk()->assertSee('Ubah transaksi')->assertSee('value="1000000.00"', false);

        $this->put(route('transaksi.update', $transaction), array_merge($data, ['amount' => 1500000]))
            ->assertRedirect(route('transaksi.index', ['category' => 'simpanan']));
        $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'amount' => 1500000]);

        $this->delete(route('transaksi.destroy', $transaction))->assertRedirect(route('transaksi.index'));
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);

        $otherMember = Member::create([
            'user_id' => User::factory()->create()->id,
            'member_number' => 'AG-003',
            'name' => 'Anggota Lain',
            'address' => 'Surabaya',
            'phone' => '08123456781',
        ]);
        $this->post(route('transaksi.store'), array_merge($data, ['member_id' => $otherMember->id]))
            ->assertSessionHasErrors('member_id');

        $this->post(route('transaksi.store'), array_merge($data, ['type' => 'pencairan']))
            ->assertSessionHasErrors('type');
    }

    public function test_user_can_print_pdf_reports(): void
    {
        $user = User::factory()->create();
        $member = Member::create([
            'user_id' => $user->id,
            'member_number' => 'AG-004',
            'name' => 'Dewi',
            'address' => 'Yogyakarta',
            'phone' => '08123456782',
        ]);
        Transaction::create([
            'user_id' => $user->id,
            'member_id' => $member->id,
            'category' => 'pinjaman',
            'type' => 'pencairan',
            'amount' => 500000,
            'transaction_date' => '2026-11-12',
            'description' => 'Pinjaman usaha',
        ]);
        $this->actingAs($user);

        $membersReport = $this->get(route('reports.members'));
        $membersReport->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $membersReport->getContent());

        $transactionsReport = $this->get(route('reports.transactions'));
        $transactionsReport->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $transactionsReport->getContent());
    }
}
