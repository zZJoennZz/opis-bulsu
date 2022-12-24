@include('layout/header', ['title' => 'Reset Password | OPIS - BulSU e-PROCUREMENT'])
<header class="bg-dark d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <img src="{{ asset('/img/logo.png') }}" class="w-50" alt="BulSU Logo" />
    </a>
</header>
<main class="forgot-password-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mb-3">
                    <a href="{{ url('/') }}" class="btn btn-secondary">Go Back</a>
                </div>
                <div class="card shadow">
                    <div class="card-header">Reset Password</div>
                    <div class="card-body">
                        @if (Session::has('message'))
                            <div class="alert alert-success" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        <form action="{{ route('forgot-password.submit') }}" method="POST">
                            @csrf
                            <div class="form-group row mb-3">
                                <label for="email_address" class="col-md-2 col-form-label text-md-right">Email Address</label>
                                <div class="col-md-6">
                                    <input type="text" id="email_address" class="form-control" name="email" required autofocus placeholder="Please enter your account's email address">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Send Password Reset Link
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@include('layout/footer')