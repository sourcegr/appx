<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width,initial-scale=1'>
    <title>Welcome to APPX!</title>
    <style>
        body {
            background: #fff;
            font-family: serif;
        }

        .input {
            padding-bottom: 10px;
        }

        .head, .contents {
            padding: 0 10px;
        }

        .head {
            background: #ff3e00;
            padding: 10px
        }

        .panel {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #dddddd;
            box-shadow: 0 0 10px #333333aa;
        }
    </style>
</head>

<body>
<div class="panel">
    <div class="head">
        Please login
    </div>
    <div class="contents">
        <form method="post" action="/login">
            <input type="hidden" name="{!! $APPX_CSRF_TOKEN_NAME !!}" value="{!! $APPX_CSRF_TOKEN_VALUE
 !!}">
            <div class="label">
                Email
            </div>
            <div class="input">
                <input type="text" name="email">
            </div>

            <div class="label">
                Password
            </div>

            <div class="input">
                <input type="password" name="password">
            </div>
            <hr>
            <button>Login</button>
        </form>
    </div>
</div>
</body>
</html>
