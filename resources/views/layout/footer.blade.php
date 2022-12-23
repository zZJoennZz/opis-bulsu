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
    </body>
</html>