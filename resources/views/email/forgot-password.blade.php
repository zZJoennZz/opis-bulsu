<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <style type="text/css">
            .logo__container{
                background-color:#763435;
                padding: 1rem 0 0.155rem;
            }

            .logo__container img{
                width: 640px;
                display: block;
                margin: 0 auto;
            }

            .email_body{
                background:#f3f3f3;
                padding:2.5rem;
                text-align: center;
            }
            .email_body h1 {
                color: #333;
                font-size: 36px;
                font-family: 'Montserrat', sans-serif;
            }
            .email_body p {
                color: #333;
                font-size: 20px;
                font-family: 'Montserrat', sans-serif;
            }
            .email_body a {
                font-size: 20px;
                letter-spacing: 1px;
            }
            .body_content {
                background: #fff;
                display: block;
                padding: 5rem 0 2rem;
            }
            .reset_pass-img {
                width: 100%;
                max-width: 196px;
            }
        </style>
    </head>
    <body>
        <div class="logo__container">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" >
        </div>
        
        <div class="email_body">  
            <div class="body_content">
                <img src="{{ asset('img/reset-password.png') }}" alt="Password Reset image" class="reset_pass-img">
                <h1>Forget Password Email</h1>
                <p> You can reset password from below link </p> 
                <a href="{{ route('reset-password.show', $token) }}">Reset Password</a>
            </div>
        </div>
    </body>
</html>
