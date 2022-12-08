<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.12.1/jquery-ui.js"></script>
    <title>Hệ thống đặt vé MUSTGO</title>
</head>

<body>
<div class="container mt30 mb30">
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
</div>
</body>

</html>
