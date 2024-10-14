<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('assets/libs/bootstrap/css/bootstrap.min.css'.env('ASSET_VERSION'))}}" type="text/css" />
    <link rel="stylesheet" href="{{asset('assets/css/pages/login.css')}}">
</head>
<!-- This snippet uses Font Awesome 5 Free as a dependency. You can download it at fontawesome.io! -->

<body>
<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card border-0 shadow rounded-3 my-5">
                <div class="card-body p-4 p-sm-5 login-form">
                    <h5 class="card-title text-center mb-5 fw-light fs-5">Welcome back !</h5>
                    <form id="login">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="email" value="" placeholder="name@example.com" autocomplete="off" onfocus="this.removeAttribute('readonly');" readonly>
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" name="password" class="form-control" id="password" value="" placeholder="Password" autocomplete="off" onfocus="this.removeAttribute('readonly');" readonly>
                            <label for="password">Password</label>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit" id="signUp">Sign in</button>
                        </div>
                        <hr class="my-4">
                        <div class="d-grid mb-2">
                            <a href="{{route('register')}}" class="btn btn-secondary btn-login text-uppercase fw-bold">
                                <i class="fab fa-google me-2"></i> Sign up
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>

<script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script>
    $(document).ready(function (){
        $("#login").validate({
            rules : {
                email: {
                    required : true,
                    email: true
                },
                password: {
                    required: true
                }
            },
            messages: {
                email: {
                    required : "The email field is required.",
                    email: 'The email must be a valid email address.'
                },
                password: {
                    required: "The password field is required."
                }
            },
            errorPlacement: function(error, element) {
                error.insertAfter($(element).parent());
            },
            errorClass : "text-danger error",
            submitHandler: function (form,e){
                e.preventDefault();
                let formData = new FormData(form);
                $.ajax({
                    url: "{{route('login-check')}}",
                    method: 'post',
                    dataType: "json",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    async: true,
                    beforeSend: function () {
                        $('#signUp').attr('disabled',true);
                    },
                    success: function (data) {
                        alert(data.message);
                        window.location = "{{route('index')}}";
                        {{--Swal.fire({--}}
                        {{--    title: "Success",--}}
                        {{--    text: data.message,--}}
                        {{--    icon: "success",--}}
                        {{--    timer: 2000,--}}
                        {{--    showCancelButton: false,--}}
                        {{--    showConfirmButton: false--}}
                        {{--}).then(function() {--}}
                        {{--    window.location = "{{route('index')}}";--}}
                        {{--});--}}
                    },
                    error: function (response){
                        data = response.responseJSON;
                        if(data.hasOwnProperty('error')){
                            if(data.error.hasOwnProperty('email')){
                                $("#email-error").text(data.error.email).show();
                            }
                            if(data.error.hasOwnProperty('password')){
                                $("#password-error").text(data.error.email).show();
                            }
                        }else{
                            alert(data.message);
                            // Swal.fire({
                            //     title: "Error",
                            //     text: data.message,
                            //     icon: "warning",
                            //     showConfirmButton: false,
                            //     allowOutsideClick: false,
                            //     timer: 2000
                            // });
                        }
                    },
                    complete: function (){
                        $('#signUp').attr('disabled',false);
                    }
                });
            }
        });
    });
</script>
</body>

</html>
