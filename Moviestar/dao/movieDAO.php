<?php 

    require_once("models/movie.php");
    require_once("models/msg.php");
    require_once("dao/reviewDAO.php");

    class MovieDAO implements MovieDAOInterface {

        private $conn;
        private $url;
        private $message; 

        public function __construct(PDO $conn, $url) {
            $this->conn    = $conn;
            $this->url     = $url;
            $this->message = new Message($url);
        }

        public function buildMovie($data) {
            $movie = new Movie();

            $movie->id          = $data["id"];
            $movie->title       = $data["title"];
            $movie->image       = $data["image"];
            $movie->trailer     = $data["trailer"];
            $movie->description = $data["description"];
            $movie->length      = $data["length"];
            $movie->user_id     = $data["user_id"];
            $movie->category    = $data["category"];

            $reviewDao = new ReviewDAO($this->conn, $this->url);
            
            $rating = $reviewDao->getRatings($movie->id);

            $movie->rating = $rating;

            return $movie;
        }

        public function create(Movie $movie) {

            $stmt = $this->conn->prepare("INSERT INTO movies (
                title,
                description,
                image,
                trailer,
                length,
                user_id,
                category
            ) VALUES (
                :title,
                :description,
                :image,
                :trailer,
                :length,
                :user_id,
                :category
            )");

            $stmt->bindParam(":title",       $movie->title);
            $stmt->bindParam(":description", $movie->description);
            $stmt->bindParam(":image",       $movie->image);
            $stmt->bindParam(":trailer",     $movie->trailer);
            $stmt->bindParam(":category",    $movie->category);
            $stmt->bindParam(":length",      $movie->length);
            $stmt->bindParam(":user_id",     $movie->user_id);

            try {
                $stmt->execute();
            } catch (PDOException $e) {
                die("SQL Error: " . $e->getMessage());
            }

            $this->message->setMessage("Filme adicionado com sucesso!", "success", "index.php");
        }

        public function update(Movie $movie) {
            
            $stmt = $this->conn->prepare("UPDATE movies SET
                title = :title,
                description = :description,
                image = :image,
                trailer = :trailer,
                length = :length,
                category = :category
                WHERE id = :id");

                $stmt->bindParam(":title", $movie->title);
                $stmt->bindParam(":description", $movie->description);
                $stmt->bindParam(":image", $movie->image);
                $stmt->bindParam(":trailer", $movie->trailer);
                $stmt->bindParam(":length", $movie->length);
                $stmt->bindParam(":category", $movie->category);
                $stmt->bindParam(":id", $movie->id);

                $stmt->execute();

                $this->message->setMessage("Filme atualizado com sucesso!", "success", "dashboard.php");

        }

        public function destroy($id) {

            $stmt = $this->conn->prepare("DELETE FROM movies WHERE id = :id");
            $stmt->bindParam(":id", $id);

            try {
                $stmt->execute();

                $this->message->setMessage("Filme removido com sucesso","success","dashboard.php");
            } catch (PDOException $e) {
                die("SQL Error: " . $e->getMessage());
            }

        }

        public function findAll() {

            $movies = [];
            
            $stmt = $this->conn->query("SELECT * FROM movies");
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function findById($id) {
            
            $movie = [];

            $stmt = $this->conn->prepare("SELECT * FROM movies WHERE id = :id");

            $stmt->bindParam(":id", $id);

            $stmt->execute();

            if($stmt->rowCount() > 0) {

                $movieData = $stmt->fetch();
                
                $movie = $this->buildMovie($movieData);
                
                return $movie;

            } else {
                return false;
            }

            

        }

        public function findByTitle($title) {

            $movies = [];


            $stmt = $this->conn->prepare("SELECT * FROM movies WHERE title LIKE :title");
            $stmt->bindValue(":title", "%$title%"); // ← bind the wrapped version

            $stmt->execute();

            if($stmt->rowCount() > 0) {

                $moviesData = $stmt->fetchAll(); // ← fetchAll, not fetch

                foreach($moviesData as $movie) {
                    $movies[] = $this->buildMovie($movie); // ← build each result
                }
            }

            return $movies;
        }

        public function getLatestMovies() {

            $movies = [];

            $stmt = $this->conn->query("SELECT * FROM movies ORDER BY id DESC");

            if($stmt->rowCount() > 0) {

                $moviesArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach($moviesArray as $movie) {
                    $movies[] = $this->buildMovie($movie);
                }
            }

            return $movies;
        }

        public function getMoviesByCategory($category) {

            $movies = [];

            $stmt = $this->conn->prepare("SELECT * FROM movies WHERE category = :category ORDER BY id DESC");

            $stmt->bindParam(":category", $category);

            $stmt->execute();

            if($stmt->rowCount() > 0) {

                $moviesArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach($moviesArray as $movie) {
                    $movies[] = $this->buildMovie($movie);
                }
            }

            return $movies;
            
        }

        public function getMoviesByUserId($user_id) {
        
            $movies = [];

            $stmt = $this->conn->prepare("SELECT * FROM movies WHERE user_id = :user_id");

            $stmt->bindParam(":user_id", $user_id);

            $stmt->execute();

            if($stmt->rowCount() > 0) {

                $moviesArray = $stmt->fetchAll();
                
                foreach($moviesArray as $movie) {
                    $movies[] = $this->buildMovie($movie);
                }
            }

            return $movies;
            
        }
    }