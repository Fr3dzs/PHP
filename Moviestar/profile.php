<?php

    require_once("templates/header.php");
    require_once("models/User.php");
    require_once("models/movie.php");
    require_once("dao/UserDAO.php");
    require_once("dao/MovieDAO.php");
    require_once("models/msg.php");

    $user     = new User();
    $userDao  = new UserDAO($conn, $BASE_URL);
    $movieDao = new MovieDAO($conn, $BASE_URL); // ← lowercase, consistent
    $message  = new Message($BASE_URL);

    $id = filter_input(INPUT_GET, "id");

    if (empty($id)) {

        if (!empty($userData)) {
            $id          = $userData->id;
            $profileData = $userData;
        } else {
            $message->setMessage("O User não foi encontrado!", "error", "index.php");
        }

    } else {

        $profileData = $userDao->findById($id);

        if (!$profileData) { // ← only send error if user not found
            $message->setMessage("O User não foi encontrado!", "error", "index.php");
        }
    }

    $fullName = $user->getFullName($profileData);

    if (empty($profileData->image)) {
        $profileData->image = "user.png";
    }

    $userMovies = $movieDao->getMoviesByUserId($id);

?>

<div id="main-container" class="container-fluid">
    <div class="col-md-8 offset-md-2">
        <div class="row profile-container">

            <div class="col-md-12 about-container">
                <div class="profile-image-container profile-image" style="background-image: url('<?= $BASE_URL ?>images/users/<?= $profileData->image ?>')"></div>
                <h1 class="page-title"><?= $fullName ?></h1>
                <p class="about-title">Sobre:</p>
                <?php if (empty($profileData->bio)): ?>
                    <p class="profile-description">Este utilizador ainda não adicionou uma descrição!</p>
                <?php else: ?>
                    <p class="profile-description"><?= $profileData->bio ?></p>
                <?php endif; ?>
            </div>

            <div class="col-md-12 added-movies-container">
                <h2 class="section-title">Filmes adicionados</h2>
                <div class="movies-container">
                    <?php if (!empty($userMovies)): ?>
                        <?php foreach($userMovies as $movie): ?>
                            <?php require("templates/movie_card.php"); ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="empty-list">Este utilizador ainda não adicionou filmes!</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once("templates/footer.php"); ?>