    @include('layout/header', ['title' => $item_detail[0]->description . ' | OPIS - BulSU e-PROCUREMENT'])
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">OPIS</a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            {{-- <input class="form-control form-control-dark w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search"> --}}
            <div class="w-100"></div>
            <div class="navbar-nav">
                <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="{{ route('logout.perform') }}">Logout</a>
                </div>
            </div>
        </header>
        <div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3 sidebar-sticky">
                        <ul class="nav flex-column">
                            @include('layout/enduser_sidebar')
                        </ul>
                    </div>
                </nav>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger mt-3 mb-3" role="alert">
                                {{$error}}
                            </div>
                        @endforeach
                    @endif
                    <div class="p-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="cart-title" style="border-bottom: 1px solid gray; padding-bottom: 1rem;">{{ $item_detail[0]->description }}</h5>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="fs-3 mb-3">Order Item Details</div>
                                        <div class="mb-3">
                                            <label for="ppmp_year" class="form-label">PPMP Year:</label>
                                            <input type="text" class="form-control" id="ppmp_year" value="{{ $ppmp_year }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description:</label>
                                            <input type="text" class="form-control" id="description" value="{{ $item_detail[0]->description }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="ppmp_year" class="form-label">Price:</label>
                                            <input type="text" class="form-control" id="ppmp_year" value="{{ $item_detail[0]->price_catalogue }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="uom" class="form-label">Unit of Measurement:</label>
                                            <input type="text" class="form-control" id="uom" value="{{ $item_detail[0]->uom }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cat_desc" class="form-label">Category:</label>
                                            <input type="text" class="form-control" id="cat_desc" value="{{ $item_detail[0]->cat_desc }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <form action="{{ route('item-detail-single.add', request()->segment(count(request()->segments()))) }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-4 col-md-12 mb-3">
                                                    <div class="mb-3">
                                                        <label for="source_of_funds_id" class="form-label">Source of Fund:</label>
                                                        <select class="form-select" id="source_of_funds_id" name="source_of_funds_id" aria-label="Purpose of the item">
                                                            @foreach ($source_of_funds as $source)
                                                                <option value="{{$source->id}}">{{$source->source_of_fund}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8 col-md-12 mb-3">
                                                    <div class="mb-3">
                                                        <label for="item_purposes_id" class="form-label">Purpose:</label>
                                                        <select class="form-select" id="item_purposes_id" name="item_purposes_id" aria-label="Purpose of the item">
                                                            @foreach ($item_purposes as $purpose)
                                                                <option value="{{$purpose->id}}">{{$purpose->description}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                @foreach (json_decode($ppmp_format) as $format)
                                                <div class="col-lg-4 col-md-12 mb-3 d-flex" style="align-items: center; justify-content: center;">
                                                    <label class="me-2" for="{{ $format->id }}">{{ $format->name }}:</label>
                                                    <input class="w-100 form-control" type="number" id="{{ $format->id }}" name="{{ $format->id }}" value="0" required />
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <label for="estimated_budget" class="form-label">Estimated Budget:</label>
                                                    <input type="text" class="form-control" name="estimated_budget" id="estimated_budget" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-12 mb-3 d-flex" style="align-items: center; justify-content: center;">
                                                    <input class="form-check-input me-2" type="checkbox" value="yes" id="is_priority" name="is_priority">
                                                    <label class="form-check-label" for="is_priority">
                                                        Is Priority?
                                                    </label>
                                                </div>
                                                <div class="col-lg-9 col-md-12 mb-3">
                                                    <label for="remarks" class="form-label">Remarks:</label>
                                                    <input type="text" class="form-control" name="remarks" id="remarks">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 d-flex" style="justify-content: end">
                                                    <button type="submit" class="btn btn-primary me-2">Add to PPMP Cart</button>
                                                    <a href="/dashboard" class="btn btn-danger">Cancel</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <style>
            .feather {
            width: 16px;
            height: 16px;
            }

            /*
            * Sidebar
            */

            .sidebar {
            position: fixed;
            top: 0;
            /* rtl:raw:
            right: 0;
            */
            bottom: 0;
            /* rtl:remove */
            left: 0;
            z-index: 100; /* Behind the navbar */
            padding: 48px 0 0; /* Height of navbar */
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            }

            @media (max-width: 767.98px) {
            .sidebar {
                top: 5rem;
            }
            }

            .sidebar-sticky {
            height: calc(100vh - 48px);
            overflow-x: hidden;
            overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
            }

            .sidebar .nav-link {
            font-weight: 500;
            color: #333;
            }

            .sidebar .nav-link .feather {
            margin-right: 4px;
            color: #727272;
            }

            .sidebar .nav-link.active {
            color: #2470dc;
            }

            .sidebar .nav-link:hover .feather,
            .sidebar .nav-link.active .feather {
            color: inherit;
            }

            .sidebar-heading {
            font-size: .75rem;
            }

            /*
            * Navbar
            */

            .navbar-brand {
            padding-top: .75rem;
            padding-bottom: .75rem;
            background-color: rgba(0, 0, 0, .25);
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
            }

            .navbar .navbar-toggler {
            top: .25rem;
            right: 1rem;
            }

            .navbar .form-control {
            padding: .75rem 1rem;
            }

            .form-control-dark {
            color: #fff;
            background-color: rgba(255, 255, 255, .1);
            border-color: rgba(255, 255, 255, .1);
            }

            .form-control-dark:focus {
            border-color: transparent;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
            }
        </style>
    @include('layout/footer')