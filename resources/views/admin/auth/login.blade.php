<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mintas Studio</title>
    <!-- FAVICON -->
    <link rel="icon" type="image/png" href="{{ asset('uploads/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('uploads/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{asset('uploads/favicon.ico')}}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('uploads/apple-touch-icon.png')}}" />
    <link rel="manifest" href="{{ asset('uploads/site.webmanifest') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- ICONS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('mintas/style.css') }}">
    <link rel="stylesheet" href="{{ asset('mintas/responsive.css') }}">
</head>

<body>
    <section class="auth-section">
        <div class="auth-card">
            <img src="{{ asset('uploads/logo.png') }}" class="auth-logo" alt="">
            <h2>Login</h2>
            <p>Login to continue shopping with Mintas.</p>
            <form method="post" action="{{ route('admin-verify-login') }}">
                @csrf
                <div class="form-group error">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter Email" value="{{old('email')}}">
                    
                    @if ($errors->has('email'))
                        <small class="input-error">
                            {{ $errors->first('email') }}
                        </small>
                    @endif
                </div>

                <div class="form-group">
                    <input type="password"  class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="password" value="" autocomplete="false">
                    @if ($errors->has('password'))
                        <small class="input-error">{{ $errors->first('password') }}</small>
                    @endif
                </div>

                {{-- <div class="auth-row">
                    <label class="remember-me">
                        <input type="checkbox">
                        Remember me
                    </label>
                    <a href="forgot-password.html">Forgot Password?</a>
                </div> --}}
                <button type="submit" class="login-btn">Login</button>
            </form>

            {{-- <div class="divider">
                <span>OR</span>
            </div>

            <button class="google-btn">
                <img src="images/google-icon-logo.svg" alt=""> Continue with Google
            </button> --}}

            {{-- <p class="register-text">
                Don't have an account?
                <a href="registration.html">Register</a>
            </p> --}}
        </div>
    </section>
    </body>
    </html>
