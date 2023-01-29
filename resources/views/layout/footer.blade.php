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

              $('[homelogin]').click(function(){
                  $('[container-spinner]').removeClass('d-none');
                  $('[container-spinner]').addClass('d-flex');
              });
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
    </body>
</html>
