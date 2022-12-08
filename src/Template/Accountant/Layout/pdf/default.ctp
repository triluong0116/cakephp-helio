<html lang="vi">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" charset=ISO-8859-8>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title></title>
    <style>
        html {
            font-family: sans-serif;
            font-family: DejaVu Sans, sans;
            font-size: 12px;
        }
        table, td, th {
            border: 1px solid #ddd;
            text-align: left;
        }
        td {
            width: 200px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table tr td:nth-child(2) {
            text-align: right;
        }
    </style>
    <title></title>
</head>
<body>
<header style="background: #0c3e53">
    <img src="<?= \Cake\Routing\Router::url('/', true) . 'frontend/img/logo.png' ?>" alt="Trippal" style="padding: 10px 10px 10px 50px; width: 200px"/>
</header>
<div style="padding-left: 40px; padding-right: 40px">
    <?= $this->fetch('content') ?>
</div>
<footer style="background: #0c3e53; padding: 10px 10px 10px 10px">
    <div style="text-align: center;color: white">
        <p>CÔNG TY CP DU LỊCH LIÊN MINH VIỆT NAM</p>
        <p>Trụ sở chính: Số 122 Trần Đại Nghĩa, P Đồng Tâm, Q Hai Bà Trưng, TP
            Hà Nội.</p>
        <p>Địa chỉ giao dịch: Tầng 1, Đơn nguyên 1, Chung cư 43-45, Ngõ 130 Đốc Ngữ, Q.Ba Đình, TP Hà Nội</p>
    </div>
</footer>
</body>
</html>
