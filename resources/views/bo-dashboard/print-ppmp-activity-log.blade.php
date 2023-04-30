<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Print PPMP History Logs |  OPIS - BulSU e-PROCUREMENT
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
                    size: A4;
                    margin: 10px 0px 0px 10px;
                    page-break-after: always;
                }
            }
        </style>
    </head>
    <body>
        <div class="to-print">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-7 m-auto">
                        <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" style="position: absolute; top: 0; left: 20vw; width: 75px;" />
                        <div class="text-center">
                            <div>Republic of the Philippines</div>
                            <div class="fw-bold">BULACAN STATE UNIVERSITY</div>
                            <div class="fst-italic">City of Malolos, Bulacan</div>
                            <div class="fw-bold mt-5">PPMP Request Change Logs of {{ $ppmp_histories[0]->ppmp->branch->branch_name }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered border-dark table-sm">
                            <caption></caption>
                            <thead class="align-middle text-center">
                                <tr>
                                    <th style="width: 50%;">Activity</th>
                                    <th style="width: 25%;">Date/Time</th>
                                    <th style="width: 25%;">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ppmp_histories as $history)
                                    <tr>
                                        <td>
                                            <div class="mb-2">{{ $history->ppmp->item_detail->description }}</div>
                                            <div class="py-2 ps-4">
                                                @foreach (json_decode($history->changes_summary) as $summary)
                                                    <div class="border-bottom border-dark">{{ $summary }}</div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {{ date('Y-m-d h:i:s A', strtotime($history->created_at)) }}
                                        </td>
                                        <td class="text-center">
                                            {{ $history->changes_record_by->profile->first_name }} {{ $history->changes_record_by->profile->last_name }}
                                        </td>
                                    </tr>
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