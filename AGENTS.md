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

---

## Menu Configuration

Menu didefinisikan di `config/menu.php` dan di-render oleh component Blade.

### Config Structure (`config/menu.php`)

```php
return [
    'sidebar' => [
        [
            'label' => 'Warehouse',  // null untuk section tanpa label
            'items' => [
                ['route' => 'warehouse.stock', 'icon' => 'inventory_2', 'label' => 'Stock Management'],
                // ...
            ],
        ],
    ],
    'bottom_nav' => [
        ['route' => 'warehouse.stock', 'icon' => 'inventory_2', 'label' => 'Stock'],
        // max 5 items, item ke-3 (index 2) jadi center button
    ],
];
```

### Components

| Component | Fungsi |
|-----------|--------|
| `<x-menu-items />` | Render sidebar/drawer menu dari config |
| `<x-menu-items :mobile="true" />` | Render dengan `@click="drawerOpen = false"` |
| `<x-bottom-nav />` | Render bottom nav dari config |

### Penggunaan di Layout

```blade
{{-- Mobile Drawer --}}
<nav class="flex-1 py-4 px-3 pb-24 space-y-1 overflow-y-auto">
    <x-menu-items :mobile="true" />
</nav>

{{-- Desktop Sidebar --}}
<nav class="flex-1 space-y-2 overflow-y-auto pr-3 pb-4">
    <x-menu-items />
</nav>

{{-- Bottom Nav --}}
<x-bottom-nav />
```

### Menambah Menu Baru

1. Buka `config/menu.php`
2. Tambah item di section yang sesuai:
   - `sidebar` — untuk desktop sidebar dan mobile drawer
   - `bottom_nav` — untuk bottom nav mobile (max 5 items)
3. Format: `['route' => 'route.name', 'icon' => 'material_icon', 'label' => 'Display Label']`

### Contoh

Lihat:
- `config/menu.php`
- `resources/views/components/menu-items.blade.php`
- `resources/views/components/bottom-nav.blade.php`
- `resources/views/layouts/warehouse.blade.php`

---

## Database Naming Convention

### Bahasa Indonesia

Semua nama field menggunakan **bahasa Indonesia**. Contoh:

| English | Indonesia |
|---------|-----------|
| name | nama |
| note | catatan |
| amount | jumlah |
| status | status |
| type | tipe |
| description | keterangan |
| price | harga |
| date | tanggal |
| image | gambar |
| address | alamat |
| phone | telepon |

### Table Naming

Table names use **plural snake_case** (default Laravel convention):

```
users, payments, affiliate, cashouts, plans
```

Exception: entity-specific tables can use singular if it represents a domain concept (e.g., `affiliate`).

### Field Naming

All fields use prefix `{table_singular}_` with the table name as prefix.

#### Rules

1. **Primary key**: `{table}_id` (e.g., `payment_id`, `affiliate_id`, `cashout_id`)
2. **Foreign keys**: `{table}_id_{reference}` (e.g., `payment_id_user`, `affiliate_id_from_user`, `cashout_id_user`)
3. **Regular fields**: `{table}_{field_indonesia}` (e.g., `payment_jumlah`, `payment_status`, `affiliate_tipe`)
4. **Timestamps**: `{table}_created_at`, `{table}_updated_at` (e.g., `payment_created_at`)

#### Examples

| Table | PK | Foreign Keys | Fields |
|-------|-----|-------------|--------|
| `users` | `id` | - | `nama`, `email`, `role`, `affiliate_code`, `affiliate_reff`, `rekening_nama`, `rekening_bank`, `rekening_nomor` |
| `payments` | `payment_id` | `payment_id_user`, `payment_id_plan` | `payment_order_code`, `payment_jumlah`, `payment_diskon`, `payment_total`, `payment_qris_string`, `payment_status`, `payment_metode`, `payment_paid_at`, `payment_expired_at`, `payment_created_at`, `payment_updated_at` |
| `affiliate` | `affiliate_id` | `affiliate_id_user`, `affiliate_id_from_user`, `affiliate_id_payment` | `affiliate_tipe`, `affiliate_jumlah`, `affiliate_payment_jumlah`, `affiliate_commission_rate`, `affiliate_catatan`, `affiliate_status`, `affiliate_created_at`, `affiliate_updated_at` |
| `cashouts` | `cashout_id` | `cashout_id_user` | `cashout_jumlah`, `cashout_admin_fee`, `cashout_diterima`, `cashout_rekening_bank`, `cashout_rekening_nomor`, `cashout_rekening_nama`, `cashout_status`, `cashout_catatan`, `cashout_created_at`, `cashout_updated_at` |

#### Model Convention

```php
class Payment extends Model
{
    protected $primaryKey = 'payment_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'payment_id_user');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'payment_id_plan', 'plan_id');
    }
}
```

#### Creating New Table

When creating a new table, follow this template (gunakan bahasa Indonesia untuk nama field):

```php
Schema::create('example', function (Blueprint $table) {
    $table->id('example_id');
    $table->integer('example_id_user');
    $table->string('example_nama');
    $table->integer('example_jumlah');
    $table->enum('example_status', ['aktif', 'nonaktif'])->default('aktif');
    $table->text('example_catatan')->nullable();
    $table->dateTime('example_created_at')->nullable();
    $table->dateTime('example_updated_at')->nullable();
});
```
