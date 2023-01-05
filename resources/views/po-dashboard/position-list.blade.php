@include('layout/header', ['title' => 'Position | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="modal fade" id="addNewPosition" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('position.add') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Position</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><em class="bi bi-save"></em> Save Position</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editPosition" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form onsubmit="return saveChanges(event)">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-cat-header-text">Edit Position</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="edit_description" name="description" placeholder="Description">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><em class="bi bi-save"></em> Save Position</button>
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
                        <h1 class="h5 card-title">Position List <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($positions) }}</span></span></h1>
                        <hr />
                        <div class="mb-4">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewPosition"><em class="bi bi-folder-plus"></em> Add</button>
                            <button class="btn btn-danger" onclick="deleteRecord()"><em class="bi bi-trash"></em> Delete</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="position-table">
                                <caption>Position</caption>
                                <thead>
                                    <tr>
                                        <th style="width: 5%;"></th>
                                        <th class="text-center" style="width: 5%;">Edit</th>
                                        <th style="width: 80%;">Description</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($positions as $position)
                                        <tr>
                                            <td>
                                                <div class="form-check w-100 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input position-checkbox" type="checkbox" value="{{ $position->id }}" id="position{{ $position->id }}" @if($position->is_delete===1) disabled @endif>
                                                </div>
                                            </td>
                                            <td class="text-center"><button class="btn btn-success" onclick="openEdit({{ $position->id }})" @if($position->is_delete===1) disabled @endif><em class="bi bi-pencil-square"></em></button></td>
                                            <td>{{ $position->description }} @if($position->is_delete===1) <span class="badge bg-secondary">Position Deleted</span> @else <button type="button" class="btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="deleteRecord({{$position->id}})"><em class="bi bi-trash-fill"></em></button> @endif</td>
                                            <td>{{ $position->created_at }}</td>
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
    let selectedPosition = 0;

    async function openEdit(id) {
        selectedPosition = id;
        await axios.get(`{{ url('/position') }}/${id}`)
            .then(res => {
                $('#edit_description').val(res.data.description);
                $('#editPosition').modal('toggle');
            })
            .catch(err => alert("Could not fetch the data. Please contact website administrator."));
    }

    async function saveChanges(e) {
        e.preventDefault();
        const data = {
            "description" : $('#edit_description').val(),
        };
        await axios.put(`{{ url('/position') }}/${selectedPosition}`, data)
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
            let allSelectedPosition = $(".position-checkbox");
            let toDelete = [];
            for (let i = 0; i < allSelectedPosition.length; i ++) {
                if (allSelectedPosition[i].checked) {
                    toDelete.push(allSelectedPosition[i].value);
                }
            }
            if (toDelete.length > 0) {
                let confirmDeleteBatch = confirm("Are you sure to delete?");
                if (confirmDeleteBatch) {
                    await axios.post(`{{ route('position.delete_batch') }}`, { id : toDelete })
                        .then(res => {
                            window.location.reload();
                        }).catch(err => {
                            window.location.reload();
                        });
                }
            } else {
                alert("Select position to delete first!");
            }
        } else {
            let confirmDeleteSingle = confirm("Are you sure to delete this position?");
            if (confirmDeleteSingle) {
                await axios.delete(`{{ url('position/single') }}/${id}`)
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
@include('layout/datatable', ['tableId' => 'position-table', 'columnId' => '3'])
@include('layout/footer')
