@include('layout/header', ['title' => 'Account Settings | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3">
                <div class="card">
                    <div class="card-body">
                        @include('layout/breadcrumb',
                            [
                                'breadcrumbs' => [
                                    ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                                    ['name' => 'Account Settings'],
                                ]
                            ]
                        )
                        <form action="{{ route('account-settings.save') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" value="{{$account_details->username}}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="mb-3 text-muted fst-italic">
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
                                    <label for="position" class="form-label">Position</label>
                                    <input type="text" class="form-control" id="position" value="{{ $account_details->profile->position->description }}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/footer')
