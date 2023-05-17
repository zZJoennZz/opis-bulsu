<x-dashboard-layout>
    <x-slot:title>
        Account Settings
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>'],
            ['name' => 'Account Settings'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <form action="{{ route('account-settings.save') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{$account_details->username}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="mb-1">
                    <label for="email" class="form-label">Email Address <small class="text-muted">Please make sure to use active email</small></label>
                    <input type="email" class="form-control" name="email" id="email" value="{{$account_details->email}}" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="mb-3 text-muted fst-italic small">
                    To change your password, please logout and use the reset password form.
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-6">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{$account_details->profile->first_name}}">
            </div>
            <div class="col-sm-12 col-md-6">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{$account_details->profile->last_name}}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                Position: <em>{{ $account_details->profile->position->description }}</em>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-end">
                <button class="btn btn-primary" type="submit"><em class="bi bi-download"></em> Save Changes</button>
            </div>
        </div>
    </form>
    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>