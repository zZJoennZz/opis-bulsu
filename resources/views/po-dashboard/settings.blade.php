<x-dashboard-layout>
    <x-slot:title>
        Settings
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Settings'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    @foreach ($all_settings as $setting)
        <div class="row">
            <div class="col-sm-12 col-md-6 mb-3">
                @if ($setting->name === 'maintenance_mode')
                    <div class="mb-2">Maintenance Mode <span class="small text-muted">System Setting</span></div>
                    <div class="form-check form-switch">
                        @if(intval($setting->value) === 1)
                            <input class="form-check-input" type="checkbox" role="switch" name="maintenance_mode" id="maintenance_mode" checked>
                        @else
                            <input class="form-check-input" type="checkbox" role="switch" name="maintenance_mode" id="maintenance_mode">
                        @endif
                    </div>
                    <div style="font-size: 0.8em;" class="fst-italic text-muted">Enabling maintenance mode will prevent other user groups from accessing OPIS.</div>
                @endif

                @if ($setting->name === 'bac_chairman')
                    <label for="bac_chairman" class="form-label">BAC Chairman <span class="small text-muted">Signatory Setting</span></label>
                    <input value="{{$setting->value}}" type="text" class="form-control form-control-lg" id="bac_chairman" name="bac_chairman">
                @endif

                @if ($setting->name === 'university_president')
                    <label for="university_president" class="form-label">University President <span class="small text-muted">Signatory Setting</span></label>
                    <input value="{{$setting->value}}" type="text" class="form-control form-control-lg" id="university_president" name="university_president">
                @endif
            </div>
        </div>
    @endforeach
    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>