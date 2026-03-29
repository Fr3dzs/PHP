<?php 

  require_once("globals.php");
  require_once("config.php");
  require_once("models/movie.php");
  require_once("models/msg.php");
  require_once("dao/movieDAO.php");
  require_once("dao/userDAO.php");

  $message = new Message($BASE_URL);

  $movieDao = new MovieDAO($conn, $BASE_URL);

  $userDao  = new UserDAO($conn, $BASE_URL);

  $type = filter_input(INPUT_POST, "type");

  $userData = $userDao->verifyToken();


if ($type === "create") {

    $title       = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer     = filter_input(INPUT_POST, "trailer");
    $category    = filter_input(INPUT_POST, "category");
    $length      = filter_input(INPUT_POST, "length");

    $movie = new Movie();

    if (!empty($title) && !empty($description) && !empty($category)) {

        $movie->title       = $title;
        $movie->description = $description;
        $movie->trailer     = $trailer;
        $movie->category    = $category;
        $movie->length      = $length;
        $movie->user_id     = $userData->id;

        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

            $image      = $_FILES["image"];
            $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
            $jpgArray   = ["image/jpeg", "image/jpg"];

            if (in_array($image["type"], $imageTypes)) {

                if (in_array($image["type"], $jpgArray)) {
                    $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                } else {
                    $imageFile = imagecreatefrompng($image["tmp_name"]);
                }

                $imageName    = $movie->imageGenerateName();
                imagejpeg($imageFile, "./images/movies/" . $imageName, 100);
                $movie->image = $imageName;

            } else {
                $message->setMessage("Tipo de imagem inválida, insira uma imagem JPG ou PNG!", "error", "back");
            }
        }

        $movieDao->create($movie);

    } else {
        $message->setMessage("Precisas de adicionar pelo menos um título, uma descrição e uma categoria!", "error", "back");
    }

} elseif ($type === "delete") {

    $id    = filter_input(INPUT_POST, "id");
    $movie = $movieDao->findById($id);

    if ($movie) {

        if ($movie->user_id === $userData->id) {
            $movieDao->destroy($movie->id);
            $message->setMessage("Filme removido com sucesso!", "success", "dashboard.php");
        } else {
            $message->setMessage("Não tens permissão para apagar este filme!", "error", "back");
        }

    } else {
        $message->setMessage("Filme não encontrado!", "error", "index.php");
    }

} elseif ($type === "update") {

    $id          = filter_input(INPUT_POST, "id");
    $title       = filter_input(INPUT_POST, "title");
    $image       = filter_input(INPUT_POST, "image");
    $description = filter_input(INPUT_POST, "description");
    $trailer     = filter_input(INPUT_POST, "trailer");
    $category    = filter_input(INPUT_POST, "category");
    $length      = filter_input(INPUT_POST, "length");

    $movieData = $movieDao->findById($id);

    if ($movieData) {

        if ($movieData->user_id === $userData->id) {

            if (!empty($title) && !empty($description) && !empty($category)) {

                $movieData->title       = $title;
                $movieData->image = $image;
                $movieData->description = $description;
                $movieData->trailer     = $trailer;
                $movieData->category    = $category;
                $movieData->length      = $length;

                if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

                    $image      = $_FILES["image"];
                    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                    $jpgArray   = ["image/jpeg", "image/jpg"];

                    if (in_array($image["type"], $imageTypes)) {

                        if (in_array($image["type"], $jpgArray)) {
                            $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                        } else {
                            $imageFile = imagecreatefrompng($image["tmp_name"]);
                        }

                        $imageName          = $movieData->imageGenerateName();
                        imagejpeg($imageFile, "./images/movies/" . $imageName, 100);
                        $movieData->image   = $imageName;

                    } else {
                        $message->setMessage("Tipo de imagem inválida, insira uma imagem JPG ou PNG!", "error", "back");
                    }
                }

                $movieDao->update($movieData);

                $message->setMessage("Filme atualizado com sucesso!", "success", "dashboard.php"); 

            } else {
                $message->setMessage("Precisas de adicionar pelo menos um título, uma descrição e uma categoria!", "error", "back");
            }

        } else {
            $message->setMessage("Não tens permissão para editar este filme!", "error", "back");
        }

    } else {
        $message->setMessage("Filme não encontrado!", "error", "index.php");
    }

} else {
    $message->setMessage("Informações inválidas!", "error", "index.php");
}