@include('layout/header', ['title' => 'Users List | OPIS - BulSU e-PROCUREMENT'])
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
                                ['name' => 'Users List']
                            ]
                        ]
                        )
                        <h1 class="h5 card-title"><span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($users) }}</span></span></h1>
                        <div class="mb-4">
                            <a class="btn btn-primary" href="{{ route('add-new-user.show') }}"><em class="bi bi-folder-plus"></em> Add</a>
                            <button class="btn btn-danger" onclick="deleteRecord()"><em class="bi bi-trash"></em> Delete</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="users-list-table">
                                <caption>Users List</caption>
                                <thead>
                                    <tr>
                                        <th class="p-2" style="width: 5%;"></th>
                                        <th class="text-center p-2" style="width: 5%;">Edit</th>
                                        <th class="p-2" style="width: 20%;">College / Department</th>
                                        <th class="p-2" style="width: 25">Name (Username)</th>
                                        <th class="p-2" style="width: 10%">Account Type</th>
                                        <th class="p-2" style="width: 20%">Date Added</th>
                                        <th class="p-2" style="width: 10%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <div class="form-check w-100 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input item-checkbox" type="checkbox" value="{{ $user->id }}" id="category{{ $user->id }}" @if($user->is_active!==1) disabled @endif>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-success" @if($user->is_active!==1) style="opacity: 0.5;" @else href="{{ route('view-user.show', ['user_id' => $user->id]) }}" @endif><em class="bi bi-pencil-square"></em></a>
                                            </td>
                                            <td class="p-2">
                                                {{ $user->branch->branch_name }}
                                            </td>
                                            <td class="p-2">
                                                {{ $user->profile->first_name }} {{ $user->profile->last_name }} <small class="text-muted">({{ $user->username }})</small>
                                            </td>
                                            <td class="p-2">
                                                @if ($user->account_type === "admin")
                                                    <span class="badge text-bg-info"><em class="bi bi-shield-shaded"></em> Admin</span>
                                                @endif
                                                @if ($user->account_type === "PROCUREMENT_OFFICE")
                                                    <span class="badge text-bg-success"><em class="bi bi-bag-fill"></em> Procurement</span>
                                                @endif
                                                @if ($user->account_type === "BUDGET_OFFICE")
                                                    <span class="badge text-bg-primary"><em class="bi bi-cash-stack"></em> Budget</span>
                                                @endif
                                                @if ($user->account_type === "END_USER")
                                                    <span class="badge text-bg-secondary"><em class="bi bi-person-fill"></em> End User</span>
                                                @endif
                                            </td>
                                            <td class="p-2 small">
                                                {{ date_format($user->created_at,"M d, Y h:i:s A")}}
                                            </td>
                                            <td class="p-2">
                                                {{-- @if ($user->is_active)
                                                    <span class="badge text-bg-success"><em class="bi bi-person-fill-check"></em> Active</span>
                                                @else
                                                    <span class="badge text-bg-warning"><em class="bi bi-person-fill-slash"></em> Inactive</span>
                                                @endif
                                                 --}}
                                                @if($user->is_active==1)
                                                    <a href="javascript:void(0)" id="status{{$user->id}}" title="off" onclick="status('{{$user->id}}','{{$user->is_active}}')" class="status__id"><span class="badge text-bg-success"><em class="bi bi-person-fill-check"></em> Active</span></a>
                                                @else
                                                    <a href="javascript:void(0)" id="status{{$user->id}}" title="on" onclick="status('{{$user->id}}','{{$user->is_active}}')" class="status__id"><span class="badge text-bg-warning"><em class="bi bi-person-fill-slash"></em> Inactive</span></a>
                                                @endif
                                            </td>
                                        </tr>
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
@include('layout/datatable', ["tableId" => "users-list-table"])
@include('layout/footer')