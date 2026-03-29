<?php


    require_once("models/user.php");
    
    $userModel = new User();

    $fullName = $userModel->getFullName($review->user);

    if ($review->user->image == "") {
      $review->user->image  = "user.png";
    }

    


?>

<div class="col-md-12 review">
    <div class="row">
        <div class="col-md-1">
            <div class="profile-image-container profile-image" style="background-image: url('<?= $BASE_URL ?>images/users/<?= $review->image ?>')"></div>
        </div>
        <div class="cold-md-9 author-details-container">
            <h4 class="author-name">
                <a href="#"><?= $fullName ?></a>
            </h4>
            <p><i class="fas fa-star"></i> <?= $review->rating ?>/5</p>
        </div>
        <div class="col-md-12">
            <p class="comment-title">Comentário:</p>
            <p><?= $review->review ?></p>
        </div>
    </div>
</div>