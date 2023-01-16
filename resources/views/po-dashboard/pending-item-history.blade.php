@include('layout/header', ['title' => $item_detail->description . ' History | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="mt-4">
                <div class="card">
                    <div class="card-body">
                        @include('layout/breadcrumb',
                        [
                            'breadcrumbs' => [
                                ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                                ['name' => 'Item Details List', 'route' => 'item-detail-list.all'],
                                ['name' => 'Edit item detail: <span class="badge bg-primary">' . $item_detail->description . '</span>', 'routeWithParam' => route('view-item-detail.show', ['item_detail_id' => $item_detail->id])],
                                ['name' => '<span class="badge bg-primary">' . $item_detail->description . '</span> History']
                            ]
                        ]
                        )
                        @if(count($item_detail->histories) === 0)
                            <div class="row">
                                <div class="col-12 small fst-italic text-secondary">NO LOGS FOR THIS ITEM YET</div>
                            </div>
                        @else
                        @php (
                            $latestData = $item_detail->histories[count($item_detail->histories) - 1]
                        )
                        <div class="row">
                            <div class="col-6">
                                <h2 class="h6 mb-3">Latest Pending Changes</h2>
                                <form @if($latestData->is_approve !== 1) action="{{ route('approve-pending-item.perform', ['item_details_id' => $item_detail->id]) }}" @endif method="POST">
                                    @if($latestData->is_approve !== 1)
                                        @csrf
                                        @method("PUT")
                                    @endif
                                    @php(
                                        $afterState = json_decode($latestData->after_change)
                                    )
                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-bold">Item Detail</label>
                                        <input type="text" class="form-control" id="description" name="description" value="{{$afterState->description}}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="article" class="form-label fw-bold">Article</label>
                                        <input type="text" class="form-control" id="article" name="article" value="{{$afterState->article}}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="unit_id" class="form-label fw-bold">Unit</label>
                                        <select class="form-select" aria-label="category" name="unit_id" id="unit_id" disabled>
                                            @foreach ($units as $unit)
                                                <option value="{{$unit->id}}" @if(intval($afterState->unit_id) === intval($unit->id)) selected @endif>{{$unit->uom}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="price_catalogue" class="form-label fw-bold">Price Catalogue</label>
                                        <input type="text" class="form-control" id="price_catalogue" name="price_catalogue" value="{{$afterState->price_catalogue}}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label fw-bold">Category</label>
                                        <select class="form-select" aria-label="category" name="category_id" id="category_id" disabled>
                                            @foreach ($categories as $category)
                                                <option value="{{$category->id}}" @if($afterState->category_id === $category->id) selected @endif>{{$category->description}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        @if($latestData->is_approve === 1)
                                            <button class="btn btn-primary" disabled><em class="bi bi-check-all"></em> Already Live</button>
                                        @else
                                            <button class="btn btn-primary"><em class="bi bi-check-all"></em> Approve changes</button>
                                        @endif
                                    </div>
                                </form>
                            </div>

                            <div class="col-6">
                                <div class="table-responsive">
                                    <h2 class="h6 mb-3">Logs</h2>
                                    <table class="table table-bordered table-small" id="item-history-logs">
                                        <caption class="fst-italic">{{$item_detail->description}} history logs</caption>
                                        <thead>
                                            <tr>
                                                <th style="width: 50%;">Changes</th>
                                                <th style="width: 25%;">Date Added</th>
                                                <th style="width: 25%;">Action By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = count($item_detail->histories)-1; $i >= 0; $i --)
                                                <tr>
                                                    <td>
                                                        @foreach(json_decode($item_detail->histories[$i]->changes) as $changes)
                                                            <div class="mb-3 border p-1">
                                                                {{$changes}}
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                    <td>{{$item_detail->histories[$i]->created_at}}</td>
                                                    <td>{{$item_detail->histories[$i]->user->profile->first_name}} {{$item_detail->histories[$i]->user->profile->last_name}}</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
{{-- <script src="{{asset('build/assets/app.b487754a.js')}}"></script> --}}
@include('layout/datatable', ['tableId' => 'item-history-logs'])
@include('layout/footer')