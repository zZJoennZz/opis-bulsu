@include('layout/header', ['title' => 'Uh oh! Not found! | OPIS - BulSU e-PROCUREMENT'])
<div style="display: flex; align-items: center; justify-content: center; height: 100vh; flex-direction: column;">
    <div style="font-size: 10rem; text-shadow: 5px 5px 0px #c76f6f;" class="fw-bold">
        404
    </div>
    <div style="margin-top:-2rem; color: #c76f6f; font-size: 2rem;">
        Page not found!
    </div>
    <div class="fw-bold mt-3">
        <a href="{{route('login')}}" class="btn btn-danger"><em class="bi bi-arrow-bar-left"></em> Go back to main site.</a>
    </div>
</div>
@include('layout/footer')