<?php

class Article {

    private $conn;
    private $table = 'articles';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getExcerpt($content, $length = 100)
    {
        if(strlen($content) > $length){
            return substr($content, 0, $length) . "...";
        }
        return $content;
    }


    public function get_all()
    {

        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getArticleById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $article  = $stmt->fetch(PDO::FETCH_OBJ);

        if($article){

            if($article->user_id == $_SESSION['user_id']){
                return $article;
            } else {
//             echo "<h1 style='text-align: center'>You do not have permission</h1>";

                redirect("admin.php");

                exit;
            }


            } else {

                return false;
        }
    }

    public function deleteWithImage($id)
    {

        $article =  $this->getArticleById($id);


        if($article){

            // Check for user ownership
            if($article->user_id == $_SESSION['user_id']) {

                if(!empty($article->image) && file_exists($article->image)){
                    if(!unlink($article->image)){
                        return false;
                    }
                }
                $query = "DELETE FROM " . $this->table . " WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                return $stmt->execute();

            } else {
                redirect("admin.php");
            }

                }




        return false;
    }







    public function getArticleWithOwnerByID($id)
    {
        $query = "SELECT
                    articles.id,
                    articles.title,
                    articles.content,
                    articles.image,
                    articles.created_at,
                    users.username AS author,
                    users.email AS author_email
                  FROM " . $this->table . "
                  JOIN users ON articles.user_id = users.id
                  WHERE articles.id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $article  = $stmt->fetch(PDO::FETCH_OBJ);

        if($article){
            return $article;
        } else {
            return false;
        }







        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $article  = $stmt->fetch(PDO::FETCH_OBJ);

        if($article){
            return $article;
        } else {
            return false;
        }


    }


    public function getArticlesByUser($userId)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);

    }




    public function formatCreatedAt($date)
    {
        return date('F j, Y', strtotime($date));
    }


    public function create($title, $content, $author_id, $created_at, $image)
    {
        $query = "INSERT INTO " . $this->table . " (title, content, user_id, created_at, image)
                  VALUES (:title, :content, :user_id, :created_at, :image)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':user_id', $author_id);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':image', $image);
        return $stmt->execute();

    }

    public function updateWithImage()
    {

    }


    public function uploadImage($file)
    {

        $imagePath = '';
        $targetDir = 'uploads/';
        $error = '';

        if(!is_dir($targetDir)){
            mkdir($targetDir, 0755, true);
        }

        if(isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0){

            $targetFile = $targetDir . basename($_FILES['featured_image']['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if(in_array( $imageFileType, $allowedTypes)) {
                $uniqueFileName = uniqid() . "_" . time() . "." . $imageFileType;
                $targetFile = $targetFile . "_" . $uniqueFileName;
                if(move_uploaded_file($_FILES['featured_image']['tmp_name'], $targetFile)){
                    return $targetFile;
                } else {
                    return "There was an error uploading the file";
                }

            } else {
                return "Only JPG, JPEG, PNG, and GIF files are allowed";
            }

        }

        return '';

    }










}