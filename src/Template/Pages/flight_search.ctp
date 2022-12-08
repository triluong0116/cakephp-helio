<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.12.1/jquery-ui.js"></script>
    <title>Hệ thống đặt vé MUSTGO</title>
    <style>
        .lds-spinner {
            color: official;
            display: none;
            position: fixed;
            width: 80px;
            height: 80px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .lds-spinner div {
            transform-origin: 40px 40px;
            animation: lds-spinner 1.2s linear infinite;
        }
        .lds-spinner div:after {
            content: " ";
            display: block;
            position: absolute;
            top: 3px;
            left: 37px;
            width: 6px;
            height: 18px;
            border-radius: 20%;
            background: #126bc4;
        }
        .lds-spinner div:nth-child(1) {
            transform: rotate(0deg);
            animation-delay: -1.1s;
        }
        .lds-spinner div:nth-child(2) {
            transform: rotate(30deg);
            animation-delay: -1s;
        }
        .lds-spinner div:nth-child(3) {
            transform: rotate(60deg);
            animation-delay: -0.9s;
        }
        .lds-spinner div:nth-child(4) {
            transform: rotate(90deg);
            animation-delay: -0.8s;
        }
        .lds-spinner div:nth-child(5) {
            transform: rotate(120deg);
            animation-delay: -0.7s;
        }
        .lds-spinner div:nth-child(6) {
            transform: rotate(150deg);
            animation-delay: -0.6s;
        }
        .lds-spinner div:nth-child(7) {
            transform: rotate(180deg);
            animation-delay: -0.5s;
        }
        .lds-spinner div:nth-child(8) {
            transform: rotate(210deg);
            animation-delay: -0.4s;
        }
        .lds-spinner div:nth-child(9) {
            transform: rotate(240deg);
            animation-delay: -0.3s;
        }
        .lds-spinner div:nth-child(10) {
            transform: rotate(270deg);
            animation-delay: -0.2s;
        }
        .lds-spinner div:nth-child(11) {
            transform: rotate(300deg);
            animation-delay: -0.1s;
        }
        .lds-spinner div:nth-child(12) {
            transform: rotate(330deg);
            animation-delay: 0s;
        }
        @keyframes lds-spinner {
            0% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }
    </style>
</head>

<body>
<div class="container mt30 mb30">
    <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    <div id='dtc-search'></div>

    <script type='text/javascript'>
        var dtc_search = {
            path: ('https:' == document.location.protocol ? 'https://' : 'http://') + 'plugin.datacom.vn',
            productKey: 'keh2sgv93mag1z5',
            languageCode: 'vi',
        };
        (function () {
            var dtc_head = document.getElementsByTagName('head')[0];
            var dtc_script = document.createElement('script');
            dtc_script.async = true;
            dtc_script.src = dtc_search.path.concat('/Resources/Static/Js/plugin.js?v=' + (new Date().getTime()));
            dtc_script.charset = 'UTF-8';
            dtc_head.appendChild(dtc_script);
        })();
    </script>
    <script type="text/javascript">
        var checkExist = setInterval(function() {
            if ($('.dtc-search-main').length) {
                $(".lds-spinner").hide();
                clearInterval(checkExist);
            } else {
                $(".lds-spinner").show();
            }
        }, 100);
    </script>
</div>
</body>

</html>
