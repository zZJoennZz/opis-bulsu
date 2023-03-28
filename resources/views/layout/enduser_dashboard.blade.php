<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 class="h3">Price Catalogue</h2>
    <div class="btn-toolbar mb-2 mb-md-0 d-flex justify-content-center align-items-center">
        <div class="text-muted me-2">Can't find the item?</div> <a href={{ route('add-new-item.show') }} class="btn btn-warning"><em class="bi bi-bag-plus-fill"></em> Add new!</a>
    </div>
</div>
<div class="mb-3">
    <div class="mb-3 fs-2 fw-bold"><em class="bi bi-tags-fill"></em> All Items</div>
    @if($is_consolidated)
        <div class="alert alert-warning mb-3 fs-5" role="alert">
            {{-- PPMP items for the year <div class="badge bg-primary">{{ Auth::user()->ppmp_year }}</div> is already consolidated. You are not allowed to add more items. --}}
            <strong>Warning!</strong>  Items are already consolidated.
        </div>
    @endif
    <div class="mb-3">
        <input type="text" class="form-control fs-4" id="item-search-text" oninput="onChangeText()" placeholder="Search..." />
    </div>
    <div class="mb-3">
        <select class="form-select fs-4" id="price_catalogue_id" aria-label="Price Catalogue Category" onchange="getPriceCatalogue()">
            <option value="0" selected>Price Catalogue Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->description }}</option>
            @endforeach
        </select>
    </div>
    <div id="price_catalogue">
    </div>
</div>
<script defer>
    let itemList = [
        @foreach ($items as $item)
        {
            id : {{ $item->id }},
            description : '{{ $item->description }}',
            article : '{{ $item->article }}',
            price_catalogue : '{{ $item->price_catalogue }}',
            category_id : '{{ $item->category_id }}',
            cat_desc : '{{ $item->cat_desc }}',
            uom : '{{ $item->uom }}',
            unit_id : '{{ $item->unit_id }}',
        },
        @endforeach
    ];
    function getPriceCatalogue() {
        let priceCat = document.getElementById('price_catalogue');
        let priceCatSel = document.getElementById('price_catalogue_id');
        let itemListObj = priceCatSel.value === "0" ? itemList : itemList.filter(item => item.category_id === priceCatSel.value);
        priceCat.innerHTML = "";
        itemListObj.map(item => {
            priceCat.innerHTML += `
                <div class="col-lg-4 col-sm-12 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title mb-3">${item["description"]}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">₱ ${item["price_catalogue"]} / ${item["uom"]}</h6>
                            <p class="card-text">
                                ${item["cat_desc"]}
                            </p>
                            {{-- @if($is_consolidated)
                                <button disabled class="btn btn-primary"><em class="bi bi-box-seam"></em> Item Detail</button>
                            @else
                                <a href="{{ url('item-detail') }}/${item['id']}" class="btn btn-primary"><em class="bi bi-box-seam"></em> Item Detail</a>
                            @endif --}}
                            <a href="{{ url('item-detail') }}/${item['id']}" class="btn btn-primary"><em class="bi bi-box-seam"></em> Item Detail</a>

                        </div>
                    </div>
                </div>
            `;
        })

        priceCat.innerHTML = `
            <div class="row">
                ${priceCat.innerHTML}
            </div>
        `;
    }

    function onChangeText() {
        let priceCat = document.getElementById('price_catalogue');
        let priceCatSel = document.getElementById('item-search-text');
        let itemListObj = priceCatSel.value.trim() === "" ? itemList : itemList.filter(item => item.description.toLowerCase().includes(priceCatSel.value.toLowerCase()));
        priceCat.innerHTML = "";
        itemListObj.map(item => {
            priceCat.innerHTML += `
                <div class="col-lg-4 col-sm-12 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title mb-3">${item["description"]}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">₱ ${item["price_catalogue"]} / ${item["uom"]}</h6>
                            <p class="card-text">
                                ${item["cat_desc"]}
                            </p>
                            <a href="{{ url('item-detail') }}/${item['id']}" class="btn btn-primary"><em class="bi bi-box-seam"></em> Item Detail</a>
                        </div>
                    </div>
                </div>
            `;
        })

        priceCat.innerHTML = `
            <div class="row">
                ${priceCat.innerHTML}
            </div>
        `;
    }

    getPriceCatalogue();
</script>