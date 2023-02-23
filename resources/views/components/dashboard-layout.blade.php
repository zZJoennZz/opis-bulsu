<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            {{ $title ?? "" }} |  OPIS - BulSU e-PROCUREMENT
        </title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}" crossorigin="use-credentials" />
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>
        {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script> --}}
        <meta name="viewport" content="width=device-width, initial-scale=1" />
    </head>
    <body>
        <noscript>PLEASE ENABLE YOUR JAVASCRIPT TO USE THE WEBSITE WITHOUT ANY ISSUES.</noscript>
        <x-member-nav-bar />
        <div class="container-fluid">
            <div class="row">
                
                <x-sidebar />
        
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="p-3">
                        <div class="card">
                            <div class="card-body">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
        <script>
            $(document).ready(function() {
                let systemClock = $('#system-clock');
                function runClock() {
                    systemClock.html(new Date().toLocaleString('en-PH', {
                        weekday: "long",
                        month: '2-digit',
                        day: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    }))
                }

                runClock();

                setInterval(() => {
                    runClock();
                }, 1000);
            });
            
        </script>
        
        <script>
            function status(id, status){
                // alert(id+"-"+status);
                let confirmStatus = confirm("Are you sure to change the status?");
                    if (confirmStatus) {
                        var stp = document.getElementById('status'+id).title;
                        
                        // alert(stp);
                        if(stp == 'off'){
                        stf = 0;
                        }
                        if(stp == 'on'){
                        stf = 1;
                        }
                    
                        $.ajax({
                            type: 'GET',
                            url: "/users/update-status/"+id+"/"+stf,
                    
                            success:function(response){
                                if(response.status == "1")
                                {
                                    document.getElementById('status'+ adid).innerHTML = "OFF";
                                }
                        
                                if(response.status == "0")
                                {
                                    document.getElementById('status'+ adid).innerHTML = "ON";
                                }
                            }
                        });
                    location.reload();
                }
            }
        </script>
        <script>
            $('form').on('submit', function (e) {
                $('button[type=submit], input[type=submit]', $(this)).blur().addClass('disabled is-submited');
                $('button[type=submit], input[type=submit]', $(this)).attr("disabled","disabled");
            });
            $(document).on('click', 'button[type=submit].is-submited, input[type=submit].is-submited', function(e) {
                e.preventDefault();
            });
        </script>

        {{$additional_script ?? ''}}
    </body>
</html>