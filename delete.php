<?php
    //connect to database
    require_once "config/database.php";

    //Check request method
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        die("Invalid Request");
    }
    
    //receive movie id
    $id=$_POST['id'] ?? null;

    //validate 
    if(filter_var($id,FILTER_VALIDATE_INT) === false || $id <= 0){
        die("Invalid Movie ID");
    }

    //delete movie
    $sql="DELETE FROM movies WHERE id = :id";

    $stmt=$pdo->prepare($sql);

    $stmt->execute([':id'=>$id]);

    //redirect to movie list
    header("Location:index.php");
    exit;
?>