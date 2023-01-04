@include('layout/header', ['title' => 'Item Branch | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="modal fade" id="addNewBranch" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('branch.add') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New Branch</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="branch_name" class="form-label">Branch Name</label>
                        <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Branch Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <input type="text" class="form-control" id="type" name="type" placeholder="Type" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_address" class="form-label">Email Address</label>
                        <input type="text" class="form-control" id="email_address" name="email_address" placeholder="Email Address" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="number" class="form-control" id="contact_number" name="contact_number" placeholder="Contact Number" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><em class="bi bi-save"></em> Save Branch</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editBranch" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form onsubmit="return saveChanges(event)">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-cat-header-text">Edit Item Branch</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="branch_name" class="form-label">Branch Name</label>
                        <input type="text" class="form-control" id="edit_branch_name" name="branch_name" placeholder="Branch Name">
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <input type="text" class="form-control" id="edit_type" name="type" placeholder="Type">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="edit_address" name="address" placeholder="Address">
                    </div>
                    <div class="mb-3">
                        <label for="email_address" class="form-label">Email Address</label>
                        <input type="text" class="form-control" id="edit_email_address" name="email_address" placeholder="Email Address">
                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="number" class="form-control" id="edit_contact_number" name="contact_number" placeholder="Contact Number">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><em class="bi bi-save"></em> Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h5 card-title">Item Branch List <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($branches) }}</span></span></h1>
                        <hr />
                        <div class="mb-4">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewBranch"><em class="bi bi-folder-plus"></em> Add</button>
                            <button class="btn btn-danger" onclick="deleteRecord()"><em class="bi bi-trash"></em> Delete</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="branch-table">
                                <caption>Branch List</caption>
                                <thead>
                                    <tr>
                                        <th style="width: 5%;"></th>
                                        <th class="text-center" style="width: 5%;">Edit</th>
                                        <th style="">Branch Name</th>
                                        <th style="">Type</th>
                                        <th style="">Address</th>
                                        <th style="">Email</th>
                                        <th style="">Contact No.</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($branches as $branch)
                                        <tr>
                                            <td>
                                                <div class="form-check w-100 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input branch-checkbox" type="checkbox" value="{{ $branch->id }}" id="branch{{ $branch->id }}" @if($branch->is_delete===1) disabled @endif>
                                                </div>
                                            </td>
                                            <td class="text-center"><button class="btn btn-success" onclick="openEdit({{ $branch->id }})" @if($branch->is_delete===1) disabled @endif><em class="bi bi-pencil-square"></em></button></td>
                                            <td>{{ $branch->branch_name }} @if($branch->is_delete===1) <span class="badge bg-secondary">Item Branch Deleted</span> @else <button type="button" class="btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="deleteRecord({{$branch->id}})"><em class="bi bi-trash-fill"></em></button> @endif</td>
                                            <td>{{ $branch->type }}</td>
                                            <td>{{ $branch->address }}</td>
                                            <td>{{ $branch->email_address }}</td>
                                            <td>{{ $branch->contact_number }}</td>
                                            <td>{{ $branch->created_at }}</td>
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
<script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
<script>
    let selectedBranch = 0;

    async function openEdit(id) {
        selectedBranch = id;
        await axios.get(`{{ url('/branch') }}/${id}`)
            .then(res => {
                $('#edit_branch_name').val(res.data.branch_name);
                $('#edit_type').val(res.data.type);
                $('#edit_address').val(res.data.address);
                $('#edit_email_address').val(res.data.email_address);
                $('#edit_contact_number').val(res.data.contact_number);
                $('#editBranch').modal('toggle');
            })
            .catch(err => alert("Could not fetch the data. Please contact website administrator."));
    }

    async function saveChanges(e) {
        e.preventDefault();
        const data = {
            "branch_name" : $('#edit_branch_name').val(),
            "type" : $('#edit_type').val(),
            "address" : $('#edit_address').val(),
            "email_address" : $('#edit_email_address').val(),
            "contact_number" : $('#edit_contact_number').val(),
        };
        await axios.put(`{{ url('/branch') }}/${selectedBranch}`, data)
            .then(res => {
                {{Session::forget('success');}}
                window.location.reload();
            })
            .catch(err => {
                window.location.reload();
                alert("Could not fetch the data. Please contact website administrator.");
                console.log("Could not fetch the data. Please contact website administrator.");
            });

        return false;
    }

    async function deleteRecord(id = null){
        if (id === null) {
            let allSelectedBranch = $(".branch-checkbox");
            let toDelete = [];
            for (let i = 0; i < allSelectedBranch.length; i ++) {
                if (allSelectedBranch[i].checked) {
                    toDelete.push(allSelectedBranch[i].value);
                }
            }
            if (toDelete.length > 0) {
                let confirmDeleteBatch = confirm("Are you sure to delete?");
                if (confirmDeleteBatch) {
                    await axios.post(`{{ route('branch.delete_batch') }}`, { id : toDelete })
                        .then(res => {
                            window.location.reload();
                        }).catch(err => {
                            window.location.reload();
                        });
                }
            } else {
                alert("Select item branch to delete first!");
            }
        } else {
            let confirmDeleteSingle = confirm("Are you sure to delete this item branch?");
            if (confirmDeleteSingle) {
                await axios.delete(`{{ url('branch/single') }}/${id}`)
                    .then(res => {
                        window.location.reload();
                    })
                    .catch(err => {
                        window.location.reload();
                    });
            }
        }
    }
</script>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/datatable', ['tableId' => 'branch-table', 'columnId' => '7'])
@include('layout/footer')
