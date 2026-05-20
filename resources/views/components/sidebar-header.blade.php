<div class="p-3 pb-4 border-b border-base-300">
    <select class="select select-sm w-full">
        <option>{{ config('app.name', 'Laravel') }}</option>
        @foreach ($options ?? [] as $key => $label)
            <option value="{{ $key }}">{{ $label }}</option>
        @endforeach
    </select>
</div>
