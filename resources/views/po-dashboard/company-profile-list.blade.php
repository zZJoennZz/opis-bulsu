@include('layout/header', ['title' => 'Company Profiles | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
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
                                ['name' => 'Price Quotations', 'route' => 'quotation.all'],
                                ['name' => 'Company Profiles List'],
                            ]
                        ]
                        )
                        <h1 class="h5 card-title"><span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($company_profiles) }}</span></span></h1>
                        <div class="modal fade" id="add-company-modal" tabindex="-1" aria-labelledby="addCompanyLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="addCompanyLabel">Add New Company Profile</h1>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="close"></button>
                                    </div>
                                    <form action="{{ route('company.add') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="mb-3 col-12">
                                                    <label for="name" class="col-form-label">Company Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col-12">
                                                    <label for="full_address" class="col-form-label">Address</label>
                                                    <textarea class="form-control" id="full_address" name="full_address"></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col-12">
                                                    <label for="tin" class="col-form-label">TIN #</label>
                                                    <input type="text" class="form-control" id="tin" name="tin" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <label for="contact_number" class="col-form-label">Contact Number</label>
                                                    <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label for="email_address" class="col-form-label">Email Address</label>
                                                    <input type="email" class="form-control" id="email_address" name="email_address" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- FOR EDIT --}}
                        <div class="modal fade" id="edit-company-modal" tabindex="-1" aria-labelledby="editCompanyLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editCompanyLabel">Edit Company Profile</h1>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="close"></button>
                                    </div>
                                    <form id="edit-company-form" onsubmit="return submitForm(event)" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="mb-3 col-12">
                                                    <label for="edit_name" class="col-form-label">Company Name</label>
                                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col-12">
                                                    <label for="edit_full_address" class="col-form-label">Address</label>
                                                    <textarea class="form-control" id="edit_full_address" name="full_address"></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col-12">
                                                    <label for="edit_tin" class="col-form-label">TIN #</label>
                                                    <input type="text" class="form-control" id="edit_tin" name="tin" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <label for="edit_contact_number" class="col-form-label">Contact Number</label>
                                                    <input type="text" class="form-control" id="edit_contact_number" name="contact_number" required>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label for="edit_email_address" class="col-form-label">Email Address</label>
                                                    <input type="email" class="form-control" id="edit_email_address" name="email_address" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <button id="tool-add-new" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-company-modal"><em class="bi bi-folder-plus"></em> Add</button>
                        </div>
                        <div class="table-responsive p-3">
                            <table class="table table-sm table-bordered table-hover" id="company-profile-table">
                                <caption>List of company profiles</caption>
                                <thead>
                                    <tr>
                                        <th style="width: 25%;">Company Name</th>
                                        <th style="width: 25%;">Address</th>
                                        <th>TIN #</th>
                                        <th>Contact #</th>
                                        <th>Email Address</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($company_profiles as $profile)
                                        <tr>
                                            <td class="fs-5 fw-bold">{{ $profile->name }}</td>
                                            <td>{{ $profile->full_address }}</td>
                                            <td>{{ $profile->tin }}</td>
                                            <td>{{ $profile->contact_number }}</td>
                                            <td>{{ $profile->email_address }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center w-100">
                                                    <form data-is-delete="{{$profile->is_delete}}" onsubmit="return submitDelete(event, {{ $profile->id }})" method="POST" id="delete-form-{{$profile->id}}">
                                                        @method('DELETE')
                                                        @csrf
                                                        <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                            <button id="tool-edit" type="button" onclick="showEditForm(event, {{ $profile->id }})" class="btn btn-primary" @if($profile->is_delete === 1) disabled @endif><em class="bi bi-pencil-square"></em></button>
                                                            @if($profile->is_delete === 0)
                                                                <button id="tool-delete" class="btn btn-danger"><em class="bi bi-trash"></em></button>
                                                            @else
                                                                <button id="tool-delete" class="btn btn-secondary"><em class="bi bi-arrow-repeat"></em></button>
                                                            @endif
                                                        </div>
                                                    </form>
                                                </div>
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
<script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
<script>
    let selectedCompanyId = 0;
    // \d{3}-\d{3}-\d{3}-\d{3}
    async function showEditForm(event, companyId) {
        $('button[id^="tool-"]').prop('disabled', true);
        selectedCompanyId = companyId;
        await axios.get(`{{ route('company.single.api') }}/${companyId}`)
            .then(res => {
                const companyProfile = res.data.data[0];
                $('#edit_name').val(companyProfile.name);
                $('#edit_full_address').val(companyProfile.full_address);
                $('#edit_tin').val(companyProfile.tin);
                $('#edit_contact_number').val(companyProfile.contact_number);
                $('#edit_email_address').val(companyProfile.email_address);
                $("#edit-company-modal").modal('show');
                $('button[id^="tool-"]').prop('disabled', false);
            })
            .catch(err => {
                alert('Cannot fetch company profile details. Please try again.');
                $('button[id^="tool-"]').prop('disabled', true);
            });
    }

    async function submitForm(event) {
        $('#edit-company-form').attr('action', `{{ route('company.update') }}/${selectedCompanyId}`);
        return true;
    }

    async function submitDelete(event, companyId) {
        const mode = $(`#delete-form-${companyId}`).data('is-delete');
        $(`#delete-form-${companyId}`).attr('action', `{{ route('company.delete') }}/${companyId}`);
        let confirmDelete = confirm(`Are you sure to ${mode === 1 ? 'restore' : 'delete'} this company profile?`);
        if (confirmDelete) {
            return true;
        }
        event.preventDefault();
        return false;
    }
</script>
@include('layout/datatable', ['tableId' => 'company-profile-table'])
@include('layout/footer')