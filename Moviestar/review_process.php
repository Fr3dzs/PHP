<?php 

  require_once("globals.php");
  require_once("config.php");
  require_once("models/movie.php");
    require_once("models/review.php");
  require_once("models/msg.php");
  require_once("dao/movieDAO.php");
  require_once("dao/userDAO.php");
  require_once("dao/reviewDAO.php");

  $message = new Message($BASE_URL);

  $movieDao = new MovieDAO($conn, $BASE_URL);

  $userDao  = new UserDAO($conn, $BASE_URL);

  $reviewDao = new ReviewDAO($conn, $BASE_URL);

  $type = filter_input(INPUT_POST, "type");

  $userData = $userDao->verifyToken();


  if ($type === "create") {

    //Get Ratings

    $rating = filter_input(INPUT_POST, "rating");
    $review = filter_input(INPUT_POST, "review");
    $movie_id = filter_input(INPUT_POST, "movie_id");

    $reviewObject = new Review();

    $movieData = $movieDao->findById($movie_id);

    if($movieData) {

        if(!empty($rating) && !empty($review) && !empty($movie_id)) {

            $reviewObject->movie_id = $movie_id;
            $reviewObject->user_id = $userData->id;
            $reviewObject->rating = $rating;
            $reviewObject->review = $review;

            $reviewDao->create($reviewObject);

        } else {
            $message->setMessage("Preencha todos os campos para criar a review!", "error", "back");
        }

  }
}