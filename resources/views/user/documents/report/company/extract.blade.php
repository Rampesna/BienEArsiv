<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1254"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/bs5/css/bootstrap.css">
    <title>Cari Extre Raporu</title>

    <style>
        body {
            font-family: "dejavu sans", serif;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="row">
    <div class="col-xl-12">
        <div class="card border-0">
            <div class="card-body">
                <table class="table">
                    <thead class="bg-light fw-bolder">
                    <tr>
                        <td>Tarih</td>
                        <td>İşlem</td>
                        <td>Tutar</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{$transaction['date']}}</td>
                            <td>{{$transaction['type']}}</td>
                            <td>{{$transaction['amount']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="assets/bs5/js/bootstrap.js"></script>
</body>
</html>
