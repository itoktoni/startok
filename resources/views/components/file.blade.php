@props(['name', 'label' => null, 'col' => '12', 'multiple' => false, 'accept' => 'image/*,.pdf'])
@php $label = $label ?? ucwords(str_replace('_', ' ', $name)); $uid = 'file_'.uniqid(); @endphp
<div class="col-span-{{ $col }} md:col-span-{{ $col }}">
    @if($label)<label class="label-text text-xs">{{ $label }}</label>@endif
    <div class="border-2 border-dashed border-base-300 rounded-lg p-4 text-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-colors mt-1"
        onclick="document.getElementById('{{ $uid }}').click()"
        ondragover="event.preventDefault();this.classList.add('border-primary','bg-primary/5')"
        ondragleave="this.classList.remove('border-primary','bg-primary/5')"
        ondrop="event.preventDefault();this.classList.remove('border-primary','bg-primary/5');document.getElementById('{{ $uid }}').files=event.dataTransfer.files">
        <span class="icon-[tabler--cloud-upload] size-6 text-base-content/30 mx-auto"></span>
        <p class="text-[10px] text-base-content/50 mt-1">Drag & drop or click to browse</p>
        <input type="file" id="{{ $uid }}" name="{{ $name }}" class="hidden" {{ $multiple ? 'multiple' : '' }} accept="{{ $accept }}" {{ $attributes }}>
    </div>
</div>
