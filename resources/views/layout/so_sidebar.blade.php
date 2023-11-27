<li class="nav-item small">
    <b class="nav-link" aria-current="page">
        SUPPLY OFFICE
    </b>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('dashboard.show') }}">
        <em class="bi bi-house-fill"></em>
        Dashboard
    </a>
</li>
<li class="nav-item d-flex flex-column">
    <div class="p-2">
        <div class="mb-2">
            <a href="{{ route('icsl.add') }}" class="btn btn-dark fs-5 fw-bold w-100"><em class="bi bi-file-text-fill float-start"></em>
                ICS
                <span class="badge bg-secondary">
                    <em class="bi bi-caret-down-fill"></em> Low Value
                </span></a>
        </div>
        <div class="mb-2">
            <a href="{{ route('icsh.add') }}" class="btn btn-dark fs-5 fw-bold w-100"><em class="bi bi-file-text-fill float-start"></em>
                ICS
                <span class="badge bg-primary">
                    <em class="bi bi-caret-up-fill"></em> High Value
                </span></a>
        </div>
        <div class="mb-2">
            <a href="{{ route('par.add') }}" class="btn btn-dark fs-5 fw-bold w-100"><em class="bi bi-file-text-fill float-start"></em>
                PAR</a>
        </div>
        <div class="mb-2">
            <a href="{{ route('trans.all') }}" class="btn btn-dark fs-5 fw-bold w-100"><em class="bi bi-eye-fill float-start"></em>
                All Transactions</a>
        </div>
    </div>
</li>
<li class="nav-item small">
    <b class="nav-link" aria-current="page">
        PROPERTIES
    </b>
</li>
<li class="nav-item">
    <a href="{{ route('transfers.all') }}" aria-current="page" class="nav-link">
        <em class="bi bi-list"></em>
        Transfers
    </a>
</li>
{{-- <li class="nav-item">
    <a href="{{ route('ics.all') }}" aria-current="page" class="nav-link">
        <em class="bi bi-file-text-fill"></em>
        ICS Items
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('par.all') }}" aria-current="page" class="nav-link">
        <em class="bi bi-file-text-fill"></em>
        PAR Items
    </a>
</li> --}}
<li class="nav-item">
    <a href="{{ route('transfered_items.all') }}" aria-current="page" class="nav-link">
        <em class="bi bi-bag-fill"></em>
        Items
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('end_users.all') }}" aria-current="page" class="nav-link">
        <em class="bi bi-people-fill"></em>
        Keepers
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('maintenance.index') }}" aria-current="page" class="nav-link">
        <em class="bi bi-wrench-adjustable-circle"></em>
        Unserviceable Property
    </a>
</li>
<li>
    <hr />
</li>
<li class="nav-item small text-uppercase">
    <b class="nav-link" aria-current="page">
        General Inventory
    </b>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('gi-par.show') }}">
        <em class="bi bi-people-fill"></em> Property Acknowledgement Receipt
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('gi-ics.show') }}">
        <em class="bi bi-person-fill-up"></em> Inventory Custodian Receipt
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('iirup.index') }}">
        <em class="bi bi-person-fill-gear"></em> Inventory and Inspection Report of Unserviceable Property
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('equipment-code.all') }}">
        <em class="bi bi-diagram-3-fill"></em> Inventory of Furniture and Fixtures
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('ppe.index') }}">
        <em class="bi bi-diagram-3-fill"></em> Physical Count of Property, Plant and Equipment
    </a>
</li>
<li>
    <hr />
</li>
<li class="nav-item small">
    <b class="nav-link" aria-current="page">
        SETTINGS
    </b>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('supply-end-user.all') }}">
        <em class="bi bi-people-fill"></em> End Users
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('supply-employee.all') }}">
        <em class="bi bi-person-fill-up"></em> Supply Employees
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('supply-position.all') }}">
        <em class="bi bi-person-fill-gear"></em> Supply Positions
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('equipment-code.all') }}">
        <em class="bi bi-diagram-3-fill"></em> Equipment Codes
    </a>
</li>