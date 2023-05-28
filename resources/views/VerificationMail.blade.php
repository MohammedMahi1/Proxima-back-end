<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verification</title>
    <style>
        body {
            background-color: #fafafa;
            display: flex;
            flex-direction: column;
            font-family: sans-serif;
            align-items: center;
            justify-content: center;
            height: 100vh;
            width: 100vw;
            margin: 0;
            padding: 0;
        }

        button{
            background-color: #1a70e5;
            padding: 10px;
            width: fit-content;
            font-size: 15px;
            border-radius: 5px;
            margin-top: 10px;
            margin-bottom: 10px;
            outline:none;
            border: none;
            cursor: pointer;
            color: #fff;
            transition: 0.2s;
        }
        button:hover{
            background-color: #2e81f1;
        }
        button:focus{
            box-shadow: rgba(63, 139, 246, 0.47) 0 0 0 3px;
        }
        .paper{
            width: 60%;
            height: 90%;
            padding: 10px;
            box-shadow: #d0d0d0 2px 2px 6px;
            border-radius:5px;
        }
        .paper h1,h2,h3 {
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="paper">

<h1>Verification email</h1>
<h2>Hello {{$user->name}} !</h2>
<h3>Please click the button to verifie your account email</h3>
    <a href="{{\Illuminate\Support\Facades\URL::temporarySignedRoute('verification',now()->addMinute(5), ['id'=>$user->id])}}" target="_blank">
<button>
        Click here to verify your email
</button>
    </a>
</div>
</body>
</html>



