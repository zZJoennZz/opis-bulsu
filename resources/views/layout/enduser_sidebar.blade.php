<li class="nav-item">
    <b class="nav-link" aria-current="page">
        END USER
    </b>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('dashboard.show') }}">
        <em class="bi bi-tags-fill"></em>
        Price Catalogue
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('ppmp-cart.get') }}">
        <em class="bi bi-cart-fill"></em>
        <div class="position-relative d-inline">
            Cart
            <span class="badge rounded-pill bg-danger" style="font-size: 0.7rem">
                @php
                    echo getCartCount()
                @endphp
            </span>
        </div>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('ppmp-request.get') }}">
        <em class="bi bi-window"></em>
        PPMP <span class="badge text-bg-primary">{{ Auth::user()->ppmp_year }}</span>
    </a>
</li>   
<li class="nav-item">
    <a class="nav-link" href="{{ route('pr-list.show') }}">
        <em class="bi bi-list-ul"></em>
        Purchase Request List
    </a>
</li>