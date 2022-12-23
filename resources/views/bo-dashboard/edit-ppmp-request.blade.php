@include('layout/header', ['title' => 'Edit PPMP | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3 sidebar-sticky">
                @if (Auth::user()->account_type === "admin")
                    <div class="p-2">
                        <div class="alert alert-primary d-flex align-items-center" role="alert">
                            <em class="bi bi-info-circle-fill"></em>
                            <div class="ms-2">
                                Admin View
                            </div>
                        </div>
                    </div>
                @endif
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('notification.show') }}">
                            <em class="bi bi-patch-exclamation-fill"></em>
                            Notification(s)
                            @if (count(Auth::user()->notifications->where('is_read', '=', 0)) > 0)
                                <span class="badge text-bg-danger">New!</span>
                            @endif
                        </a>
                    </li>
                </ul>
                <hr />
                @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "END_USER")
                    <ul class="nav flex-column">
                        @include('layout/enduser_sidebar')
                    </ul>
                    <hr />
                @endif

                @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "BUDGET_OFFICE")
                    <ul class="nav flex-column">
                        @include('layout/bo_sidebar')
                    </ul>
                    <hr />
                @endif
                
                @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "PROCUREMENT_OFFICE")
                    
                    <ul class="nav flex-column">
                        @include('layout/po_sidebar')
                    </ul>
                    <hr />
                @endif
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

            @if (isset($success))
                <div class="alert alert-success mt-3 mb-3" role="alert">
                    {{ $success }}
                </div>
            @endif

            @if( Session::has('success') )
                <div class="alert alert-success mt-3 mb-3" role="alert">
                    {{ Session::get('success') }}
                </div>
            @endif
            
            <div class="p-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="cart-title" style="border-bottom: 1px solid gray; padding-bottom: 1rem;">{{ $item_detail[0]->description }}</h5>
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="fs-3 mb-3 fw-bold">Edit PPMP Request</div>
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
                                <form action="{{ route('update-ppmp-record.perform', ["ppmp_id" => request()->segment(count(request()->segments()))]) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <div class="fs-3 fw-bold">Unit: {{ $ppmp_record->branch_name }}</div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 mb-3">
                                            <div class="mb-3">
                                                <label for="source_of_funds_id" class="form-label">Source of Fund:</label>
                                                <select class="form-select" id="source_of_funds_id" name="source_of_funds_id" aria-label="Purpose of the item">
                                                    @foreach ($source_of_funds as $source)
                                                        @if (intval($ppmp_record->source_of_funds_id) === intval($source->id))
                                                            <option value="{{$source->id}}" selected>{{$source->source_of_fund}}</option>
                                                        @else
                                                            <option value="{{$source->id}}">{{$source->source_of_fund}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-12 mb-3">
                                            <div class="mb-3">
                                                <label for="item_purposes_id" class="form-label">Purpose:</label>
                                                <select class="form-select" id="item_purposes_id" name="item_purposes_id" aria-label="Purpose of the item">
                                                    @foreach ($item_purposes as $purpose)
                                                        @if ($ppmp_record->item_purposes_id === $purpose->id)
                                                            <option value="{{$purpose->id}}" selected>{{$purpose->description}}</option>
                                                        @else
                                                            <option value="{{$purpose->id}}">{{$purpose->description}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @foreach (json_decode($ppmp_format) as $format)
                                        <div class="col-lg-4 col-md-12 mb-3 d-flex" style="align-items: center; justify-content: center;">
                                            <label class="me-2" for="{{ $format->id }}">{{ $format->name }}:</label>
                                            @foreach ($milestone_values as $milestone)
                                                @if ($milestone->milestone_value_id === $format->id)
                                                    <input class="w-100 form-control" type="number" id="{{ $format->id }}" name="{{ $format->id }}" value="{{ $milestone->milestone_value }}" required />
                                                @endif
                                            @endforeach
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="estimated_budget" class="form-label">Estimated Budget:</label>
                                            <input type="text" class="form-control" name="estimated_budget" id="estimated_budget" value="{{$ppmp_record->estimated_budget}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-12 mb-3 d-flex" style="align-items: center; justify-content: center;">
                                            @if ($ppmp_record->is_priority === 1)
                                                <input class="form-check-input me-2" type="checkbox" value="yes" id="is_priority" name="is_priority" checked>
                                            @else
                                                <input class="form-check-input me-2" type="checkbox" value="yes" id="is_priority" name="is_priority">
                                            @endif
                                            <label class="form-check-label" for="is_priority">
                                                Is Priority?
                                            </label>
                                        </div>
                                        <div class="col-lg-9 col-md-12 mb-3">
                                            <label for="remarks" class="form-label">Remarks:</label>
                                            <input type="text" class="form-control" name="remarks" id="remarks" value="{{ $ppmp_record->remarks }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex" style="justify-content: end;">
                                            <button type="submit" class="btn btn-primary me-2"><em class="bi bi-save2"></em> Update PPMP Record</button>
                                            <a href="{{ route('bo-new-ppmp-request.show', ['branch_id' => $ppmp_record->branches_id]) }}" class="btn btn-danger">Cancel</a>
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