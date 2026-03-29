<?php

  require_once("templates/header.php");
  require_once("models/movie.php");
  require_once("models/review.php");
  require_once("models/msg.php");
  require_once("dao/MovieDAO.php");
  require_once("dao/UserDAO.php");
    require_once("dao/reviewDAO.php");

  $message  = new Message($BASE_URL);
  $movieDao = new MovieDAO($conn, $BASE_URL);
  $userDao  = new UserDAO($conn, $BASE_URL);
  $reviewDao = new ReviewDAO($conn, $BASE_URL);

  $id = filter_input(INPUT_GET, "id");

  if (empty($id)) {
      $message->setMessage("O filme não foi encontrado!", "error", "index.php");
  }

  $movie = $movieDao->findById($id);

  if (!$movie) {
      $message->setMessage("O filme não foi encontrado!", "error", "index.php");
  }

  if (empty($movie->image)) {
      $movie->image = "movie_cover.jpg";
  }

  $userData      = $userDao->verifyToken();
  $userOwnsMovie = false;

  if (!empty($userData)) {
      if ($userData->id === $movie->user_id) {
          $userOwnsMovie = true;
      }

      $alreadyReviewed = $reviewDao->hasAlreadyReviewed($id, $userData->id);
  }

  $movieReviews = $reviewDao->getMoviesReview($movie->id);


?>

<div id="main-container" class="container-fluid">
    <div class="row">

        <!-- Movie info -->
        <div class="offset-md-1 col-md-6 movie-container">
            <h1 class="page-title"><?= $movie->title ?></h1>
            <p class="movie-details">
                <span>Duração: <?= $movie->length ?></span>
                <span class="pipe"></span>
                <span><?= $movie->category ?></span>
                <span class="pipe"></span>
                <span><i class="fas fa-star"></i><?= $movie->rating ?></span>
            </p>
            <iframe 
                src="<?= $movie->trailer ?>" 
                width="560" 
                height="315" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
            <p class="movie-description"><?= $movie->description ?></p>

            <?php if($userOwnsMovie): ?>
            <div style="margin-top: 20px;">
                <a href="<?= $BASE_URL ?>editmovie.php?id=<?= $movie->id ?>" class="edit-btn">
                    <i class="far fa-edit"></i> Editar
                </a>
                <form action="<?= $BASE_URL ?>movie_process.php" method="POST" style="display:inline-block;">
                    <input type="hidden" name="type" value="delete">
                    <input type="hidden" name="id" value="<?= $movie->id ?>">
                    <button type="submit" class="delete-btn">
                        <i class="fas fa-times"></i> Apagar
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>

        <!-- Movie image -->
        <div class="col-md-4">
            <div class="movie-image-container" style="background-image: url('<?= $BASE_URL ?>images/movies/<?= $movie->image ?>')"></div>
        </div>

        <!-- Reviews -->
        <div class="offset-md-1 col-md-10" id="reviews-container">
            <h3 id="reviews-title">Avaliações</h3>

            <!-- Review form -->
            <?php if(!empty($userData) && !$userOwnsMovie && !$alreadyReviewed): ?>
            <div class="col-md-12" id="review-form-container">
                <h4>Adicione sua avaliação</h4>
                <p class="page-description">Preencha o formulário abaixo para adicionar sua avaliação</p>
                <form action="<?= $BASE_URL ?>review_process.php" method="POST" id="review-form">
                    <input type="hidden" name="type" value="create">
                    <input type="hidden" name="movie_id" value="<?= $movie->id ?>">
                    <div class="form-group">
                        <label for="rating">Avaliação:</label>
                        <select class="form-control" id="rating" name="rating" required>
                            <option value="">Selecione</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="review">Comentário:</label>
                        <textarea class="form-control" id="review" name="review" rows="3" placeholder="O que achou do filme?"></textarea>
                    </div>
                    <button type="submit" class="btn card-btn">Enviar comentário</button>
                </form>
            </div>
            <?php endif; ?>

                <?php foreach($movieReviews as $review): ?>
                    <?php require("templates/user_review.php"); ?>
                <?php endforeach; ?>
                <?php if(count($movieReviews) == 0): ?>
                    <p class="empty-list">Não há comentários para este filme ainda...</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once("templates/footer.php"); ?>