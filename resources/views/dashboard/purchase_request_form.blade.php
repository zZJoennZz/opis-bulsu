@include('layout/header', ['title' => 'Purchase Request Form | OPIS - BulSU e-PROCUREMENT'])
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
                                ['name' => 'Purchase Requests List <span class="badge bg-primary">' . Auth::user()->ppmp_year . '</span>', 'route' => 'pr-list.show'],
                                ['name' => 'Purchase Request Form']
                            ]
                        ]
                        )
                        @if ($is_pr_enabled)
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <form onsubmit="submitForm(event)">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <span class="form-label">PR #:</span>
                                                <div class="fst-italic text-secondary small">PR # will be generated once submitted.</div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-12 col-md-6">
                                                <label for="year" class="form-label">Year:</label>
                                                <input class="form-control" id="year" name="year" type="text" placeholder="{{ Auth::user()->ppmp_year }}" disabled>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <label for="date" class="form-label">Date:</label>
                                                <input class="form-control" id="date" name="date" type="text" placeholder="{{ date('m-d-Y') }}" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <label for="entity" class="form-label">Entity:</label>
                                                <input class="form-control" id="entity" name="entity" type="text" placeholder="{{ Auth::user()->branch->branch_name }}" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <label for="year" class="form-label">Requested by:</label>
                                                <input class="form-control" id="year" name="year" type="text" placeholder="{{ Auth::user()->profile->first_name }} {{ Auth::user()->profile->last_name }}" disabled>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="mb-3">
                                            Selected Items:
                                        </div>
                                        <div id="selected-items-div" class="table-responsive mb-3" style="max-height: 300px;">
                                            <table id="selected-items-table" class="table table-small table-bordered">
                                                <caption>Selected item/s for this entity</caption>
                                                <thead>
                                                    <tr>
                                                        <th style="width: 50%;">Item Detail</th>
                                                        <th>Quantity</th>
                                                        <th>Source of Fund</th>
                                                        <th>Remove</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="selected-items">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mb-3">
                                            <label for="purpose" class="form-label">Purpose</label>
                                            <input type="text" class="form-control" name="purpose" id="purpose">
                                        </div>
                                        <div class="mb-3">
                                            <label for="office" class="form-label">Office/Section</label>
                                            <input type="text" class="form-control" name="office" id="office">
                                        </div>
                                        <div class="mb-3">
                                            <label for="responsibility_center_code" class="form-label">Responsibility Center Code</label>
                                            <input type="text" class="form-control" name="responsibility_center_code" id="responsibility_center_code">
                                        </div>
                                        <div>
                                            <button id="submitBtn" disabled class="btn btn-primary"><em class="bi bi-file-earmark-fill"></em> Submit Purchase Request</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="mb-3">
                                        Available Items:
                                    </div>
                                    <div id="available-items-div" class="table-responsive" style="display: none;">
                                        <table id="available-items-table" class="table table-small table-bordered">
                                            <caption>Available item/s for this entity</caption>
                                            <thead>
                                                <tr>
                                                    <th style="width: 50%;">Item Detail</th>
                                                    <th>Quantity</th>
                                                    <th>Source of Fund</th>
                                                    <th>Add</th>
                                                </tr>
                                            </thead>
                                            <tbody id="available-items">
    
                                            </tbody>
                                        </table>
                                    </div>
                                    <p id="items-are-loading" class="placeholder-glow">
                                        <span class="placeholder col-12"></span>
                                        <span class="placeholder col-12"></span>
                                        <span class="placeholder col-12"></span>
                                        <span class="placeholder col-12"></span>
                                        <span class="placeholder col-12"></span>
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center justify-content-center" style="min-height: 50vh;">
                                <div class="fs-5 fw-bold fst-italic text-secondary">
                                    <div class="text-center fs-1"><em class="bi bi-exclamation-triangle"></em></div>
                                    Purchase request submissions is not yet enabled for the year <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span>. Please get in touch with procurement office.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/datatable', ['tableId' => 'available-items-table'])
