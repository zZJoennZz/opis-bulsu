@include('layout/header', ['title' => 'Users List | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="mt-4">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h5 card-title">Add User</h1>
                        <hr />
                        <form action="{{route('add-new-user.perform')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-1 small text-secondary border-bottom">Account</div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="account_type" class="form-label">Account Type:</label>
                                        <select onchange="accountTypeOnChange(this.value)" name="account_type" id="account_type" class="form-select" required>
                                            <option value="0" selected>Select account type</option>
                                            @if (Auth::user()->account_type === 'admin')
                                                <option value="admin">Admin</option>
                                            @endif
                                            <option value="PROCUREMENT_OFFICE">Procurement Office</option>
                                            <option value="BUDGET_OFFICE">Budget Office</option>
                                            <option value="SUPPLY_OFFICE">Supply Office</option>
                                            <option value="END_USER">User</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3" id="dept-div">
                                        <span class="fst-italic">
                                            <label for="select-first" class="form-label">Select account type first!<span class="text-danger">*</span></label>
                                            <input type="text" id="select-first" class="form-control" disabled>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username:</label>
                                        <input type="text" id="username" name="username" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-1 small text-secondary border-bottom">User Profile</div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name:</label>
                                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3" id="dept-div">
                                        <label for="last_name" class="form-label">Last Name:</label>
                                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="positions_id" class="form-label">Position:</label>
                                        <select name="positions_id" id="positions_id" class="form-select" required>
                                            <option value="0" selected>Select position</option>
                                            @foreach ($positions as $position)
                                                <option value="{{$position->id}}">{{$position->description}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="email" class="form-label">Email Address:</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                    <small class="text-secondary fst-italic">The password reset link will be sent to this email address.</small>
                                </div>
                                <div class="col-12">
                                    <a href="{{route('users-list.show')}}" class="btn btn-danger">Cancel</a>
                                    <button class="btn btn-primary float-end fw-bold"><em class="bi bi-save"></em> Save User</button>
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
   
    let Positions = [
        @foreach($positions as $position)
        {
            'id' : {{$position->id}},
            'description' : '{{$position->description}}',
        },
        @endforeach
    ];

    async function accountTypeOnChange(e) {
        let deptDiv = $('#dept-div');
        let pos = $('#positions_id');
        deptDiv[0].innerHTML = "ASD";
        let branches;
        let position;
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
        } else if(e === "PROCUREMENT_OFFICE"){
            branches = allBranches.filter(d => d.branch_name === "Procurement Office");
            deptDiv[0].innerHTML = `
                <label for="branches_id" class="form-label">Department:</label>
                <select name="branches_id" id="branches_id" class="form-select" required>
                    ${
                        branches.map(d =>
                            `<option value="${d.id}" selected>${d.branch_name}</option>`
                        )
                    }
                </select>
            `;
            position = Positions.filter(dx => dx.description === "Procurement Staff");
            pos.html( `
                    ${
                        position.map(dx => 
                            `<option value="${dx.id}" selected>${dx.description}</option>`
                        )
                    }                              
            `);
        } else if(e === "BUDGET_OFFICE"){
            branches = allBranches.filter(d => d.branch_name === "Budget Office");
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
            position = Positions.filter(dx => dx.description === "Budget Office Staff");
            pos.html( `
                    ${
                        position.map(dx => 
                            `<option value="${dx.id}" selected>${dx.description}</option>`
                        )
                    }                              
            `);
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
            pos.html( `
                    ${
                        Positions.map(dx => 
                            `<option value="${dx.id}" selected>${dx.description}</option>`
                        )
                    }                              
            `);
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
        position = null;
    }
</script>
{{-- <label for="account_type" class="form-label">Select first</label>
<select name="account_type" id="account_type" class="form-select" required>
    <option value="0" selected>Select account type</option>
    @if (Auth::user()->account_type === 'admin')
        <option value="admin">Admin</option>
    @endif
    <option value="PROCUREMENT_OFFICE">Procurement Office</option>
    <option value="BUDGET_OFFICE">Budget Office</option>
    <option value="SUPPLY_OFFICE">Supply Office</option>
    <option value="END_USER">User</option>
</select> --}}
@include('layout/footer')