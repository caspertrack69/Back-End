<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{translate('Admin')}} | {{translate('Login')}}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('public/favicon.ico')}}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/toastr.css">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>


    <style>
        .login-card {
            /*display: flex;*/
            /*align-items: center;*/
            /*justify-content: center;*/


            width: 100%;
            height: auto;
        }

        .login-card div {
        }

        .center-element {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .input-field {
            border-width: 0;
            border: none;
            background-color: #FFFFFF;
            border-width: 0;
            box-shadow: none;
            border-bottom: 1px solid darkgrey;
        }

        .sign-in-button {
            background-color: #014F5B;
            width: 150px;
            height: 4.5vh;
            border-radius: 20px;

        }

        @media only screen and (min-width: 768px) {
            .text-div {
                border-radius: 15px 0 0 15px;
                background-color: #014F5B;
            }

            .form-div {
                border-radius: 0 15px 15px 0;
                background-color: #FFFFFF;
            }

            .header-text {
                font-size: 30px;
                font-weight: bold
            }

            .container-div {
                /*width: 50vw;*/
                height: 60vh;
            }
        }

        @media only screen and (max-width: 768px) {
            .text-div {
                border-radius: 15px 15px 0 0;
                background-color: #014F5B;
            }

            .form-div {
                border-radius: 0 0 15px 15px;
                background-color: #FFFFFF;
            }

            .header-text {
                font-size: 25px;
                font-weight: bold
            }
        }


        /* for captcha */
        .input-icons i {
            position: absolute;
            cursor: pointer;
        }

        .input-icons {
            width: 100%;
            margin-bottom: 10px;
        }

        .icon {
            padding: 9% 0 0 0;
            min-width: 40px;
        }

        .input-field {
            width: 100%;
            padding: 10px 0 10px 10px;
            text-align: center;
            border-right-style: none;
        }
    </style>
</head>

<body>
<!-- ========== MAIN CONTENT ========== -->
<main id="content" role="main" class="main" style="height:100vh; display: flex; flex-direction: column; justify-content: center;">
    <div class="position-fixed top-0 right-0 left-0 bg-img-hero"
         style="height: 100%; background-image: url({{asset('public/assets/admin')}}/svg/components/login_background.svg);opacity: 0.5">
    </div>
@php($systemlogo=\App\Models\BusinessSetting::where(['key'=>'logo'])->first()->value??'')
<!-- Content -->
    <div class="container py-5 py-sm-7">
        <!-- Card -->
        <div class="center-element" style="/*margin-top: 10%*/">
            <div class="row px-1 container-div">
                <div class="col-12 text-div col-md-6 center-element py-4">
                    <div class="text-center">
                        <h1 class="text-white text-uppercase header-text">{{ translate('Welcome to '. Helpers::get_business_settings('business_name') ?? translate('6cash')) }}</h1>
                        <hr class="bg-white" style="width: 40%">
                        <div class="text-white text-uppercase">
                            <span style="width: 50%;display: inline-block">
                                {{ translate((Helpers::get_business_settings('business_name') ?? translate('6cash')) . ' is a secured and user-friendly digital wallet') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 form-div col-md-6 center-element py-4">
                    <!-- Form -->
                    <form class="" action="{{route('admin.auth.login')}}" method="post" id="form-id">
                        @csrf
                        <div class="text-center">
                            <div class="mb-5">
                                <h2 class="text-capitalize">{{translate('sign in')}}</h2>
                            </div>
                        </div>

                        <!-- Form Group -->
                        <div class="js-form-message form-group px-4">
                            <input type="text" class="form-control form-control-lg input-field" name="phone"
                                   id="phone" required
                                   tabindex="1" placeholder="{{translate('Enter your phone no.')}}"
                                   data-msg="{{translate('Please enter a valid phone number.')}}">
                        </div>
                        <!-- End Form Group -->

                        <!-- Form Group -->
                        <div class="js-form-message form-group px-4">
                            <div class="input-group input-group-merge">
                                <input type="password"
                                       class="js-toggle-password form-control form-control-lg input-field"
                                       name="password" id="signupSrPassword"
                                       placeholder="{{translate('Enter your password')}}"
                                       aria-label="8+ characters required" required
                                       data-msg="{{translate('Your password is invalid. Please try again.')}}"
                                       data-hs-toggle-password-options='{
                                                     "target": "#changePassTarget",
                                            "defaultClass": "tio-hidden-outlined",
                                            "showClass": "tio-visible-outlined",
                                            "classChangeTarget": "#changePassIcon"
                                            }'>
                                <div id="changePassTarget" class="input-group-append">
                                    <a class="input-group-text" href="javascript:">
                                        <i id="changePassIcon" class="tio-visible-outlined"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- End Form Group -->

                        <!-- Checkbox -->
{{--                        <div class="form-group">--}}
{{--                            <div class="custom-control custom-checkbox">--}}
{{--                                <input type="checkbox" class="custom-control-input" id="termsCheckbox"--}}
{{--                                       name="remember">--}}
{{--                                <label class="custom-control-label text-muted" for="termsCheckbox">--}}
{{--                                    {{translate('remember me')}}--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <!-- End Checkbox -->

                        {{-- recaptcha --}}
                        @php($recaptcha = \App\CentralLogics\Helpers::get_business_settings('recaptcha'))
                        @if(isset($recaptcha) && $recaptcha['status'] == 1)
                            <div id="recaptcha_element" style="width: 100%;" data-type="image"></div>
                            <br/>
                        @else
                            <div class="row p-2">
                                <div class="col-6 pr-0">
                                    <input type="text" class="form-control form-control-lg" name="default_captcha_value" value=""
                                           placeholder="{{translate('Enter captcha')}}" style="border: none" autocomplete="off">
                                </div>
                                <div class="col-6 input-icons" style="background-color: #FFFFFF; border-radius: 5px;">
                                    <a onclick="javascript:re_captcha();">
                                        <img src="{{ URL('/admin/auth/code/captcha/1') }}" class="input-field" id="default_recaptcha_id" style="display: inline;width: 90%; height: 75%; border-bottom: none; border-radius: 10px">
                                        <i class="tio-refresh icon"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if(env('APP_MODE')=='demo')
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-10">
                                        <span>{{translate('Email')}} : +8801100000000</span><br>
                                        <span>{{translate('Password')}} : {{translate('12345678')}}</span>
                                    </div>
                                    <div class="col-2">
                                        <span class="btn btn-primary" onclick="copy_cred()"><i class="tio-copy"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="center-element" style="margin-top: 8vh;">
                            <button type="submit"
                                    class="btn btn-lg btn-block text-white center-element sign-in-button">{{translate('sign_in')}}</button>
                        </div>
                    </form>
                    <!-- End Form -->
                </div>

            </div>

        </div>
        <!-- End Card -->
    </div>
    <!-- End Content -->
</main>
<!-- ========== END MAIN CONTENT ========== -->


<!-- JS Implementing Plugins -->
<script src="{{asset('public/assets/admin')}}/js/vendor.min.js"></script>

<!-- JS Front -->
<script src="{{asset('public/assets/admin')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

<!-- JS Plugins Init. -->
<script>
    $(document).on('ready', function () {
        // INITIALIZATION OF SHOW PASSWORD
        // =======================================================
        $('.js-toggle-password').each(function () {
            new HSTogglePassword(this).init()
        });

        // INITIALIZATION OF FORM VALIDATION
        // =======================================================
        $('.js-validate').each(function () {
            $.HSCore.components.HSValidation.init($(this));
        });
    });
</script>

{{-- recaptcha scripts start --}}
@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script type="text/javascript">
        var onloadCallback = function () {
            grecaptcha.render('recaptcha_element', {
                'sitekey': '{{ \App\CentralLogics\Helpers::get_business_settings('recaptcha')['site_key'] }}'
            });
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script>
        $("#form-id").on('submit',function(e) {
            var response = grecaptcha.getResponse();

            if (response.length === 0) {
                e.preventDefault();
                toastr.error("{{translate('Please check the recaptcha')}}");
            }
        });
    </script>
@else
    <script type="text/javascript">
        function re_captcha() {
            $url = "{{ URL('/admin/auth/code/captcha') }}";
            $url = $url + "/" + Math.random();
            document.getElementById('default_recaptcha_id').src = $url;
            console.log('url: '+ $url);
        }
    </script>
@endif
{{-- recaptcha scripts end --}}

@if(env('APP_MODE')=='demo')
    <script>
        function copy_cred() {
            $('#phone').val('+8801100000000');
            $('#signupSrPassword').val('12345678');
            toastr.success('Copied successfully!', 'Success!', {
                CloseButton: true,
                ProgressBar: true
            });
            return false;
        }
    </script>
@endif

<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public//assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>
</body>
</html>
