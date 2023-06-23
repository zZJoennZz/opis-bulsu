<x-dashboard-layout>
    <x-slot:title>
        Edit Item Detail
    </x-slot>

    @php
        $bc = [];
        $cancelUrl;
        if (Auth::user()->account_type === 'END_USER') {
            $bc =
            [
                ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                ['name' => 'Add <span class="badge bg-primary">' . $item_detail->description . '</span> to cart', 'routeWithParam' => route('item-detail-single.show', ['id' => $item_detail->id])],
                ['name' => 'Edit item detail: <span class="badge bg-primary">' . $item_detail->description . '</span>']
            ];
            $cancelUrl = route('item-detail-single.show', ['id' => $item_detail->id]);
        } else if (Auth::user()->account_type === 'PROCUREMENT_HEAD' ||Auth::user()->account_type === 'PROCUREMENT_OFFICE' || Auth::user()->account_type === 'admin') {
            $bc =
            [
                ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                ['name' => 'Item Details List', 'route' => 'item-detail-list.all'],
                ['name' => 'Edit item detail: <span class="badge bg-primary">' . $item_detail->description . '</span>']
            ];
            $cancelUrl = route('item-detail-list.all');
        }
        $breadcrumb = $bc;
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <div class="mb-3">
        <h1 class="card-title">@if($item_detail->is_approve === 0 && $item_detail->is_delete === 0) <span class="badge bg-secondary">Under Review</span> @endif @if($item_detail->is_delete===1) <span class="badge bg-danger">Item deleted</span> @endif</h1>
        @if (Auth::user()->account_type === 'PROCUREMENT_HEAD' || Auth::user()->account_type === 'PROCUREMENT_OFFICE' || Auth::user()->account_type === 'admin')
            <div>
                <small><a href="{{route('pending-item-detail.single', ['item_detail_id' => $item_detail->id])}}">View changes logs</a></small>
            </div>
        @endif
    </div>
    <div class="mb-3">
        <form action="@if($item_detail->is_approve === 0 && $item_detail->is_delete === 0 && (Auth::user()->account_type === "PROCUREMENT_HEAD" || Auth::user()->account_type === "admin")){{ route('item-detail-review-approve.perform', ['item_detail_id' => $item_detail->id]) }}@else{{ route('view-item-detail.update', ['item_detail_id' => $item_detail->id]) }}@endif" method="post">
            @csrf
            <div class="form-floating mb-3">
                <input type="text" class="form-control" value="{{$item_detail->description}}" id="description" name="description" placeholder="Item name" @if($item_detail->is_delete===1) disabled @endif required>
                <label for="description">Item name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" value="{{$item_detail->article}}" id="article" name="article" placeholder="Item article" @if($item_detail->is_delete===1) disabled @endif required>
                <label for="article">Item article</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" value="{{$item_detail->extra_article}}" id="extra_article" name="extra_article" placeholder="Item article line 2" @if($item_detail->is_delete===1) disabled @endif required>
                <label for="extra_article">Item article line 2</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="unit_id" name="unit_id" @if($item_detail->is_delete===1) disabled @endif aria-label="Unit of measurement">
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" @if ($item_detail->unit_id === $unit->id) selected @endif>{{ $unit->uom }}</option>
                    @endforeach
                </select>
                <label for="unit_id">Unit of Measurement</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" value="{{$item_detail->price_catalogue}}" id="price_catalogue" @if($item_detail->is_delete===1) disabled @endif name="price_catalogue" placeholder="Price catalogue" required>
                <label for="article">Price catalogue</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="category_id" name="category_id" @if($item_detail->is_delete===1) disabled @endif aria-label="Item category">
                    @foreach ($item_categories as $category)
                        <option value="{{ $category->id }}" @if ($item_detail->category_id === $category->id) selected @endif>{{ $category->description }}</option>
                    @endforeach
                </select>
                <label for="category_id">Category</label>
            </div>
            <div class="row">
                <div class="col-12 d-flex" style="justify-content: end;">
                    @if($item_detail->is_approve === 0 && $item_detail->is_delete === 0 && (Auth::user()->account_type === "PROCUREMENT_HEAD" || Auth::user()->account_type === "admin"))
                        <button type="submit" @if($item_detail->is_delete===1) disabled @endif class="btn btn-info me-2"><em class="bi bi-save"></em> Save & Approve Item</button>
                    @endif
                    @if (Auth::user()->account_type === "PROCUREMENT_HEAD" || Auth::user()->account_type === "admin")
                        <script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
                        <script>
                            async function deleteItem() {
                                let isConfirm = confirm("Are you sure to delete this item?");
                                if (isConfirm) {
                                    await axios.delete('{{ route('item-detail.delete', ['item_detail_id' => $item_detail->id]) }}')
                                        .then(res => window.location.reload())
                                        .catch(err => window.location.reload());
                                }
                            }
                        </script>
                        <button type="button" @if($item_detail->is_delete===1) disabled @endif class="btn btn-danger me-2" onclick="deleteItem()"><em class="bi bi-trash"></em> Delete Item</button>
                    @endif

                    @if (count($item_detail->histories) >= 1)
                        @if ($item_detail->is_approve === 1 && $item_detail->is_delete === 0 && $item_detail->histories[count($item_detail->histories)-1]->is_approve === 1)
                            <button type="submit" @if($item_detail->is_delete===1) disabled @endif class="btn btn-primary me-2"><em class="bi bi-save"></em> Save Item Detail</button>
                        @elseif ($item_detail->histories[count($item_detail->histories)-1]->is_approve === 0)
                            <button disabled class="btn btn-primary me-2"><em class="bi bi-save"></em> This item have pending changes, can't save new changes</button>
                        @endif
                    @else
                        @if ($item_detail->is_approve === 1 && $item_detail->is_delete === 0)
                            <button type="submit" @if($item_detail->is_delete===1) disabled @endif class="btn btn-primary me-2"><em class="bi bi-save"></em> Save Item Detail</button>
                        @endif
                    @endif
                    
                    <a href="{{ $cancelUrl }}" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>
    </div>
    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>