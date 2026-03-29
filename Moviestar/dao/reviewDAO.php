<?php 

    require_once("models/review.php");
    require_once("dao/userDAO.php");
    require_once("models/msg.php");


    class ReviewDAO implements ReviewDAOInterface {

        private $conn;
        private $url;
        private $message;

        public function __construct(PDO $conn, $url){
            $this->conn = $conn;
            $this->url = $url;
            $this->message = new Message($url);
        }

        public function buildReview($data){

            $reviewObject = new Review();

            $reviewObject->id = $data["id"];
            $reviewObject->movie_id = $data["movie_id"];
            $reviewObject->user_id = $data["user_id"];
            $reviewObject->rating = $data["rating"];
            $reviewObject->review = $data["review"];
            $reviewObject->created_at = $data["created_at"];

            return $reviewObject;
        }

        public function create(Review $review) {

            $stmt = $this->conn->prepare("INSERT INTO reviews (
                movie_id, user_id, rating, review
            ) VALUES (
                :movie_id, :user_id, :rating, :review
            )");

            $stmt->bindParam(":movie_id",  $review->movie_id);
            $stmt->bindParam(":user_id",   $review->user_id);
            $stmt->bindParam(":rating",    $review->rating);
            $stmt->bindParam(":review",    $review->review);

            $stmt->execute();

            $this->message->setMessage("Review criada com sucesso!", "success", "movie.php?id=" . $review->movie_id);
        }

        public function getMoviesReview($id){

            $reviews = [];

            $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movie_id = :movie_id");

            $stmt->bindParam(":movie_id", $id);
            
            $stmt->execute();

            if($stmt->rowCount()> 0){

                $reviewsData = $stmt->fetchAll();

                $userDao = new UserDao($this->conn, $this->url);

                foreach($reviewsData as $review) {

                    $reviewObject = $this->buildReview($review);

                    $user = $userDao->findById($reviewObject->user_id);

                    $reviewObject-> user = $user;

                    $reviews[] = $reviewObject;

                }
                
            }

            return $reviews;
        }

        public function hasAlreadyReviewed($movie_id, $user_id){

            $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movie_id = :movie_id and user_id = :user_id");
            $stmt->bindParam(":movie_id", $movie_id);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }

        }

        public function getRatings($id){

            $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movie_id = :movie_id");

            $stmt->bindParam(":movie_id", $id);

            $stmt->execute();

            if($stmt->rowCount() > 0) {

                $reviewsData = $stmt->fetchAll();

                $rating = 0;

                foreach($reviewsData as $review) {
                    $rating += $review["rating"];
                }

                return round($rating / count($reviewsData));

            } else {

               return $rating = "Não avaliado";
            }


        }
        
    }