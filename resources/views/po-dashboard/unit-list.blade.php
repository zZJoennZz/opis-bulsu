@include('layout/header', ['title' => 'Unit | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="modal fade" id="addNewUnit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('unit.add') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Unit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="uom" class="form-label">Description</label>
                        <input type="text" class="form-control" id="uom" name="uom" placeholder="Description">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><em class="bi bi-save"></em> Save Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editUnit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form onsubmit="return saveChanges(event)">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-cat-header-text">Edit Unit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="uom" class="form-label">Unit of Measurement</label>
                        <input type="text" class="form-control" id="edit_uom" name="uom" placeholder="Unit of Measurement">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><em class="bi bi-save"></em> Save Unit</button>
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
                                ['name' => 'Item Unit List'],
                            ]
                        ]
                        )
                        <h1 class="h5 card-title"><span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($units) }}</span></span></h1>
                        <div class="mb-4">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewUnit"><em class="bi bi-folder-plus"></em> Add</button>
                            <button class="btn btn-danger" onclick="deleteRecord()"><em class="bi bi-trash"></em> Delete</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="unit-table">
                                <caption>Unit List</caption>
                                <thead>
                                    <tr>
                                        <th style="width: 5%;"></th>
                                        <th class="text-center" style="width: 5%;">Edit</th>
                                        <th style="width: 80%;">Unit of Measurements</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($units as $unit)
                                        <tr>
                                            <td>
                                                <div class="form-check w-100 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input unit-checkbox" type="checkbox" value="{{ $unit->id }}" id="itemunit{{ $unit->id }}" @if($unit->is_delete===1) disabled @endif>
                                                </div>
                                            </td>
                                            <td class="text-center"><button class="btn btn-success" onclick="openEdit({{ $unit->id }})" @if($unit->is_delete===1) disabled @endif><em class="bi bi-pencil-square"></em></button></td>
                                            <td>{{ $unit->uom }} @if($unit->is_delete===1) <span class="badge bg-secondary">Unit Deleted</span> @else <button type="button" class="btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="deleteRecord({{$unit->id}})"><em class="bi bi-trash-fill"></em></button> @endif</td>
                                            <td>{{$unit->created_at}}</td>
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
    let selectedUnit = 0;

    async function openEdit(id) {
        selectedUnit = id;
        await axios.get(`{{ url('/unit') }}/${id}`)
            .then(res => {
                $('#edit_uom').val(res.data.uom);
                $('#editUnit').modal('toggle');
            })
            .catch(err => alert("Could not fetch the data. Please contact website administrator."));
    }

    async function saveChanges(e) {
        e.preventDefault();
        const data = {
            "uom" : $('#edit_uom').val(),
        };
        await axios.put(`{{ url('/unit') }}/${selectedUnit}`, data)
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
            let allSelectedUnit = $(".unit-checkbox");
            let toDelete = [];
            for (let i = 0; i < allSelectedUnit.length; i ++) {
                if (allSelectedUnit[i].checked) {
                    toDelete.push(allSelectedUnit[i].value);
                }
            }
            if (toDelete.length > 0) {
                let confirmDeleteBatch = confirm("Are you sure to delete?");
                if (confirmDeleteBatch) {
                    await axios.post(`{{ route('unit.delete_batch') }}`, { id : toDelete })
                        .then(res => {
                            window.location.reload();
                        }).catch(err => {
                            window.location.reload();
                        });
                }
            } else {
                alert("Select unit to delete first!");
            }
        } else {
            let confirmDeleteSingle = confirm("Are you sure to delete this unit?");
            if (confirmDeleteSingle) {
                await axios.delete(`{{ url('unit/single') }}/${id}`)
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
@include('layout/datatable', ['tableId' => 'unit-table' , 'columnId' => '3'])
@include('layout/footer')
