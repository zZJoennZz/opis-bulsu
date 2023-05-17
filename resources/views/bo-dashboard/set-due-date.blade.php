<x-dashboard-layout>
    <x-slot:title>
        Submission Due Date
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>'],
            ['name' => 'Submission Due Date'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <div class="row mb-3">
        <div class="col-12">
            <div class="text-uppercase text-secondary small">Please note that this will repeat every year.</div>
        </div>
    </div>
    <form action="{{ route('due-date.update') }}" method="post">
        @csrf
        @method("PUT")
        <div class="row mb-3">
            <div class="col-12">
                <div><label for="due_month" class="form-label">Due Month:</label></div>
                <div>
                    <select onchange="setDays(event)" name="due_month" id="due_month" class="form-select">
                        <option value="0" disabled selected hidden>Select PPMP due month</option>
                        @php
                            $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                        @endphp
                        @for ($i = 0; $i < 12; $i++)
                            <option value="{{ $i + 1 }}"
                                @if($i + 1 === intval($settings[0]->value))
                                    selected
                                @endif
                            >{{ $months[$i] }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div id="due-day" class="col-12">
                
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary"><em class="bi bi-download"></em> Save Changes</button>
            </div>
        </div>
    </form>

    <x-slot:additional_script>
        <script>
            const monthsList = [
                {
                    'value': 1,
                    'days': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]
                },
                {
                    'value': 2,
                    'days': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29]
                },
                {
                    'value': 3,
                    'days': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]
                },
                {
                    'value': 4,
                    'days': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30]
                },
                {
                    'value': 5,
                    'days': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]
                },
                {
                    'value': 6,
                    'days': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30]
                },
                {
                    'value': 7,
                    'days': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]
                },
                {
                    'value': 8,
                    'days': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]
                },
                {
                    'value': 9,
                    'days': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30]
                },
                {
                    'value': 10,
                    'days': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]
                },
                {
                    'value': 11,
                    'days': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30]
                },
                {
                    'value': 12,
                    'days': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]
                },
            ];

            function setDays(event = null) {
                let daysInMonth = 0;

                if (event !== null) {
                    daysInMonth = monthsList.filter((d) => d.value === parseInt(event.target.value))[0].days;
                } else {
                    daysInMonth = monthsList.filter((d) => d.value === parseInt({{ $settings[0]->value }}))[0].days;
                }

                const dueDayDiv = $("#due-day");
                dueDayDiv.html('');

                //create select
                let $dueDayLabel = $("<label>", {
                    for: "due_day",
                    text: "Due Day:",
                    class: "form-label"
                });
                let $dueDaySelect = $("<select>", {
                    id: "due_day",
                    name: "due_day",
                    class: "form-select"
                });

                daysInMonth.forEach((day) => {
                    $dueDaySelect.append($("<option>", {
                        value: day,
                        selected: {{ $settings[1]->value }} === parseInt(day) ? true : false,
                        text: day,
                    }));
                });

                dueDayDiv.append($dueDayLabel);
                dueDayDiv.append($dueDaySelect);
            }

            setDays();
        </script>
    </x-slot>
</x-dashboard-layout>