@props(['price' => null, 'description' => null])
<p class="text-sm font-bold mt-1">Rp {{ number_format($price, 0, ',', '.') }}</p>
<p class="text-xs text-base-content/50">{{ $description }}</p>
