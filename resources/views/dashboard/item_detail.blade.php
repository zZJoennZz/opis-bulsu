    @include('layout/header', ['title' => $item_detail[0]->description . ' | OPIS - BulSU e-PROCUREMENT'])
        @include('layout/member_header')
        <div class="container-fluid">
            <div class="row">
                @include('layout/sidebar')

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
                                        <div class="mb-3">
                                            <a href={{ route('view-item-detail.show',['item_detail_id' => $item_detail[0]->id]) }} class="btn btn-success" style="font-size: 0.7rem;"><em class="bi bi-pencil-square"></em> Edit this item</a>
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
                                            @foreach (json_decode($ppmp_format) as $format)
                                            <div class="row">
                                                <div class="col-lg-4 col-md-12 mb-1 d-flex" style="align-items: center; justify-content: center;">
                                                    <label class="me-2"  style="max-width:50px;width:100%;" for="{{ $format->id }}">{{ $format->name }}:</label>
                                                    <input class="w-100 form-control py-1" type="number" id="{{ $format->id }}" name="{{ $format->id }}" value="0" required />
                                                </div>
                                            </div>
                                            @endforeach
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
        <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
    @include('layout/footer')