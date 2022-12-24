@include('layout/header', ['title' => 'Add New Item Detail | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="p-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <h1>Add New Item Detail Form</h1>
                        </div>
                        <div class="mb-3">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm"><em class="bi bi-caret-left-fill"></em> Go Back</a>
                        </div>
                        <div class="mb-3">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger mt-3 mb-3" role="alert">
                                        {{$error}}
                                    </div>
                                @endforeach
                            @endif
                            @if (Session::get('success') !== null)
                                <div class="alert alert-success mt-3 mb-3" role="alert">
                                    {{ Session::get('success') }}
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <form action="{{ route('add-new-item.perform') }}" method="post">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="description" name="description" placeholder="Item name" required>
                                    <label for="description">Item name</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="article" name="article" placeholder="Item article" required>
                                    <label for="article">Item article</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="unit_id" name="unit_id" aria-label="Unit of measurement">
                                        <option value="0" selected>Select unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->uom }}</option>
                                        @endforeach
                                    </select>
                                    <label for="unit_id">Unit of Measurement</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="price_catalogue" name="price_catalogue" placeholder="Price catalogue" required>
                                    <label for="article">Price catalogue</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="category_id" name="category_id" aria-label="Item category">
                                        <option value="0" selected>Select item category</option>
                                        @foreach ($item_categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->description }}</option>
                                        @endforeach
                                    </select>
                                    <label for="category_id">Category</label>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex" style="justify-content: end;">
                                        <button type="submit" class="btn btn-primary me-2"><em class="bi bi-save"></em> Save Item Detail</button>
                                        <a href="{{ route('dashboard.show') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/footer')