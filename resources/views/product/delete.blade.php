<x-layouts::app :title="$title ?? 'Delete Product'">
    <div class="max-w-lg">
        <h1 class="text-lg font-bold mb-3 text-error">Delete Product</h1>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body p-4">
                <p class="mb-4">Are you sure you want to delete <strong>{{ $product->name }}</strong>?</p>
                <form action="/product/delete/{{ $product->id }}" method="POST">
                    @csrf
                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-sm btn-error">Delete</button>
                        <a href="/product/table" class="btn btn-sm btn-soft">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
