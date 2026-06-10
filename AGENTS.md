# AGENTS.md - Panduan untuk AI Agent

## Form dengan Fixed Bottom Action Bar

Saat membuat form yang memiliki tombol aksi (save, submit, dll) yang perlu selalu terlihat di bawah layar, gunakan component `<x-action>`.

### Struktur dengan Model (CRUD)

```blade
<x-form :model="$model" :action="route('...')" method="POST">
    <x-card :label="Title">
        @bind($model)
            {{-- Input fields --}}
        @endbind
    </x-card>

    <x-action :model="$model" :action="['save']"/>
</x-form>
```

### Struktur Tanpa Model (Manual)

Untuk form tanpa model (seperti settings/env), buat manual karena `<x-action>` menggunakan `@can` check yang memerlukan model:

```blade
<form action="{{ route('...') }}" method="POST">
    @csrf
    {{-- Form content --}}

    <style>
        @media (min-width: 768px) { .action-bar { bottom: 0 !important; } }
    </style>
    <div class="action-bar fixed left-0 right-0 lg:left-72 bg-surface-container-lowest border-t border-outline-variant shadow-[0_-4px_12px_rgba(0,0,0,0.08)] px-4 md:px-6 py-3 z-[45]" style="bottom: 4rem">
        <div class="flex items-center justify-end max-w-full mx-auto gap-3">
            <x-button type="submit" icon="save">Save</x-button>
        </div>
    </div>
    <div class="h-28 md:h-16"></div>
</form>
```

### Cara Kerja

| Behavior | Mobile | Desktop |
|----------|--------|---------|
| Posisi | `style="bottom: 4rem"` (di atas bottom nav `h-16`) | CSS media query `bottom: 0 !important` |
| Sidebar offset | Tidak ada | `lg:left-72` |
| Z-index | `z-[45]` | `z-[45]` |
| Spacer | `h-28` (7rem) | `h-16` (4rem) |

### Catatan Penting

- `<x-action>` menggunakan `@can('save', $model)` — hanya gunakan untuk form dengan model yang punya policy
- Untuk form tanpa model, buat manual HTML dengan class yang sama seperti di atas
- Jangan lupa `<div class="h-28 md:h-16"></div>` sebagai spacer setelah action bar

### Contoh Penggunaan

- `resources/views/pages/product/form.blade.php` — form dengan model (`<x-action>`)
- `resources/views/pages/settings/env.blade.php` — form tanpa model (manual)

---

## Mobile Drawer Padding

Drawer sidebar di mobile perlu padding bottom agar menu di bagian bawah (seperti Settings) bisa di-scroll dan terlihat.

### Masalah

Tanpa padding bottom, menu di bagian bawah drawer terpotong dan tidak bisa di-scroll karena tertimpa area bawah layar.

### Solusi

Tambahkan `pb-24` pada `<nav>` di dalam mobile drawer:

```blade
<nav class="flex-1 py-4 px-3 pb-24 space-y-1 overflow-y-auto">
    {{-- Menu items --}}
</nav>
```

### Kenapa `pb-24`?

- Bottom nav bar mobile: `h-16` (4rem)
- Padding bottom: `pb-24` (6rem) — memberikan ruang ekstra agar item terakhir bisa di-scroll ke atas dan terlihat

### Contoh

Lihat `resources/views/layouts/warehouse.blade.php` pada bagian Mobile Drawer.

---

## Filter Component

Component `<x-filter>` digunakan untuk search, perpage, dan filter pada halaman table.

### Struktur

```blade
<x-filter :fields="$fields" searchPlaceholder="Search..." />
```

### Layout

```
[Perpage] [FilterField =====] [Search ==========================]
```

| Element | Width | Keterangan |
|---------|-------|------------|
| Perpage select | `w-3xl` | Fixed width, tidak `w-full` |
| FilterField select | `flex-1` | Sama rata dengan search |
| Search input + button | `flex-1` | Sama rata dengan filterField |

### Catatan Penting

- Perpage: parent div `relative sm:w-auto` (bukan `w-full`), select `w-3xl` agar chevron mengikuti lebar select
- FilterField dan Search: kedua-duanya `flex-1` agar lebar sama rata
- Chevron (expand_more) menggunakan `absolute right-3` di dalam parent `relative`

### Contoh

Lihat `resources/views/components/filter.blade.php`.

---

## Profile Tabs

Tab buttons pada halaman profile menggunakan padding `py-4` agar tidak berdempetan.

### Struktur

```blade
<div class="flex overflow-x-auto border-b border-outline-variant bg-surface-container">
    <button class="flex items-center gap-2 px-4 md:px-5 py-4 text-sm font-semibold border-b-2 transition-colors whitespace-nowrap">
        Tab Label
    </button>
</div>
```

### Catatan

- `py-4` — padding vertikal tab button
- `overflow-x-auto` — agar tab bisa di-scroll horizontal di mobile
- `whitespace-nowrap` — agar teks tab tidak wrap

### Contoh

Lihat `resources/views/pages/settings/profile.blade.php`.
