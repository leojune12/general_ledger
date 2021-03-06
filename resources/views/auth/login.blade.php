<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    {{--for sb admin template--}}
    {{--<link href="{{ asset('sb/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('sb/css/sb-admin-2.min.css') }}" rel="stylesheet">--}}

    {{--<script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>--}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<style>
    body {
        font-family: Arial, Helvetica, sans-serif !important;
    }
</style>

<body style="background-color: #ECEFF1;">
    <div style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%)">
        <div class="card mx-auto" style="min-width: 300px; width: 320px;">
            <div class="card-header bg-white border-0">
                <h4 class="text-dark ">GENERAL LEDGER</h4>
                <h5><small>Welcome back, please login to your account.</small></h5>
            </div>
            <div class="card-body pb-1 pt-0">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input
                        type="text"
                        class="form-control mb-3"
                        placeholder="Username"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="off"
                        required
                    >

                    <input
                        type="password"
                        class="form-control mb-3"
                        placeholder="Password"
                        name="password"
                        autocomplete="current-password"
                        required
                        value="{{ old('password') }}"
                    >

                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0 pl-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
            <div class="card-footer border-0 bg-white text-center pt-2">
                <small>A Program by Leo June Bedeo</small>
            </div>
        </div>
    </div>
</body>

</html>
