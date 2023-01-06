        <script>
            $(document).ready(function() {
                let systemClock = $('#system-clock');
                function runClock() {
                    systemClock.html(new Date().toLocaleString('en-PH', {
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
      $('#users-list-table tbody tr .status__id').click(function(){
    
        if($(this).hasClass('approved')){
          $(this).attr('title','off');
        }else{
          $(this).attr('title','off');
    
        }
      }); 
    
    
    
      function status(id, status){
        // alert(id+"-"+status);
        
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
    </script>
    </body>
</html>