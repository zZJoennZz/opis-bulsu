    @include('layout/header', ['title' => 'OPIS - BulSU e-PROCUREMENT'])
    <section class="min-vh-100">
        <div class="container-fluid h-100">
            <div class="row d-flex justify-content-center min-vh-100 align-items-center h-100 bg-dark bg-gradient">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <div class="mb-5">
                        <img src="{{ asset('img/logo.png') }}" class="img-fluid" alt="BSU Logo">
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-justify text-light">
                            <h2 class="h3 text-start mb-3">The BulSU Transparency Seal</h2>
                            <p class="small">
                                A pearl buried inside a tightly-shut shell is practically worthless. Government information is a pearl, meant to be shared with the public in order to maximize its inherent value.The Transparency Seal, depicted by a pearl shining out of an open shell, is a symbol of a policy shift towards openness in access to government information. On the one hand, it hopes to inspire Filipinos in the civil service to be more open to citizen engagement; on the other, to invite the Filipino citizenry to exercise their right to participate in governance… <a class="text-secondary" href="https://bulsu.edu.ph/transparency-seal/" target="_blank" role="button">Read More</a>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-justify text-light">
                            <h2 class="h3 text-start mb-3">About BulSU</h2>
                            <p class="small text-justify">
                                Bulacan State University (BulSU) is a state-funded institution of higher learning established in 1904 and converted into a university in 1993 by virtue of Republic Act 7665. The University in mandated to provide higher professional/technical and special instruction for special purpose and to promote research and extension services, advanced studies and extension services, advanced studies and progressive leadership in Engineering, Architecture, Education, Art and Science, Fine Arts, Information Technology, Technical courses, Commerce… <a class="text-secondary" href="https://bulsu.edu.ph/about/" target="_blank" role="button">Read More</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1 mb-sm-5 order-first order-md-last">
                    <div class="card shadow mb-3 mt-3">
                        <div class="card-body p-3">
                            <form method="POST" action={{ route('login.perform') }}>
                                <div class="mb-3 d-flex" style="flex-direction: column;">
                                    <img src="{{ asset('img/bsu-small-logo.png') }}" class="mx-auto mb-2" alt="BSU Small Logo" />
                                    <div class="text-center text-uppercase fw-bold">Bulacan State University</div>
                                    <div class="text-center fst-italic mb-3">Please login to proceed</div>
                                    @if(isset ($errors) && count($errors) > 0)
                                    <div class="alert alert-warning" role="alert">
                                        <ul class="list-unstyled mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" name="username" placeholder="Username" required />
                                    <label for="floatingInput">Username / Email Address</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required />
                                    <label for="floatingPassword">Password</label>
                                </div>
                                <button class="mb-3 w-100 btn btn-lg btn-primary" type="submit">Login</button>
                                <div class="text-center"><a href="{{ route('forgot-password.show') }}">Forgot password?</a></div>
                                <hr class="my-4">
                                <small class="text-muted text-center d-block">OPIS v1.0</small>
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary">
            <!-- Copyright -->
            <div class="text-white mb-3 mb-md-0">
                Copyright © {{ date("Y") }}. Bulacan State University.
            </div>
            <!-- Copyright -->
        </div>
    </section>
    @include('layout/footer')