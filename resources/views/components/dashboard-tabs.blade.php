@props(['tabs' => [], 'id' => 'tabs'])

<div class="card bg-base-100 shadow-sm">
    <div class="card-body p-4">
        <div class="flex gap-1 border-b border-base-300 mb-3">
            @foreach($tabs as $index => $tab)
            <button class="tab-btn px-3 py-1.5 text-xs font-medium border-b-2 {{ $index === 0 ? 'border-primary text-primary' : 'border-transparent text-base-content/50' }}"
                    onclick="showTab('{{ $tab['id'] ?? $index }}')">
                {{ $tab['label'] ?? 'Tab ' . ($index + 1) }}
            </button>
            @endforeach
        </div>

        @foreach($tabs as $index => $tab)
        <div id="tab-{{ $tab['id'] ?? $index }}" class="{{ $index !== 0 ? 'hidden' : '' }}">
            {{ $tab['content'] ?? '' }}
        </div>
        @endforeach
    </div>
</div>

@once
@push('scripts')
<script>
function showTab(id) {
    document.querySelectorAll('[id^="tab-"]').forEach(el => el.classList.add('hidden'));
    document.getElementById('tab-' + id)?.classList.remove('hidden');
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.className = 'tab-btn px-3 py-1.5 text-xs font-medium border-b-2 border-transparent text-base-content/50';
    });
    event.target.className = 'tab-btn px-3 py-1.5 text-xs font-medium border-b-2 border-primary text-primary';
}
</script>
@endpush
@endonce
