<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error 404</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body{
            color:#333;
            padding-top:0px !important;
            background: #f3f3f3;
            background-size: cover;
            -webkit-background-size: cover;
            -miz-background-size: cover;
            -o-background-size: cover;
            -ms-background-size: cover;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <header class="page-header text-center">
                    <h1>Halaman tidak ditemukan</h1>
                    <p>
                        Halaman yang dicari tidak ditemukan
                    </p>
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Kembali ke Halaman Utama</a>
                </header>
            </div>
        </div>
    </div>
</body>
</html>
