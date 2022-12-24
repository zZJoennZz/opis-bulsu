@include('layout/header', ['title' => 'Reset Password | OPIS - BulSU e-PROCUREMENT'])
<header class="bg-dark d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <img src="{{ asset('/img/logo.png') }}" class="w-50" alt="BulSU Logo" />
    </a>
</header>
<main class="reset-password-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xs-11 col-sm-10 col-md-5">
                <div class="card shadow">
                    <div class="card-header">Reset Password</div>
                    <div class="card-body">
                        <form action="{{ route('reset-password.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label for="email_address">Email Address:</label>
                                </div>
                                <div class="col-8">
                                    <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <label for="password">Password:</label>
                                </div>
                                <div class="col-8">
                                    <input type="password" id="password" class="form-control" name="password" required autofocus>
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <label for="password-confirm">Confirm Password:</label>
                                </div>
                                <div class="col-8">
                                    <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required autofocus>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                    @endif
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary float-end">
                                        Reset Password
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