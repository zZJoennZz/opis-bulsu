<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.13.1/cr-1.6.1/r-2.4.0/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.13.1/cr-1.6.1/r-2.4.0/datatables.min.js"></script>

<script defer>
    $(document).ready(function() {
        $('#{{ $tableId }}').DataTable(
            @if(isset($columnId))
                {   
                    columnDefs: [ { "targets":[{{$columnId}}], "visible":false, "searchable": false } ],
                    order: [[ {{$columnId}}, 'desc' ]],       
                }
            @endif
        );
    });
</script>
