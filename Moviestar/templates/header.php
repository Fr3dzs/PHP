<?php

    require_once("globals.php");
    require_once("config.php");
    require_once("dao/UserDAO.php");
    require_once("models/msg.php");

    $flashMessage = [];

    if (isset($_SESSION["msg"])) {
        $flashMessage["msg"]  = $_SESSION["msg"];
        $flashMessage["type"] = $_SESSION["type"];
        unset($_SESSION["msg"]);
        unset($_SESSION["type"]);
    }

    $userDao  = new UserDAO($conn, $BASE_URL); // ← uppercase DAO
    $userData = $userDao->verifyToken(false);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieStar</title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/css/bootstrap.css" integrity="sha512-zylce7fP6h4usg536JBTRj2rt7q22Z0qicHSlgSK53Irtfkz37ate3KCQ59du+aXZV6R3yyL2X1LyGKBEUMZaw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.js" integrity="sha512-aK6kibiwf8qo6euZqFOsx3sZ0WmEYs3f/wtvp44qy8CdGcTah45mgbOsLi5RMvylljL0TTMu6Shr6X+g1w1lIA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>

<header>
    <nav id="main-navbar" class="navbar navbar-expand-lg">
        <a href="<?= $BASE_URL ?>index.php" class="navbar-brand">
            <img src="<?= $BASE_URL ?>images/logo.svg" alt="Moviestar" id="logo">
            <span id="moviestar-title">Moviestar</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <form action="<?= $BASE_URL ?>search.php" method="GET" id="search-form" class="d-flex my-2 my-lg-0">
            <input type="text" name="q" id="search" class="form-control mr-sm-2" placeholder="Buscar Filmes" aria-label="search">
            <button class="btn my-2 my-sm-0" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav ms-auto">
                <?php if($userData): ?>
                    <li class="nav-item">
                        <a href="<?= $BASE_URL ?>newmovie.php" class="nav-link">
                            <i class="far fa-plus-square"></i> Incluir Filme
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $BASE_URL ?>dashboard.php" class="nav-link">Meus Filmes</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $BASE_URL ?>editprofile.php" class="nav-link">
                            <i class="fas fa-user"></i> <?= $userData->first_name ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $BASE_URL ?>logout.php" class="nav-link">Sair</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="<?= $BASE_URL ?>auth.php" class="nav-link">Entrar / Registar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>

<?php if (!empty($flashMessage["msg"])): ?>
    <div class="msg-container">
        <p class="msg <?= $flashMessage["type"] ?>"><?= $flashMessage["msg"] ?></p>
    </div>
<?php endif; ?>