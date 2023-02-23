<x-dashboard-layout>
    <x-slot:title>
        Prepare BAC Step 2
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'BAC Resolution', 'route' => 'dashboard.show'],
            ['name' => 'Prepare BAC']
        ]
    @endphp
    <div class="float-end">
        <a href="{{route('bac-reso.add')}}?step=1" class="btn btn-secondary btn-sm"><em class="bi bi-arrow-clockwise"></em> Reset</a>
    </div>
    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div>
        <div>
            @if (count($company_list) === 0)
                <div class="alert alert-warning" role="alert">
                    <em class="bi bi-exclamation-diamond-fill"></em> No quotations found.
                </div>
            @else
                <div class="mb-3">
                    <label for="company" class="form-label">Select supplier/company</label>
                    <select class="form-select" id="company" size="5" aria-label="Supplier/company to prepare BAC">
                        @foreach ($company_list as $company)
                            <option value="{{$company->id}}">{{$company->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="text-end">
                    <a id="next-btn" class="btn btn-primary" onclick="return checkCompany()">Next <em class="bi bi-chevron-double-right"></em></a>
                </div>
                <x-slot:additional_script>
                    <script>
                        function checkCompany() {
                            const companyId = $('#company').val();
                            if (companyId === null) {
                                alert('Please select company first.');
                                return false;
                            }
                            $('#next-btn').attr('href', `{{route('bac-reso.add')}}?step=3&cId=${companyId}`);

                            return true;
                        }
                    </script>
                </x-slot>
            @endif
        </div>
    </div>
</x-dashboard-layout>