<?php

    class Review {

        public $id;
        public $movie_id;
        public $user_id;
        public $rating;
        public $review;
        public $created_at;

    }

    interface ReviewDAOInterface {

        public function buildReview($data);
        public function create(Review $review);
        public function getMoviesReview($id);
        public function hasAlreadyReviewed($movie_id, $user_id);
        public function getRatings($id);
        
    }