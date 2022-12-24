<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit PPMP Year</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <img src="{{ asset("img/bsu-small-logo.png") }}" alt="BSU Small Logo" class="d-block mb-3 m-auto" />
            <label for="ppmp_year" class="form-label">Enter PPMP year</label>
            <input type="number" min="1990" max="3000" value="{{ Auth::user()->ppmp_year }}" class="form-control fs-2" id="ppmp_year" name="ppmp_year" placeholder="1996">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Update PPMP Year</button>
        </div>
        </div>
    </div>
</div>
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 fw-bold" href="{{ url('/') }}">OPIS v1</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    {{-- <input class="form-control form-control-dark w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search"> --}}
    <div class="w-100 ps-2 fs-5 text-light">
        <em>PPMP Year:</em> <span data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="badge bg-secondary" style="cursor: pointer;">{{ Auth::user()->ppmp_year }} <em class="bi bi-pencil-square"></em></span>
        <div class="float-end">
            <div id="system-clock" style="color: gray;"></div>
        </div>
    </div>
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
        <a class="nav-link px-3" href="{{ route('logout.perform') }}">Logout</a>
        </div>
    </div>
</header>