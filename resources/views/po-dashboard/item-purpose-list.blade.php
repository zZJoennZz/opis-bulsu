@include('layout/header', ['title' => 'Item Purpose | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="modal fade" id="addNewCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('item-purpose.add') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Item Purpose</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                    </div>
                    {{-- <div>
                        <label for="description" class="form-label">Under of what group (for reports)</label>
                        <select class="form-select" id="under_of_group" name="under_of_group" aria-label="Category group">
                            @foreach ($category_groups as $group)
                                <option value="{{ $group->id }}">{{ $group->title }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><em class="bi bi-save"></em> Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form onsubmit="return saveChanges(event)">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-cat-header-text">Edit Item Purpose</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="edit_description" name="description" placeholder="Description">
                    </div>
                    {{-- <div>
                        <label for="description" class="form-label">Under of what group (for reports)</label>
                        <select class="form-select" id="edit_under_of_group" name="under_of_group" aria-label="Category group">
                            @foreach ($category_groups as $group)
                                <option value="{{ $group->id }}">{{ $group->title }}</option>
                            @endforeach
                        </select>
                    </div> --}}
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
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mt-3 mb-3" role="alert">
                        {{$error}}
                    </div>
                @endforeach
            @endif
            @if (Session::get('success') !== null)
                <div class="alert alert-success mt-3 mb-3" role="alert">
                    {{ Session::get('success') }}
                </div>
            @endif
            <div class="pt-3">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h5 card-title">Item Purpose List <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($item_purposes) }}</span></span></h1>
                        <hr />
                        <div class="mb-4">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewCategory"><em class="bi bi-folder-plus"></em> Add</button>
                            <button class="btn btn-danger" onclick="deleteRecord()"><em class="bi bi-trash"></em> Delete</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="item-category-table">
                                <caption>Item Categories</caption>
                                <thead>
                                    <tr>
                                        <th style="width: 5%;"></th>
                                        <th class="text-center" style="width: 5%;">Edit</th>
                                        <th style="width: 80%;">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item_purposes as $purpose)
                                        <tr>
                                            <td>
                                                <div class="form-check w-100 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input category-checkbox" type="checkbox" value="{{ $purpose->id }}" id="category{{ $purpose->id }}" @if($purpose->is_delete===1) disabled @endif>
                                                </div>
                                            </td>
                                            <td class="text-center"><button class="btn btn-success" onclick="openEdit({{ $purpose->id }})" @if($purpose->is_delete===1) disabled @endif><em class="bi bi-pencil-square"></em></button></td>
                                            <td>{{ $purpose->description }} @if($purpose->is_delete===1) <span class="badge bg-secondary">Category Deleted</span> @else <button type="button" class="btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="deleteRecord({{$purpose->id}})"><em class="bi bi-trash-fill"></em></button> @endif</td>
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
    let selectedCategory = 0;

    async function openEdit(id) {
        selectedCategory = id;
        await axios.get(`{{ url('/item-purpose') }}/${id}`)
            .then(res => {
                $('#edit_description').val(res.data.description);
                $('#editCategory').modal('toggle');
            })
            .catch(err => alert("Could not fetch the data. Please contact website administrator."));
    }

    async function saveChanges(e) {
        e.preventDefault();
        const data = {
            "description" : $('#edit_description').val(),
        };
        await axios.put(`{{ url('/item-purpose') }}/${selectedCategory}`, data)
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
            let allSelectedCategory = $(".category-checkbox");
            let toDelete = [];
            for (let i = 0; i < allSelectedCategory.length; i ++) {
                if (allSelectedCategory[i].checked) {
                    toDelete.push(allSelectedCategory[i].value);
                }
            }
            if (toDelete.length > 0) {
                let confirmDeleteBatch = confirm("Are you sure to delete?");
                if (confirmDeleteBatch) {
                    await axios.post(`{{ route('item-cat.delete_batch') }}`, { id : toDelete })
                        .then(res => {
                            window.location.reload();
                        }).catch(err => {
                            window.location.reload();
                        });
                }
            } else {
                alert("Select category to delete first!");
            }
        } else {
            let confirmDeleteSingle = confirm("Are you sure to delete this category?");
            if (confirmDeleteSingle) {
                await axios.delete(`{{ url('item-purpose/single') }}/${id}`)
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
@include('layout/datatable', ['tableId' => 'item-category-table'])
@include('layout/footer')