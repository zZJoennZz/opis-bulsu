@include('layout/header', ['title' => 'View ' . $user->profile->first_name . ' ' . $user->profile->last_name . ' | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="mt-4">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h5 card-title">{{$user->profile->first_name}} {{$user->profile->last_name}}</h1>
                        <hr />
                        @if ($errors->any())
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger mb-3" role="alert">
                                    {{ $error }}
                                </div>
                            @endforeach
                        @endif
                        @if( Session::has('success') )
                            <div class="alert alert-success mt-3 mb-3" role="alert">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        {{-- <form action="{{route('add-new-user.perform')}}" method="POST"> --}}
                        <form onsubmit="submitForm(event)">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-1 small text-secondary border-bottom">Account</div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="account_type" class="form-label">Account Type:</label>
                                        <select onchange="accountTypeOnChange(this.value)" name="account_type" id="account_type" class="form-select" required>
                                            @if (Auth::user()->account_type === 'admin')
                                                <option value="admin" @if($user->account_type==="admin") selected @endif>Admin</option>
                                            @endif
                                            <option value="PROCUREMENT_OFFICE" @if($user->account_type==="PROCUREMENT_OFFICE") selected @endif>Procurement Office</option>
                                            <option value="BUDGET_OFFICE" @if($user->account_type==="BUDGET_OFFICE") selected @endif>Budget Office</option>
                                            <option value="SUPPLY_OFFICE" @if($user->account_type==="SUPPLY_OFFICE") selected @endif>Supply Office</option>
                                            <option value="END_USER" @if($user->account_type==="END_USER") selected @endif>User</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3" id="dept-div">
                                        @if ($user->account_type !== "END_USER")
                                            <label for="account_type" class="form-label">Department:</label>
                                            <select onchange="accountTypeOnChange(this.value)" name="branches_id" id="branches_id" class="form-select" required>
                                            @foreach ($branches as $branch)
                                                @if($branch->type !== "CAMPUS")
                                                    <option value="{{$branch->id}}" @if($user->branches_id===$branch->id) selected @endif>{{$branch->branch_name}}</option>
                                                @endif
                                            @endforeach
                                            </select>
                                        @endif
                                        @if ($user->account_type === "END_USER")
                                            <label for="account_type" class="form-label">College:</label>
                                            <select onchange="accountTypeOnChange(this.value)" name="branches_id" id="branches_id" class="form-select" required>
                                            @foreach ($branches as $branch)
                                                @if($branch->type === "CAMPUS")
                                                    <option value="{{$branch->id}}" @if($user->branches_id===$branch->id) selected @endif>{{$branch->branch_name}}</option>
                                                @endif
                                            @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username:</label>
                                        <input type="text" id="username" name="username" value="{{$user->username}}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-1 small text-secondary border-bottom">User Profile</div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name:</label>
                                        <input type="text" id="first_name" name="first_name" value="{{$user->profile->first_name}}"  class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3" id="dept-div">
                                        <label for="last_name" class="form-label">Last Name:</label>
                                        <input type="text" id="last_name" name="last_name" value="{{$user->profile->last_name}}"  class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="positions_id" class="form-label">Position:</label>
                                        <select name="positions_id" id="positions_id" class="form-select" required>
                                            <option value="0" selected>Select position</option>
                                            @foreach ($positions as $position)
                                                <option value="{{$position->id}}" @if($user->profile->positions_id===$position->id) selected @endif>{{$position->description}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="email" class="form-label">Email Address:</label>
                                    <input type="email" name="email" id="email" value="{{$user->email}}" class="form-control" required>
                                    <small class="text-secondary fst-italic">Please make sure the email address is active!</small>
                                </div>
                                <div class="col-12">
                                    <a href="{{route('users-list.show')}}" class="btn btn-danger">Cancel</a>
                                    <button class="btn btn-primary float-end fw-bold"><em class="bi bi-save"></em> Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
<script src="{{asset('build/assets/app.b487754a.js')}}"></script>
<script>
    let allBranches = [
        @foreach ($branches as $branch)
        {
            'id' : {{$branch->id}},
            'branch_name' : '{{$branch->branch_name}}',
            'type' : '{{$branch->type}}'
        },
        @endforeach
    ];

    async function submitForm(e) {
        e = e || window.event;
        e.preventDefault();
        let frmData = new FormData(e.target);
        frmData.append("id", "{{$user->id}}");
        frmData.append("profile_id", "{{$user->profile->id}}");
        await axios.post(`{{route('view-user.update')}}`, frmData)
            .then(res => {
                window.location.reload();
            })
            .catch(err => {
                alert("Something went wrong. User changes is not made. Please make sure username and email address is unique to this user.");
                window.location.reload();
            });
        e = null;
        return true;
    }

    async function accountTypeOnChange(e) {
        let deptDiv = $('#dept-div');
        deptDiv[0].innerHTML = "ASD";
        let branches;
        if (e === "END_USER") {
            branches = allBranches.filter(d => d.type === "CAMPUS");
            deptDiv[0].innerHTML = `
                <label for="branches_id" class="form-label">College:</label>
                <select name="branches_id" id="branches_id" class="form-select" required>
                    <option value="0" selected>Select college</option>
                    ${
                        branches.map(d =>
                            `<option value="${d.id}">${d.branch_name}</option>`
                        )
                    }
                </select>
            `;
        } else {
            branches = allBranches.filter(d => d.type !== "CAMPUS");
            deptDiv[0].innerHTML = `
                <label for="branches_id" class="form-label">Department:</label>
                <select name="branches_id" id="branches_id" class="form-select" required>
                    <option value="0" selected>Select department</option>
                    ${
                        branches.map(d =>
                            `<option value="${d.id}">${d.branch_name}</option>`
                        )
                    }
                </select>
            `;
        }

        if (e === "0") {
            deptDiv[0].innerHTML = `
            <span class="fst-italic">
                <label for="select-first" class="form-label">Select account type first!<span class="text-danger">*</span></label>
                <input type="text" id="select-first" class="form-control" disabled>
            </span>
            `;
        }
        branches = null;
    }
</script>
@include('layout/footer')