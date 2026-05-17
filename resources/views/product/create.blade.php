<x-layouts::app :title="$title">
    <nav class="hidden lg:block text-xs" aria-label="Breadcrumb">
        <ol class="flex items-center gap-1 text-base-content/60">
            <li><a href="/product/table" class="hover:text-primary">Products</a></li>
            <li><span class="icon-[tabler--chevron-right] size-3"></span></li>
            <li class="text-base-content font-medium">Create</li>
        </ol>
    </nav>

    @if($errors->any())
        <div class="alert alert-error text-xs py-2">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="/product/create" method="POST">
        @csrf
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body p-4 gap-3">
                <h3 class="card-title text-sm">Create Product</h3>
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-12 md:col-span-6">
                        <label class="label-text text-xs">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="input input-sm w-full" placeholder="Product name" required>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <label class="label-text text-xs">Price</label>
                        <div class="join w-full">
                            <span class="join-item btn btn-sm no-animation">Rp</span>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="input input-sm join-item flex-1" placeholder="0" required>
                        </div>
                    </div>
                    <div class="col-span-12">
                        <label class="label-text text-xs">Description</label>
                        <textarea name="description" class="textarea textarea-sm w-full" rows="3" placeholder="Product description...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Bar --}}
        <div class="fixed left-0 lg:left-56 right-0 bg-base-100 border-t border-base-300 px-3 py-2 z-30" style="bottom:0">
            <div class="flex gap-1.5 justify-end">
                <button type="submit" class="btn btn-sm btn-primary">Create</button>
                <a href="/product/table" class="btn btn-sm btn-outline">Cancel</a>
            </div>
        </div>
    </form>
</x-layouts::app>
