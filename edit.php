<?php
    require_once "config/database.php";

    //Receive movie ID
    $id=$_GET['id'] ?? null;

    //Validate ID
    if(filter_var($id,FILTER_VALIDATE_INT) === false || $id<=0){
        die("Invalid Movie ID.");
    }

    //Fetch movie from database
    $sql="SELECT * FROM movies WHERE id = :id";

    $stmt=$pdo->prepare($sql);

    $stmt->execute([':id'=>$id]);

    $movie=$stmt->fetch(PDO::FETCH_ASSOC);

    //Check if movie exists
    if(!$movie){
        die("Movie not found");
    }

    if($_SERVER['REQUEST_METHOD']==='POST'){
        try{
            //Recive data from user
            $title = trim($_POST['title'] ?? '');
            $duration_min = $_POST['duration_min'] ?? '';
            $genre = trim($_POST['genre'] ?? '');
            $language = $_POST['language'] ?? '';
            $release_year = $_POST['release_year'] ?? '';

            //2. Validate data
            // validate title
            if(empty($title)){
                $errors[]="Movie title is required.";
            }
            
            // validate duration
            if(filter_var($duration_min,FILTER_VALIDATE_INT)===false || $duration_min <=0){
                $errors[]="Duration must be a positive integer.";
            }
            
            //validate genre
            if(empty($genre)){
                $errors[]="Genre is required.";
            }

            //validate language
            $allowedLanguages=['English','Arabic','French','Spanish','Turkish','German'];

            if(!in_array($language,$allowedLanguages,true)){
                $errors[]="Please select a valid language.";
            }

            //validate release year
            if(filter_var($release_year,FILTER_VALIDATE_INT)===false || $release_year < 1888 || $release_year > (int)date('Y')){
                $errors[]="Please enter a valid release year.";
            }

            //update movie if there are no validion errors
            if(empty($errors)){

                //sql qurey
                $sql="UPDATE movies 
                    SET title=:title,
                    duration_min=:duration_min,
                    genre=:genre,
                    language=:language,
                    release_year=:release_year
                    WHERE id=:id";

                //prepare ther query
                $stmt=$pdo->prepare($sql);

                //execute the query
                $stmt->execute([
                    ':title'=>$title,
                    ':duration_min'=>$duration_min,
                    ':genre'=>$genre,
                    ':language'=>$language,
                    ':release_year'=>$release_year,
                    ':id'=>$id
                ]);

                //redirect to movies list
                header("Location: index.php");
                exit;
            }
        }
        catch(PDOException $e){
            //handle database errors
            $errors[]="Something went wrong while updating the movie.";
        }
    }

?>
<?php 
    if($_SERVER['REQUEST_METHOD']==='POST' && !empty($errors)){
        $movie=[
            'title'=>$title,
            'duration_min'=>$duration_min,
            'genre'=>$genre,
            'language'=>$language,
            'release_year'=>$release_year,
            'id'=>$id
        ];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie</title>
</head>
<body>

    <!-- error lis -->
    <?php if(!empty($errors)):?>
        <ul>
            <?php foreach($errors as $error):?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach;?>
        </ul>
    <?php endif;?>

    <h1>Edit Movie</h1>
    <form method="post" action="">
        <div class="title">
            <label for="title">Movie Title:</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($movie['title']) ?>" required/>
        </div>

        <div class="duration_min">
            <label for="duration_min">Duration:</label>
            <input type="number" name="duration_min" id="duration_min" value="<?= htmlspecialchars($movie['duration_min']) ?>" required/>
        </div>

        <div class="genre">
            <label for="genre">Movie Genre:</label>
            <input type="text" name="genre" id="genre" value="<?= htmlspecialchars($movie['genre']) ?>" required/>
        </div>

        <div class="language">
            <label for="language">Movie Language:</label>
            <select name="language" id="language" required>
                <?php
                    $languages=[
                        'English',
                        'Arabic',
                        'French',
                        'Spanish',
                        'Turkish',
                        'German'
                    ];
                ?>
                <?php foreach($languages as $language):?>
                    <option 
                    value="<?= htmlspecialchars($language) ?>" 
                    <?= $movie['language'] === $language ?'selected':''?>
                    >
                    <?= htmlspecialchars($language) ?>
                </option>
                <?php endforeach;?>
            </select>
        </div>

        <div class="release_year">
            <label for="release_year">Release Year:</label>
            <input type="number" name="release_year" id="release_year" value="<?= htmlspecialchars($movie['release_year']) ?>" required/>
        </div>

        <input type="hidden" name="id" value="<?= htmlspecialchars($movie['id']) ?>">

        <button type="submit">Update</button>
        
    </form>

    <a href="index.php">&larr;Back to Movies</a>
</body>
</html>