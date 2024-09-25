<?php
require_once "partials/header.php";
include base_path("partials/navbar.php");
include base_path("partials/hero.php");


$article = new Article();
$articles = $article->get_all();

?>

<!-- Main Content -->
<main class="container my-5">
    <?php if(!empty($articles)): ?>
    <?php foreach ($articles as $articleItem): ?>
    <!-- Blog Post 1 -->
    <div class="row mb-4">
        <div class="col-md-4">
            <?php if(!empty($articleItem->image)): ?>

                <img
                        src="<?php echo htmlspecialchars($articleItem->image)?>"
                        class="img-fluid"
                        alt="Blog Post Image"
                        style="width: 350px;height: 200px"
                >

            <?php else: ?>

            <img
                    src="https://via.placeholder.com/350x200"
                    class="img-fluid"
                    alt="Blog Post Image"
            >

         <?php endif; ?>
        </div>
        <div class="col-md-8">
            <h2><?php echo htmlspecialchars($articleItem->title); ?></h2>
            <p>

               <?php echo htmlspecialchars($article->getExcerpt($articleItem->content, 90)) ?>
            </p>
            <a href="article.php?id=<?php echo $articleItem->id; ?>" class="btn btn-primary">Read More</a>
        </div>
    </div>

   <?php endforeach; ?>
    <?php endif; ?>
</main>


<?php
include "partials/footer.php";
?>
