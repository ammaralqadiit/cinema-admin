<?php
    require_once "config/database.php";
    
    $errors=[];

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        //1. Receive data from user
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

        //Insert movie if there are no validion errors
        if(empty($errors)){

            //sql qurey
            $sql="INSERT INTO movies (title,duration_min,genre,language,release_year)
                values (:title,:duration_min,:genre,:language,:release_year)";

            //prepare ther query
            $stmt=$pdo->prepare($sql);

            //execute the query
            $stmt->execute([
                ':title'=>$title,
                ':duration_min'=>$duration_min,
                ':genre'=>$genre,
                ':language'=>$language,
                ':release_year'=>$release_year
            ]);

            //redirect to movies list
            header("Location: index.php");
            exit;
        }
    } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie</title>
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

    <a href="index.php">&larr; Back to Movies</a>

    <!-- request values from user -->
    <form method="post" action="">

        <div class="title">
            <label for="title">Movie Title:</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($title ?? '') ?>" required/>
        </div>

        <div class="duration_min">
            <label for="duration_min">Duration:</label>
            <input type="number" name="duration_min" id="duration_min" value="<?= htmlspecialchars($duration_min ?? '') ?>" required/>
        </div>

        <div class="genre">
            <label for="genre">Movie Genre:</label>
            <input type="text" name="genre" id="genre" value="<?= htmlspecialchars($genre ?? '') ?>" required/>
        </div>

        <div class="language">
            <label for="language">Movie Language:</label>
            <select name="language" id="language" required>
                <option value="" disabled selected>Select Language</option>
                <option value="English" <?= ($language ?? '')==='English'?'selected':'' ?>>English</option>
                <option value="Arabic" <?= ($language ?? '')==='Arabic'?'selected':'' ?>>Arabic</option>
                <option value="French" <?= ($language ?? '')==='French'?'selected':'' ?>>French</option>
                <option value="Spanish" <?= ($language ?? '')==='Spanish'?'selected':'' ?>>Spanish</option> 
                <option value="Turkish" <?= ($language ?? '')==='Turkish'?'selected':'' ?>>Turkish</option>
                <option value="German" <?= ($language ?? '')==='German'?'selected':'' ?>>German</option>
            </select>
        </div>

        <div class="release_year">
            <label for="release_year">Release Year:</label>
            <input type="number" name="release_year" id="release_year" value="<?= htmlspecialchars($release_year ?? '') ?>" required/>
        </div>
        
        <button type="submit" id="submit">Submit</button>
    </form>
</body>
</html>