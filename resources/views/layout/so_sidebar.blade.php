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
<li class="nav-item small">
    <b class="nav-link" aria-current="page">
        TRANSACTION
    </b>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('trans.all') }}">
        <em class="bi bi-eye-fill"></em>
        All Transactions
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('icsl.add') }}">
        <em class="bi bi-file-text-fill"></em>
        ICS
        <span class="badge bg-secondary">
            <em class="bi bi-caret-down-fill"></em> Low Value
        </span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('icsh.add') }}">
        <em class="bi bi-file-text-fill"></em>
        ICS
        <span class="badge bg-primary">
            <em class="bi bi-caret-up-fill"></em> High Value
        </span>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('par.add') }}" aria-current="page" class="nav-link">
        <em class="bi bi-file-text-fill"></em>
        PAR
    </a>
</li>
<li class="nav-item small">
    <b class="nav-link" aria-current="page">
        PROPERTY TRANSFER
    </b>
</li>
<li class="nav-item">
    <a href="{{ route('transfers.all') }}" aria-current="page" class="nav-link">
        <em class="bi bi-list"></em>
        All Transfers
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('ics.all') }}" aria-current="page" class="nav-link">
        <em class="bi bi-file-text-fill"></em>
        ICS
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('par.all') }}" aria-current="page" class="nav-link">
        <em class="bi bi-file-text-fill"></em>
        PAR
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
        <em class="bi bi-people-fill"></em> Manage End Users
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('supply-employee.all') }}">
        <em class="bi bi-person-fill-up"></em> Manage Supply Employees
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('supply-position.all') }}">
        <em class="bi bi-person-fill-gear"></em> Manage Supply Positions
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" aria-current="page" href="{{ route('equipment-code.all') }}">
        <em class="bi bi-diagram-3-fill"></em> Manage Equipment Codes
    </a>
</li>