@include('layout/header', ['title' => 'Source of Funds | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="modal fade" id="addNewSourceofFund" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('source-of-fund.add') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Source of Fund</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="source_of_fund" class="form-label">Source of Fund</label>
                        <input type="text" class="form-control" id="source_of_fund" name="source_of_fund" placeholder="Source of Fund">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><em class="bi bi-save"></em> Save Source of Fund</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editSourceofFund" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form onsubmit="return saveChanges(event)">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-cat-header-text">Edit Source of Fund</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="source_of_fund" class="form-label">Source of Fund</label>
                        <input type="text" class="form-control" id="edit_source_of_fund" name="source_of_fund" placeholder="Source of Fund">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="edit_description" name="description" placeholder="Description">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><em class="bi bi-save"></em> Save Source of Fund</button>
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
                        @include('layout/breadcrumb',
                        [
                            'breadcrumbs' => [
                                ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                                ['name' => 'Source of Fund List'],
                            ]
                        ]
                        )
                        <h1 class="h5 card-title"> <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($source_of_funds) }}</span></span></h1>
                        <div class="mb-4">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewSourceofFund"><em class="bi bi-folder-plus"></em> Add</button>
                            <button class="btn btn-danger" onclick="deleteRecord()"><em class="bi bi-trash"></em> Delete</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="source-of-fund-table">
                                <caption>Source of Fund List</caption>
                                <thead>
                                    <tr>
                                        <th style="width: 5%;"></th>
                                        <th class="text-center" style="width: 5%;">Edit</th>
                                        <th style="width: 60%;">Source of Fund</th>
                                        <th style="width: 20%;">Description</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($source_of_funds as $source_of_fund)
                                        <tr>
                                            <td>
                                                <div class="form-check w-100 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input source_of_fund-checkbox" type="checkbox" value="{{ $source_of_fund->id }}" id="source_of_fund{{ $source_of_fund->id }}" @if($source_of_fund->is_delete===1) disabled @endif>
                                                </div>
                                            </td>
                                            <td class="text-center"><button class="btn btn-success" onclick="openEdit({{ $source_of_fund->id }})" @if($source_of_fund->is_delete===1) disabled @endif><em class="bi bi-pencil-square"></em></button></td>
                                            <td>{{ $source_of_fund->source_of_fund }} @if($source_of_fund->is_delete===1) <span class="badge bg-secondary">Source of Funds Deleted</span> @else <button type="button" class="btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="deleteRecord({{$source_of_fund->id}})"><em class="bi bi-trash-fill"></em></button> @endif</td>
                                            <td>{{$source_of_fund->description}}</td>
                                            <td>{{$source_of_fund->created_at}}</td>
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
    let selectedSourceofFund = 0;

    async function openEdit(id) {
        selectedSourceofFund = id;
        await axios.get(`{{ url('/source-of-fund') }}/${id}`)
            .then(res => {
                $('#edit_source_of_fund').val(res.data.source_of_fund);
                $('#edit_description').val(res.data.description);
                $('#editSourceofFund').modal('toggle');
            })
            .catch(err => alert("Could not fetch the data. Please contact website administrator."));
    }

    async function saveChanges(e) {
        e.preventDefault();
        const data = {
            "source_of_fund" : $('#edit_source_of_fund').val(),
            "description" : $('#edit_description').val()
        };
        await axios.put(`{{ url('/source-of-fund') }}/${selectedSourceofFund}`, data)
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
            let allSelectedSourceofFund = $(".source_of_fund-checkbox");
            let toDelete = [];
            for (let i = 0; i < allSelectedSourceofFund.length; i ++) {
                if (allSelectedSourceofFund[i].checked) {
                    toDelete.push(allSelectedSourceofFund[i].value);
                }
            }
            if (toDelete.length > 0) {
                let confirmDeleteBatch = confirm("Are you sure to delete?");
                if (confirmDeleteBatch) {
                    await axios.post(`{{ route('source-of-fund.delete_batch') }}`, { id : toDelete })
                        .then(res => {
                            window.location.reload();
                        }).catch(err => {
                            window.location.reload();
                        });
                }
            } else {
                alert("Select source of fund to delete first!");
            }
        } else {
            let confirmDeleteSingle = confirm("Are you sure to delete this source of funds?");
            if (confirmDeleteSingle) {
                await axios.delete(`{{ url('source-of-fund/single') }}/${id}`)
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
@include('layout/datatable', ['tableId' => 'source-of-fund-table' , 'columnId' => '4'])
@include('layout/footer')
