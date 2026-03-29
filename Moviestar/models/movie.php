<?php

    class Movie {

        public $id;
        public $title;
        public $image;
        public $category;
        public $trailer;
        public $description;
        public $length;
        public $user_id;

        public function imageGenerateName() {
                return bin2hex(random_bytes(60)) . ".jpg";
        }


    }

    interface MovieDAOInterface {
        public function buildMovie($data);
        public function create(Movie $movie);
        public function update(Movie $movie);
        public function destroy($id);
        public function findAll();
        public function findById($id);
        public function findByTitle($title);
        public function getLatestMovies();
        public function getMoviesByCategory($category);
        public function getMoviesByUserId($user_id);


    }