<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Branches with unsubmitted PPMP |  OPIS - BulSU e-PROCUREMENT
        </title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}" crossorigin="use-credentials" />
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <style>
            * {
                font-family: 'Book Antiqua', Times, serif;
                font-size: 12px;
            }
            @media print {
                @page {
                    size: A4 portrait;
                    margin: 5px 0px 0px 5px;
                    page-break-after: always;
                }
            }
        </style>
    </head>
    <body>
        <div class="to-print">
            <div class="container-fluid">
                <div class="row mb-5">
                    <div class="col-7 m-auto">
                        <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" style="position: absolute; top: 0; left: 20vw; width: 75px;" />
                        <div class="text-center">
                            <div>Republic of the Philippines</div>
                            <div class="fw-bold">BULACAN STATE UNIVERSITY</div>
                            <div class="fst-italic">City of Malolos, Bulacan</div>
                            <div class="fw-bold text-uppercase">Unsubmitted PPMP for the year {{ getPpmpYear() }}</div>
                            <div class="fw-bold">As of {{ date('Y-m-d') }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered border-dark table-sm">
                            <caption></caption>
                            <thead class="align-middle text-center">
                                <tr>
                                    <th style="width: 10%;" rowspan="3">Office Code</th>
                                    <th style="width: 60%;" rowspan="3">Branches</th>
                                    <th style="width: 30%;" rowspan="3">Representative</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $unsubPpmpBranches = App\Models\Branch::whereDoesntHave('ppmp', function($query) {
                                        $query->where('year', getPpmpYear());
                                    }
                                    )
                                    ->where('type', '<>', 'DEVELOPER')
                                    ->get();
                                @endphp
                                @foreach($unsubPpmpBranches as $branch)
                                    @if(!isset($branch->ppmp[0]))
                                    <tr>
                                        <td>{{ $branch->office_code }}</td>
                                        <td>
                                            <div class="small text-uppercase">{{ $branch->type }}</div>
                                            <div class="fw-bold fs-5">{{ $branch->branch_name }}</div>
                                        </td>
                                        <td>
                                            @if (count($branch->users) >= 1)
                                                <div class="fw-bold">{{ $branch->users[0]->profile->first_name }} {{ $branch->users[0]->profile->last_name }}</div>
                                                <div class="fst-italic">{{ $branch->users[0]->profile->position->description }}</div>
                                            @else
                                                <div class="fst-italic">n/a</div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            window.addEventListener('load', function () {
                window.print();
            })
        </script>
    </body>
</html>