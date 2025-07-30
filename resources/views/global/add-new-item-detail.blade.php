<x-dashboard-layout>
    <x-slot:title>
        Add New Item Detail
    </x-slot>

    @php
        $bc = [];
        $cancelUrl;
        if (Auth::user()->account_type === 'END_USER') {
            $bc =
            [
                ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                ['name' => 'Add new item detail']
            ];
            $cancelUrl = route('dashboard.show');
        } else if (Auth::user()->account_type === 'PROCUREMENT_OFFICE' || Auth::user()->account_type === 'admin' || Auth::user()->account_type === 'PROCUREMENT_HEAD') {
            $bc =
            [
                ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                ['name' => 'Item Details List', 'route' => 'item-detail-list.all'],
                ['name' => 'Add new item detail']
            ];
            $cancelUrl = route('item-detail-list.all');
        }
        $breadcrumb = $bc;
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <div class="mb-3">
        <form action="{{ route('add-new-item.perform') }}" method="post">
            @csrf
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="description" name="description" placeholder="Item name" required>
                <label for="description">Item name</label>
            </div>
            <div class="form-floating mb-3">
                <input required type="text" class="form-control" id="article" name="article" placeholder="Item article">
                <label for="article">Item article</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="extra_article" name="extra_article" placeholder="Item article">
                <label for="extra_article">Item article line 2</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="unit_id" name="unit_id" aria-label="Unit of measurement">
                    <option value="0" selected>Select unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->uom }}</option>
                    @endforeach
                </select>
                <label for="unit_id">Unit of Measurement</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="price_catalogue" name="price_catalogue" placeholder="Price catalogue" required>
                <label for="article">Price catalogue</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="category_id" name="category_id" aria-label="Item category">
                    <option value="0" selected>Select item category</option>
                    @foreach ($item_categories as $category)
                        <option value="{{ $category->id }}">{{ $category->group->title }}@if($category->description!="N/A"), {{$category->description}}@endif</option>
                    @endforeach
                </select>
                <label for="category_id">Category</label>
            </div>
            <div class="row">
                <div class="col-12 d-flex" style="justify-content: end;">
                    <button type="submit" class="btn btn-primary me-2"><em class="bi bi-save"></em> Save Item Detail</button>
                    <a href="{{ $cancelUrl }}" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>
    </div>
    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>