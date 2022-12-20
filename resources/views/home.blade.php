    @include('layout/header', ['title' => 'OPIS - BulSU e-PROCUREMENT'])

        <div>
            <header class="bg-dark d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                    <img src="{{ asset('/img/logo.png') }}" class="w-50" alt="BulSU Logo" />
                </a>
            </header>
            <div class="col-xl-10 col-xxl-8 px-4 py-5" style="width: 100%;">
                <div class="row align-items-center g-lg-5 py-5 m-auto">
                    <div class="col-lg-7 text-center text-lg-start">
                        <div class="row">
                            <div class="col-xl-12 mb-3">
                                <h1>OPIS - e-PROCUREMENT v1.0</h1>
                            </div>
                            <div class="col-xl-6 mb-3">
                                <h3 class="mb-3">The BulSU Transparency Seal</h3>
                                <p>
                                    A pearl buried inside a tightly-shut shell is practically worthless. Government information is a pearl, meant to be shared with the public in order to maximize its inherent value.The Transparency Seal, depicted by a pearl shining out of an open shell, is a symbol of a policy shift towards openness in access to government information. On the one hand, it hopes to inspire Filipinos in the civil service to be more open to citizen engagement; on the other, to invite the Filipino citizenry to exercise their right to participate in governance…
                                </p>
                                <a class="btn btn-primary" href="https://bulsu.edu.ph/transparency-seal/" target="_blank" role="button">Read More</a>
                            </div>
                            <div class="col-xl-6 mb-3">
                                <h3 class="mb-3">About BulSU</h3>
                                <p>
                                    Bulacan State University (BulSU) is a state-funded institution of higher learning established in 1904 and converted into a university in 1993 by virtue of Republic Act 7665. The University in mandated to provide higher professional/technical and special instruction for special purpose and to promote research and extension services, advanced studies and extension services, advanced studies and progressive leadership in Engineering, Architecture, Education, Art and Science, Fine Arts, Information Technology, Technical courses, Commerce…
                                </p>
                                <a class="btn btn-primary" href="https://bulsu.edu.ph/about/" target="_blank" role="button">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10 mx-auto col-lg-5">
                        <form class="p-4 p-md-5 border rounded-3 bg-light" method="POST" action={{ route('login.perform') }}>
                            <div class="mb-3 d-flex" style="flex-direction: column;">
                                <img src="{{ asset('img/bsu-small-logo.png') }}" class="mx-auto mb-2" alt="BSU Small Logo" />
                                <div class="text-center text-uppercase">Bulacan State University</div>
                                <div class="text-center font-italic mb-3">Please login to proceed</div>
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
                            <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
                            <hr class="my-4">
                            <small class="text-muted text-center d-block">OPIS v1.0</small>
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @include('layout/footer')