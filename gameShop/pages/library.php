<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include '../database/connect.php'; // файл для підключення до бази даних

// Отримання всіх жанрів та тегів
$genresQuery = "SELECT DISTINCT genre FROM game UNION SELECT DISTINCT genre FROM dlc";
$genresResult = $conn->query($genresQuery);
$genres = [];
while ($row = $genresResult->fetch_assoc()) {
    $genresArray = explode(',', $row['genre']);
    foreach ($genresArray as $genre) {
        if (!in_array(trim($genre), $genres)) {
            $genres[] = trim($genre);
        }
    }
}

$tagsQuery = "SELECT DISTINCT tags FROM game UNION SELECT DISTINCT tags FROM dlc";
$tagsResult = $conn->query($tagsQuery);
$tags = [];
while ($row = $tagsResult->fetch_assoc()) {
    $tagsArray = explode(',', $row['tags']);
    foreach ($tagsArray as $tag) {
        if (!in_array(trim($tag), $tags)) {
            $tags[] = trim($tag);
        }
    }
}

// Пошук та фільтрація
$userId = $_SESSION['user_id'];
$search = isset($_GET['search']) ? $_GET['search'] : '';
$selectedGenres = isset($_GET['genres']) ? $_GET['genres'] : [];
$selectedTags = isset($_GET['tags']) ? $_GET['tags'] : [];
$order = isset($_GET['order']) ? $_GET['order'] : 'asc';

// Створення базового запиту для придбаних ігор
$queryGames = "SELECT 'game' AS type, g.id, g.title, g.genre, g.tags, gp.main_image, p.name AS publisher_name
 FROM purchases pu
 JOIN purchase_items pi ON pu.id = pi.purchase_id
 JOIN game g ON pi.game_id = g.id
 JOIN game_photoes gp ON g.id = gp.game_id
 JOIN publisher p ON g.publisher_id = p.id
 WHERE pu.user_id = ? AND g.title LIKE ?";

$params = [$userId, "%$search%"];
$types = "is";

if (!empty($selectedGenres)) {
    $genreConditions = implode(" OR ", array_fill(0, count($selectedGenres), 'g.genre LIKE ?'));
    $queryGames .= " AND ($genreConditions)";
    $params = array_merge($params, array_map(function($genre) { return "%$genre%"; }, $selectedGenres));
    $types .= str_repeat('s', count($selectedGenres));
}

if (!empty($selectedTags)) {
    $tagConditions = implode(" OR ", array_fill(0, count($selectedTags), 'g.tags LIKE ?'));
    $queryGames .= " AND ($tagConditions)";
    $params = array_merge($params, array_map(function($tag) { return "%$tag%"; }, $selectedTags));
    $types .= str_repeat('s', count($selectedTags));
}

$queryGames .= " ORDER BY pu.purchase_date $order";

// Підготовка та виконання запиту для ігор
$stmtGames = $conn->prepare($queryGames);
if (!$stmtGames) {
    die("Error preparing statement: " . $conn->error);
}
$stmtGames->bind_param($types, ...$params);
$stmtGames->execute();
$resultGames = $stmtGames->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Моя бібліотека</title>
    <link rel="stylesheet" href="../css/library.css">
    <link rel="stylesheet" href="../css/checkbox.css">
</head>
<body>
    <?php include "header.php"; ?>
    <div class="library">
        <div class="main-content">
            <div class="game-list">
                <?php while ($game = $resultGames->fetch_assoc()): ?>
                    <div class="game-item">
                        <a class="game-item">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($game['main_image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
                            <div class="game-info">
                                <h3><?php echo htmlspecialchars($game['title']); ?></h3>
                                <p><?php echo htmlspecialchars($game['publisher_name']); ?></p>
                                <div class="buttons">
                                    <!--<a class="download-button">Скачати</a>-->
                                    <a href="game_details.php?id=<?php echo $game['id']; ?>&type=game" class="details-button">Сторінка гри</a>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="sidebar">
            <!-- Пошук -->
            <div class="search">
                <h2>Пошук</h2>
                <form method="GET">
                    <input type="text" name="search" placeholder="Пошук ігор..." value="<?php echo htmlspecialchars($search); ?>">
                    <input class="search_games" type="submit" value="Шукати">
                </form>
            </div>

            <!-- Фільтри -->
            <div class="filter-search">
                <form method="GET">
                    <div class="filter-options">
                        <h3>Жанри</h3>
                        <?php foreach ($genres as $genre): ?>
                            <label>
                                <input type="checkbox" name="genres[]" value="<?php echo htmlspecialchars($genre); ?>" <?php echo in_array($genre, $selectedGenres) ? 'checked' : ''; ?>>
                                <span><?php echo htmlspecialchars($genre); ?></span>
                            </label><br>
                        <?php endforeach; ?>

                        <!--<h3>Максимальна ціна</h3>   
                        <input type="number" name="max_price" value="<?php echo htmlspecialchars($maxPrice); ?>" max="100" min="0" step="0.01"><br>-->

                        <h3>Теги</h3>
                        <?php foreach ($tags as $tag): ?>
                            <label>
                                <input type="checkbox" name="tags[]" value="<?php echo htmlspecialchars($tag); ?>" <?php echo in_array($tag, $selectedTags) ? 'checked' : ''; ?>>
                                <span><?php echo htmlspecialchars($tag); ?></span>
                            </label><br>
                        <?php endforeach; ?>
                        
                        <input type="submit" value="Застосувати фільтри">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
