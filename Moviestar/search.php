<?php

    require_once("templates/header.php");
    require_once("dao/MovieDAO.php");

    $movieDao = new MovieDAO($conn, $BASE_URL);

    $q      = filter_input(INPUT_GET, "q");
    $movies = $movieDao->findByTitle($q) ?? [];

?>

<div id="main-container" class="container-fluid">
    <h2 class="section-title">Estás a procurar por: <span id="search-result"><?= $q ?></span></h2>
    <p class="section-description">Resultados da sua pesquisa</p>
    <div class="movies-container">
        <?php if(!empty($movies)): ?>
            <?php foreach($movies as $movie): ?> 
                <?php require("templates/movie_card.php"); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-list">Não encontramos o filme que estás a procura, <a href="<?= $BASE_URL ?>">volte para a página inicial</a>.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once("templates/footer.php"); ?>