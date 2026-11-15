<div class="mb-3">
    <label class="form-label" for="member_id">Anggota</label>
    <select class="form-select @error('member_id') is-invalid @enderror" id="member_id" name="member_id" required>
        <option value="">Pilih anggota</option>
        @foreach ($members as $member)
            <option value="{{ $member->id }}" @selected(old('member_id', $transaction->member_id) == $member->id)>{{ $member->member_number }} - {{ $member->name }}</option>
        @endforeach
    </select>
    @error('member_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label" for="category">Kategori</label>
    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
        <option value="simpanan" @selected(old('category', $transaction->category) === 'simpanan')>Simpanan</option>
        <option value="pinjaman" @selected(old('category', $transaction->category) === 'pinjaman')>Pinjaman</option>
    </select>
    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label" for="type">Jenis transaksi</label>
    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
        <option value="setoran" data-category="simpanan" @selected(old('type', $transaction->type) === 'setoran')>Setoran</option>
        <option value="penarikan" data-category="simpanan" @selected(old('type', $transaction->type) === 'penarikan')>Penarikan</option>
        <option value="pencairan" data-category="pinjaman" @selected(old('type', $transaction->type) === 'pencairan')>Pencairan pinjaman</option>
        <option value="angsuran" data-category="pinjaman" @selected(old('type', $transaction->type) === 'angsuran')>Angsuran pinjaman</option>
    </select>
    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <div class="form-text">Setoran/penarikan untuk simpanan; pencairan/angsuran untuk pinjaman.</div>
</div>
<script>
    (() => {
        const category = document.getElementById('category');
        const type = document.getElementById('type');
        const updateTypes = () => {
            for (const option of type.options) {
                if (option.value) option.hidden = option.dataset.category !== category.value;
            }
            if (type.selectedOptions[0]?.dataset.category !== category.value) {
                type.value = category.value === 'pinjaman' ? 'pencairan' : 'setoran';
            }
        };
        category.addEventListener('change', updateTypes);
        updateTypes();
    })();
</script>
<div class="mb-3">
    <label class="form-label" for="amount">Jumlah (Rp)</label>
    <input class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" type="number" min="0.01" max="9999999999999.99" step="0.01" value="{{ old('amount', $transaction->amount) }}" required>
    @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <div class="form-text">Masukkan nominal penuh, misalnya 1000000 untuk Rp 1.000.000.</div>
</div>
<div class="mb-3">
    <label class="form-label" for="transaction_date">Tanggal transaksi</label>
    <input class="form-control @error('transaction_date') is-invalid @enderror" id="transaction_date" name="transaction_date" type="date" value="{{ old('transaction_date', $transaction->transaction_date?->format('Y-m-d')) }}" required>
    @error('transaction_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-4">
    <label class="form-label" for="description">Keterangan (opsional)</label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $transaction->description) }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
