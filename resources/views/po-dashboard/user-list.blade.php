<x-dashboard-layout>
    <x-slot:title>
        Users List
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'Users List']
        ]
        @endphp

        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <h1 class="h5 card-title"><span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($users) }}</span></span>
        </h1>
        <div class="mb-4">
            <a class="btn btn-primary" href="{{ route('add-new-user.show') }}"><em class="bi bi-folder-plus"></em> Add</a>
            {{-- <button class="btn btn-danger" onclick="deleteRecord()"><em class="bi bi-trash"></em> Delete</button> --}}
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover" id="users-list-table">
                <caption>Users List</caption>
                <thead>
                    <tr>
                        <th class="p-2" style="width: 5%;"></th>
                        <th class="text-center p-2" style="width: 5%;">Edit</th>
                        <th class="p-2" style="width: 20%;">College / Department</th>
                        <th class="p-2" style="width: 25">Name (<small>Username</small>)</th>
                        <th class="p-2" style="width: 10%">Account Type</th>
                        <th class="p-2" style="width: 20%">Date Added</th>
                        <th class="p-2 text-end" style="width: 10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>
                            {{-- <div class="form-check w-100 d-flex align-items-center justify-content-center">
                                @if ($user->account_type !== "admin")
                                <input class="form-check-input item-checkbox" type="checkbox" value="{{ $user->id }}" id="category{{ $user->id }}"
                                    @if($user->is_active!==1) disabled @endif>
                                @endif
                            </div> --}}
                        </td>
                        <td class="text-center">
                            @if ($user->account_type === "admin")
                            <span class="badge bg-warning">N/A</span>
                            @else
                            <a class="btn btn-sm btn-success" @if($user->is_active!==1) style="opacity: 0.5;" @else href="{{ route('view-user.show',
                                ['user_id' => $user->id]) }}" @endif><em class="bi bi-pencil-square"></em></a>
                            @endif
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
                            @if ($user->account_type === "PROCUREMENT_OFFICE" || $user->account_type === "PROCUREMENT_HEAD")
                            <span class="badge text-bg-success"><em class="bi bi-bag-fill"></em> Procurement</span>
                            @endif
                            @if ($user->account_type === "BUDGET_OFFICE")
                            <span class="badge text-bg-primary"><em class="bi bi-cash-stack"></em> Budget</span>
                            @endif
                            @if ($user->account_type === "END_USER")
                            <span class="badge text-bg-secondary"><em class="bi bi-person-fill"></em> End User</span>
                            @endif
                            @if ($user->account_type === "SUPPLY_OFFICE")
                            <span class="badge text-bg-warning"><em class="bi bi-truck"></em> Supply</span>
                            @endif
                        </td>
                        <td class="p-2 small">
                            {{ date_format($user->created_at,"M d, Y h:i:s A")}}
                        </td>
                        <td class="p-2 text-end">
                            @if ($user->account_type !== "admin")
                            @if($user->is_active === 1)
                            <a href="javascript:void(0)" id="status{{$user->id}}" title="off" onclick="status('{{$user->id}}','{{$user->is_active}}')"
                                class="status__id"><span class="badge text-bg-success" title=""><em class="bi bi-person-fill-check"></em>
                                    Active</span></a>
                            @else
                            <a href="javascript:void(0)" id="status{{$user->id}}" title="on" onclick="status('{{$user->id}}','{{$user->is_active}}')"
                                class="status__id"><span class="badge text-bg-warning" title=""><em class="bi bi-person-fill-slash"></em>
                                    Inactive</span></a>
                            @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-slot:additional_script>
            @include('layout/datatable', ["tableId" => "users-list-table"])
            </x-slot>
</x-dashboard-layout>