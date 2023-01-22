{{--
Params
divId       String
margin      Double
fileName    String
format      String
--}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" integrity="sha512-YcsIPGdhPK4P/uRW6/sruonlYj+Q7UHWeKfTAkBW+g83NKM+jMJFJ4iAPfSnVp7BKD4dKMHmVSvICUbE/V1sSw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script defer>
    function printConsolidated() {
        let element = document.getElementById('@if(isset($divId)){{$divId}}@else{{"printReport"}}@endif');
        let opt = {
            margin: @if(isset($printReport)){{$margin}}@else{{"0.2"}}@endif,
            filename: '@if(isset($fileName)){{$fileName}}@else{{"eProcurement Doc"}}@endif',
            jsPDF: { unit: 'in', format: '@if(isset($format)){{$format}}@else{{"a4"}}@endif' }
        };
        let toPrint = element.innerHTML;
        const worker = html2pdf().set(opt).from(toPrint).save();
    }
</script>