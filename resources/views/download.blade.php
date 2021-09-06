<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>下载</title>
    <style>
        * {
            margin: 0;
            padding: 0;

        }

        .container {
            background-image: url({{asset("bg.png")}});
            background-position: 0% 0%;
            background-size: 100% 100%;
            background-repeat: no-repeat;
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
        }

        .logo {
            width: 78px;
            height: 78px;
            box-sizing: border-box;
            border-radius: 5px;
        }

        .content {
            padding: 42px 36px;
        }

        .font {
            margin-top: 20px;
            font: normal normal 14px/normal PingFang SC;
            color: #fff;
            line-height: 20px;
            text-align: justify;
        }

        .android {
            background-image: url({{asset("android.png")}});
            background-position: 0% 0%;
            background-size: 100% 100%;
            background-repeat: no-repeat;
            width: 159px;
            height: 46px;
            display: block;
        }

        .ios {
            background-image: url({{asset("ios.png")}});
            background-position: 0% 0%;
            background-size: 100% 100%;
            background-repeat: no-repeat;
            width: 115px;
            height: 46px;
            display: block;
        }

        .button {
            margin-top: 80px;
            display: flex;
            justify-content: space-around;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div>
            <img class="logo" src="{{$logo}}" alt="">
            <div class="font">
                <p>APP介绍：</p>
                <p>&nbsp;&nbsp;&nbsp;一句话描写：让工作计划更简单高效</p>
                <p>&nbsp;&nbsp;&nbsp;介绍：简乐办公，是重庆宇物科技有限公司为企业打造的一个工作计划、协同、智能移动办公平台，帮助数千万企业降低沟通、协同、管理成本，提升办公效率，实现数字化新工作方式一款办公软件。</p>
            </div>
        </div>
        <div class="button">
            <a href="" class="ios"></a>
            <a href="{{$android}}" class="android"></a>
        </div>
    </div>
</div>

</body>
</html>
