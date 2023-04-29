<!-- Modal -->
<div class="modal fade" id="modalUpdate" data-bs-keyboard="false" tabindex="0" aria-labelledby="confirmYearUpdateLabel" area-hidden="true">
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
                            $('#modalUpdate').modal('show');
                        }
                    </script>
                @endif
            </div>
        </div>
    </div>
</div>