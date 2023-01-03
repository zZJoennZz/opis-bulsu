@include('layout/header', ['title' => 'Approved PPMP | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <a href="{{ route('po-dashboard.show') }}" class="btn btn-secondary"><em class="bi bi-arrow-bar-left"></em> Back</a>
                        </div>
                        <hr />
                        <div class="mb-3 fs-3">
                            CAMPUS / OFFICE: <strong>{{ $branch->branch_name }}</strong>
                        </div>
                        <div class="table-responsive">
                            <table id="ppmp_year_group" class="table table-small table-bordered">
                                <thead>
                                    <tr>
                                        <th>Year</th>
                                        <th style="width: 3%;">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ppmp_before as $ppmp)
                                        <tr>
                                            <td class="fs-2 fw-bold p-4">{{ $ppmp->year }}</td>
                                            <td class="p-4"><a href="{{ route('previous-ppmp-single.show', ["branch_id" => $branch->id, "year" => $ppmp->year]) }}" class="btn btn-success" style="margin: auto;"><em class="bi bi-folder2-open"></em></a></td>
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
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/datatable', ['tableId' => 'ppmp_year_group'])
@include('layout/footer')