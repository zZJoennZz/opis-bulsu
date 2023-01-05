@include('layout/header', ['title' => 'Pending Item Details Changes | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="mt-4">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h5 card-title">Pending Item Detail Updates</h1>
                        <hr />
                        <div class="table-responsive">
                            <table class="table table-small table-bordered" id="pending-item-changes">
                                <caption>Pending item details changes made by end users</caption>
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Updates By</th>
                                        <th>Review</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item_details as $item)
                                        @php ($historyCount = count($item->histories))
                                        @if($historyCount >= 1)
                                            @if ($item->histories[$historyCount - 1]->is_approve === 0)
                                                <tr>
                                                    <td>{{$item->histories[$historyCount - 1]->item_detail->description}}</td>
                                                    <td>{{$item->histories[$historyCount - 1]->user->profile->first_name}} {{$item->histories[$historyCount - 1]->user->profile->last_name}}</td>
                                                    <td><a href="{{ route('pending-item-detail.single', ['item_detail_id' => $item->id]) }}" class="btn btn-primary"><em class="bi bi-eye"></em></a></td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
{{-- <script src="{{asset('build/assets/app.b487754a.js')}}"></script> --}}
@include('layout/datatable', ['tableId' => 'pending-item-changes'])
@include('layout/footer')