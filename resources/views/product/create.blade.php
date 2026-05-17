<x-layouts::app :title="$title">
    <div class="max-w-lg">
        <h1 class="text-lg font-bold mb-3">Create Product</h1>

        @if($errors->any())
            <div class="alert alert-error mb-3 text-xs">
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body p-4">
                <form action="/product/create" method="POST" class="space-y-3">
                    @csrf
                    <div><label class="label-text text-xs">Name</label><input type="text" name="name" value="{{ old('name') }}" class="input input-sm w-full" required></div>
                    <div><label class="label-text text-xs">Price</label><input type="number" step="0.01" name="price" value="{{ old('price') }}" class="input input-sm w-full" required></div>
                    <div><label class="label-text text-xs">Description</label><textarea name="description" class="textarea textarea-sm w-full" rows="3">{{ old('description') }}</textarea></div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        <a href="/product/table" class="btn btn-sm btn-soft">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
