<li class="nav-item">
    <b class="nav-link" aria-current="page">
        PROCUREMENT OFFICE
    </b>
</li>
<li class="nav-item d-flex flex-column">
    {{-- <a class="nav-link" aria-current="page" href="{{ route('dashboard.show') }}">
        <em class="bi bi-house-fill"></em>
        Project Procurement Management Plan
    </a> --}}
    <div class="p-2">
        <div class="mb-2">
            <a href="{{ route('dashboard.show') }}" class="btn btn-dark fs-5 fw-bold w-100"><em class="bi bi-file-earmark-spreadsheet float-start"></em> Project Procurement Management Plan</a>
        </div>
        <hr />
        <div class="mb-2">
            <a class="btn btn-dark fs-5 fw-bold w-100"><em class="bi bi-card-list float-start"></em> Consolidated Annual Procurement Plan</a>
        </div>
        <hr />
        <div class="mb-2">
            <a class="btn btn-dark fs-5 fw-bold w-100"><em class="bi bi-basket2-fill float-start"></em> Purchase Request</a>
        </div>
        <div class="mb-2">
            <a class="btn btn-dark fs-5 fw-bold w-100"><em class="bi bi-chat-left-quote-fill float-start"></em> Price Quotation</a>
        </div>
        <div class="mb-2">
            <a class="btn btn-dark fs-5 fw-bold w-100"><em class="bi bi-envelope-check-fill float-start"></em> BAC Resolution</a>
        </div>
        <div class="mb-2">
            <a class="btn btn-dark fs-5 fw-bold w-100"><em class="bi bi-cart-check-fill float-start"></em> Purchase Order</a>
        </div>
        <div>
            <a class="btn btn-dark fs-5 fw-bold w-100"><em class="bi bi-bag-check-fill float-start"></em> Inspection & Acceptance</a>
        </div>
    </div>
</li>
<li><hr /></li>
<li class="nav-item">
    <b class="nav-link" aria-current="page">
        SETTINGS
    </b>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('users-list.show') }}">
        <em class="bi bi-people-fill"></em> Manage Users
    </a>
</li>
<li><hr /></li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('item-cat.show') }}">
        <em class="bi bi-filter"></em> Manage Item Categories
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('item-detail-list.all') }}">
        <em class="bi bi-bag-fill"></em> Manage Item Details
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('item-purpose.all') }}">
        <em class="bi bi-bullseye"></em> Manage Item Purposes
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('positions.all') }}">
        <em class="bi bi-person-lines-fill"></em> Manage Positions
    </a>
</li>
<li><hr /></li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('dashboard.show') }}">
        <em class="bi bi-file-text"></em> Reports Configuration
    </a>
</li>