<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                <img src="{{ asset("img/bsu-small-logo.png") }}" alt="BSU Small Logo" class="d-block mb-3 m-auto" />
                <label for="ppmp_year" class="form-label">Enter PPMP year</label>
                <input type="number" min="1990" max="9999" value="{{ Auth::user()->ppmp_year }}" class="form-control fs-2" id="ppmp_year" name="ppmp_year" placeholder="1996">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update PPMP Year</button>
            </div>
        </form>
        </div>
    </div>
</div>

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
                    <div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex;"><span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span></div>
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