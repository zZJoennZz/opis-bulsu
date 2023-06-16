<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
    <h2 class="fs-5 fw-bold text-uppercase text-secondary"><em class="bi bi-tags-fill"></em> Items List</h2>
</div>
<div class="mb-3">
    @if($is_consolidated)
        <div class="alert alert-warning mb-3 fs-5" role="alert">
            {{-- PPMP items for the year <div class="badge bg-primary">{{ Auth::user()->ppmp_year }}</div> is already consolidated. You are not allowed to add more items. --}}
            <strong>Warning!</strong> Items are already consolidated.
        </div>
    @endif

    @php
        $dueDate = new DateTime(date("Y") . '/' . getSettingValue("ppmp_due_month") . '/' . getSettingValue('ppmp_due_day') . ' ' . date('H:i:s'));
        $todayDate = new DateTime(date('Y-m-d H:i:s'));

        $dayDiff = daysDiff($dueDate, $todayDate);
    @endphp
    @if ($dueDate == $todayDate)
        <div class="alert alert-danger mb-3 fs-5" role="alert">
            <div class="fw-bold">PPMP Due Today</div>
            <div>Just a reminder to complete your pending tasks before the end of the day.</div>
        </div>
    @endif

    @if ($dayDiff < 0)
        <div class="alert alert-danger mb-3 fs-5" role="alert">
            <div class="fw-bold">PPMP Past Due</div>
            <div>Just a reminder to complete your pending tasks as soon as possible.</div>
        </div>
    @endif

    @if ($dayDiff <= 5 && $dayDiff > 0)
        <div class="alert alert-warning mb-3 fs-5" role="alert">
            <div class="fw-bold">{{$dayDiff}} day{{$dayDiff == 1 ? "" : "s more"}} before due!</div>
            <div>Just a reminder to complete your pending tasks as soon as possible.</div>
        </div>
    @endif

    @if ($dayDiff < 0)
        <div class="alert alert-warning mb-3 fs-5" role="alert">
            <div class="fw-bold">You are way past the DUE DATE!</div>
            <div>Just a reminder to complete your pending tasks as soon as possible.</div>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="mb-3">
                <input type="text" class="form-control fs-4" id="item-search-text" oninput="onChangeText()" placeholder="Start typing to search for item..." />
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="mb-3">
                <select class="form-select fs-4" id="price_catalogue_id" aria-label="Price Catalogue Category" onchange="getPriceCatalogue()">
                    <option value="0" selected hidden disabled>Open to select item category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->description }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div id="price_catalogue">
    </div>
</div>
<script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
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
                    <div class="card text-start">
                        <div class="card-body">
                            <div class="float-end">
                                <a href="{{ url('item-detail') }}/${item['id']}" class="btn btn-sm btn-primary"><em class="bi bi-cart-plus-fill"></em> Add to cart</a>
                            </div>
                            <h5 class="card-title mb-3">${item["description"]}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">${convertToCurrency(parseFloat(item["price_catalogue"]))} / ${item["uom"]}</h6>
                            <span class="badge bg-secondary">${item["cat_desc"]}</span>
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
                    <div class="card text-start">
                        <div class="card-body">
                            <div class="float-end">
                                <a href="{{ url('item-detail') }}/${item['id']}" class="btn btn-sm btn-primary"><em class="bi bi-cart-plus-fill"></em> Add to cart</a>
                            </div>
                            <h5 class="card-title mb-3">${item["description"]}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">${convertToCurrency(parseFloat(item["price_catalogue"]))} / ${item["uom"]}</h6>
                            <span class="badge bg-secondary">${item["cat_desc"]}</span>
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