@vite('resources/js/app.js')
<script>
    let masterList;
    let allItems;
    let selectedItems = [];
    let prPurpose = '';
    let prOffice = '';
    let prResCenCode = '';

    function addToList(id) {
        selectedItems.push(id);
        mapSelected();
    }

    function removeFromList(id) {
        selectedItems = selectedItems.filter(d => d != id);
        mapSelected();
    }

    function mapSelected() {
        selItemsArr = masterList.filter(d => selectedItems.includes(d.id));
        allItems = masterList.filter(d => !selectedItems.includes(d.id));
        let htmlContentForSelectedItems = '';
        selItemsArr.map(item => {
            let milestones_qty = 0;
            item.milestones.map(milestone => milestones_qty += milestone.milestone_value);

            htmlContentForSelectedItems += `
                <tr>
                    <td>${item.item_detail.description}</td>
                    <td>${milestones_qty}</td>
                    <td>${item.source_of_fund.source_of_fund}</td>
                    <td><button onclick="removeFromList(${item.id})" class="btn btn-danger btn-sm"><em class="bi bi-dash-circle"></em></button></td>
                </tr>
            `;
        });
        mapToTable();
        $('#selected-items').html(htmlContentForSelectedItems);
    }

    function mapToTable() {
        let htmlContent = '';
        allItems.map(d => {
            let milestones_qty = 0;
            d.milestones.map(i => milestones_qty += i.milestone_value);
            htmlContent += `
                <tr>
                    <td>${d.item_detail.description}</td>
                    <td>${milestones_qty}</td>
                    <td>${d.source_of_fund.source_of_fund}</td>
                    <td><button onclick="addToList(${d.id})" class="btn btn-primary btn-sm"><em class="bi bi-plus-circle"></em></button></td>
                </tr>
            `;
        });
        $('#available-items').html(htmlContent);
        $('#available-items-div').fadeIn();
    }

    async function submitForm(e) {
        e.preventDefault();
        if (prPurpose.trim() === '' || prPurpose === undefined || prPurpose === null) {
            alert('Please enter the purpose of this PR.');
            return;
        }
        if (prOffice.trim() === '' || prOffice === undefined || prOffice === null) {
            alert('Please enter the office/section of this PR.');
            return;
        }
        if (prResCenCode.trim() === '' || prResCenCode === undefined || prResCenCode === null) {
            alert('Please enter the responsibility center code of this PR.');
            return;
        }
        let frmData = new FormData();
        frmData.append('id', JSON.stringify(selectedItems));
        frmData.append('purpose', prPurpose);
        frmData.append('office', prOffice);
        frmData.append('responsibility_center_code', prResCenCode);
        await axios.post(`{{ route('new-pr.perform') }}`, frmData)
            .then(res => {
                window.location.href = `{{ route('pr-list.show') }}`;
                // console.log(res)
            })
            .catch(err => {
                if (err.name && err.name === 'AxiosError') {
                    alert(err.response.data.message);
                }
                window.location.reload();
            });
    }

    $(document).ready(async function() {
        $("#purpose").on('change', function (e) {
            prPurpose = e.target.value;
        })
        $("#office").on('change', function (e) {
            prOffice = e.target.value;
        })
        $("#responsibility_center_code").on('change', function (e) {
            prResCenCode = e.target.value;
        })
        await axios.get(`{{ route('pr-items.show') }}`)
            .then(res => {
                allItems = res.data.data;
                masterList = allItems;
                if (masterList.length > 0) {
                    $('#submitBtn').prop("disabled", false);
                } else {
                    $('#submitBtn').html(`<em class="bi bi-exclamation-diamond-fill"></em> No items available`);
                }
                $('#items-are-loading').fadeOut();
            })
            .then(() => {
                mapToTable();
            })
            .catch(err => alert(err.response.data.message));
        $('#selected-items-table').DataTable();
    });
</script>
@include('layout/footer')