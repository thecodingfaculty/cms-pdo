<?php
include "partials/admin/header.php";
include "partials/admin/navbar.php";


$articleId = isset($_GET['id']) ? (int)$_GET['id'] : null;

$article = new Article();
$articleData = $article->getArticleById($articleId);

if(isPostRequest()){
    $title = getPostData($_POST['title']);
    $content = getPostData($_POST['content']);
    $created_at = getPostData($_POST['date']);
    $user_id = $_SESSION['user_id'];
    $imagePath = $article->uploadImage($_FILES['featured_image']);
    if(strpos($imagePath, 'error') === false) {

        if($article->updateWithImage($title, $content, $created_at, $user_id, $imagePath  = null)){
            redirect("admin.php");
        } else {

            redirect("edit-article?id={$articleId}");
        }


    }

}





?>


<!-- Main Content -->
<main class="container my-5">
    <h2>Edit Article</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title *</label>
            <input name="title" type="text" class="form-control" id="title" value="<?php echo $articleData->title; ?>" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Published Date *</label>
            <input name="date" type="date" class="form-control" id="date" value="2045-01-01" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content *</label>
            <textarea name="content" class="form-control" id="content" rows="10" required><?php echo $articleData->content; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Featured Image URL</label>
            <input name="featured_image" type="file" class="form-control" id="image" value="https://example.com/image.jpg">
        </div>
        <button type="submit" class="btn btn-primary">Update Article</button>
        <a href="admin.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</main>



<?php include "partials/admin/footer.php"; ?>
