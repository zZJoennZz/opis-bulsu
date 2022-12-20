<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <select class="form-select" id="price_catalogue_id" aria-label="Price Catalogue Category" onchange="getPriceCatalogue()">
            <option value="0" selected>Price Catalogue Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->description }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mb-3">
    <div class="message">
        @if (isset($success))
            {{ $success }}
        @endif
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
                <div class="col-4">
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