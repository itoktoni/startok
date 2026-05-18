@props(['empty' => 'No data found.'])
<div class="card bg-base-100 shadow-sm">
    <div class="card-body p-0">
        {{-- Desktop table --}}
        @desktop()
        <div class="hidden lg:block overflow-x-auto">
            <table class="table table-sm w-full">
                <thead><tr>{{ $head }}</tr></thead>
                <tbody>{{ $body }}</tbody>
            </table>
        </div>
        @enddesktop()

        {{-- Mobile list --}}
        @mobile
        @if(isset($mobile))
        <div class="lg:hidden">{{ $mobile }}</div>
        @endif
        @endmobile()
    </div>
</div>
