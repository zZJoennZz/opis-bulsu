@if (Auth::user()->account_type !== "SUPPLY_OFFICE")
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit PPMP Year</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('update-year.perform') }}" id="changePpmpForm" method="POST">
                @csrf
                @method("PUT")
                <div class="modal-body">
                    <img src="{{ asset(" img/bsu-small-logo.png") }}" alt="BSU Small Logo" class="d-block mb-3 m-auto" />
                    <label for="ppmp_year" class="form-label">Enter PPMP year</label>
                    <input type="number" min="1990" max="9999" value="{{ Auth::user()->ppmp_year }}" class="form-control fs-2" id="ppmp_year"
                        name="ppmp_year" placeholder="1996">
                    @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "PROCUREMENT_OFFICE")
                    <div class="small text-secondary">This will also update the PPMP year for all the users.</div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update PPMP Year</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="confirmYearUpdate" data-bs-keyboard="false" tabindex="0" aria-labelledby="confirmYearUpdateLabel" area-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header fw-bold">
                @if ($errors->any())
                <span class="text-danger">
                    Oh no!
                </span>
                @else
                <span class="text-success">
                    Success!
                </span>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5">
                @if ($errors->any())
                <div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex;"><span class="swal2-x-mark"><span
                            class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span></div>
                @foreach($errors->all() as $error)
                <div class="text-center text-danger fw-bold fs-4" role="alert">
                    {{ $error }}
                </div>
                @endforeach
                <script defer>
                    window.onload = function() {
                            $('#confirmYearUpdate').modal('show');
                        }
                </script>
                @endif
                @if( Session::has('success') )
                <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">
                    <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                    <span class="swal2-success-line-tip"></span>
                    <span class="swal2-success-line-long"></span>
                    <div class="swal2-success-ring"></div>
                    <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                    <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                </div>
                <div class="text-center text-success fw-bold fs-4" role="alert">
                    {!! Session::get('success') !!}
                </div>
                <script defer>
                    window.onload = function() {
                            $('#confirmYearUpdate').modal('show');
                        }
                </script>
                @endif
            </div>
        </div>
    </div>
</div>
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 fw-bold" href="{{ url('/') }}">OPIS v1</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
        aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    {{-- <input class="form-control form-control-dark w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search"> --}}
    <div class="w-100 ps-2 fs-5 text-light">
        @if (Auth::user()->account_type !== "SUPPLY_OFFICE")
        <em>PPMP Year:</em> <span data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="badge bg-secondary" style="cursor: pointer;">{{
            Auth::user()->ppmp_year }} <em class="bi bi-pencil-square"></em></span>
        <div class="float-end mx-3">
        </div>
        @endif
        <div class="float-end fs-6 py-2">
            <div class="d-inline"><i class="bi bi-clock-fill"></i></div>
            <div class="d-inline" id="system-clock" style="color: gray;"></div>
        </div>
    </div>
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="{{ route('logout.perform') }}">Logout <em class="bi bi-arrow-bar-right"></em></a>
        </div>
    </div>
</header>