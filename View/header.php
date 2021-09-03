<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>ESHOP</title>

</head>
<body>
    <header>
        <nav class="navbar navbar-expand navbar-dark bg-dark">
            <span class="navbar-brand" style="margin-left:10px">ИМ</span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample02" aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto" style="margin-left:50px">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Каталог</a>
                    </li>
                    <?if($params['user']->getAuth()):?>
                    <li class="nav-item">
                        <a class="nav-link" href="/cabinet">Личный кабинет</a>
                    </li>
                    <?else:?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Авторизация</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/registration">Регистрация</a>
                    </li>
                    <?endif;?>
                </ul>
            </div>
        </nav>
    </header>
