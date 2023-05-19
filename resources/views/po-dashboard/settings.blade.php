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
    <form action="{{ route('settings.update') }}" method="post">
        @csrf
        @method("PUT")
        @foreach ($all_settings as $setting)
            @if ($setting->name === 'maintenance_mode')
                <div class="col-12 mb-4">
                    <div class="mb-2">Maintenance Mode <span class="small text-muted">System Setting</span></div>
                    <div class="form-check form-switch">
                        @if(intval($setting->value) === 1)
                            <input class="form-check-input" type="checkbox" role="switch" name="maintenance_mode" id="maintenance_mode" checked>
                        @else
                            <input class="form-check-input" type="checkbox" role="switch" name="maintenance_mode" id="maintenance_mode">
                        @endif
                    </div>
                    <div style="font-size: 0.8em;" class="fst-italic text-muted">Enabling maintenance mode will prevent other user groups from accessing OPIS.</div>
                </div>
            @endif

            @if ($setting->name === 'bac_chairman')
                <div class="row mb-1">
                    <div class="col-12">
                        <div class="text-uppercase fw-bold text-secondary small">
                            Default values for signatories
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="bac_chairman" class="form-label">BAC Chairman <span class="small text-muted">Signatory Setting</span></label>
                        <input value="{{$setting->value}}" type="text" class="form-control form-control-lg" id="bac_chairman" name="bac_chairman">
                    </div>
                </div>
            @endif

            @if ($setting->name === 'university_president')
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="university_president" class="form-label">University President <span class="small text-muted">Signatory Setting</span></label>
                        <input value="{{$setting->value}}" type="text" class="form-control form-control-lg" id="university_president" name="university_president">
                    </div>
                </div>
            @endif

            @if ($setting->name === 'vice_chair_1')
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="vice_chair_1" class="form-label">Vice Chairman <span class="small text-muted">Signatory Setting</span></label>
                        <input value="{{$setting->value}}" type="text" class="form-control form-control-lg" id="vice_chair_1" name="vice_chair_1">
                    </div>
                </div>
            @endif

            @if ($setting->name === 'member_1')
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="member_1" class="form-label">Member <span class="small text-muted">Signatory Setting</span></label>
                        <input value="{{$setting->value}}" type="text" class="form-control form-control-lg" id="member_1" name="member_1">
                    </div>
                </div>
            @endif

            @if ($setting->name === 'member_2')
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="member_2" class="form-label">Member <span class="small text-muted">Signatory Setting</span></label>
                        <input value="{{$setting->value}}" type="text" class="form-control form-control-lg" id="member_2" name="member_2">
                    </div>
                </div>
            @endif

            @if ($setting->name === 'member_3')
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="member_3" class="form-label">Member <span class="small text-muted">Signatory Setting</span></label>
                        <input value="{{$setting->value}}" type="text" class="form-control form-control-lg" id="member_3" name="member_3">
                    </div>
                </div>
            @endif

            @if ($setting->name === 'member_4')
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="member_4" class="form-label">Member <span class="small text-muted">Signatory Setting</span></label>
                        <input value="{{$setting->value}}" type="text" class="form-control form-control-lg" id="member_4" name="member_4">
                    </div>
                </div>
            @endif

            @if ($setting->name === 'technical_resource_person')
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="technical_resource_person" class="form-label">Technical Resource Person <span class="small text-muted">Signatory Setting</span></label>
                        <input value="{{$setting->value}}" type="text" class="form-control form-control-lg" id="technical_resource_person" name="technical_resource_person">
                    </div>
                </div>
            @endif
        @endforeach
        <div class="row">
            <div class="col-12">
                <div class="float-end">
                    <button class="btn btn-primary" type="submit"><em class="bi bi-download"></em> Save Changes</button>
                </div>
            </div>
        </div>
    </form>
    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>