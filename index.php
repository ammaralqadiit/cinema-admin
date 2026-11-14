<?php 
    require_once "config/database.php";
    
    $sql="SELECT * FROM movies ORDER BY id DESC";

    $stmt=$pdo->query($sql);
    $movies=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Cinema Admin</title>
</head>
<body>
    <h1>Cinema Admin</h1>
    <h2>Movies List</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Duration</th>
            <th>Genre</th>
            <th>Language</th>
            <th>Year</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php foreach($movies as $movie):?>

        <tr>
            <td><?= $movie['id'] ?></td>
            <td><?= htmlspecialchars($movie['title']) ?></td>
            <td><?= $movie['duration_min'] ?></td>
            <td><?= htmlspecialchars($movie['genre']) ?></td>
            <td><?= htmlspecialchars($movie['language']) ?></td>
            <td><?= $movie['release_year'] ?></td>
            <td>
                <a href="edit.php?id=<?= $movie['id'] ?>">Edit</a>
            </td>
            <td>
                <form action="delete.php" method="POST">
                    <input type="hidden" name="id" value="<?= $movie['id'] ?>">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this movie?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        
        <?php endforeach;?>
    </table>

    <a href="add.php">&larr; Add a new movie</a>
</body>
</html>