<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include '../database/connect.php';

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

// Отримання жанрів для відображення
$genreImagesQuery = "SELECT * FROM genres";
$genreImagesResult = $conn->query($genreImagesQuery);
$genreImages = [];
while ($row = $genreImagesResult->fetch_assoc()) {
    $genreImages[] = $row;
}

// Пошук та фільтрація
$search = isset($_GET['search']) ? $_GET['search'] : '';
$selectedGenres = isset($_GET['genres']) ? $_GET['genres'] : [];
$maxPrice = isset($_GET['max_price']) ? $_GET['max_price'] : 100;
$selectedTags = isset($_GET['tags']) ? $_GET['tags'] : [];

// Створення базового запиту для ігор
$queryGames = "SELECT 'game' AS type, g.id, g.title, g.price, g.genre, g.tags, gp.main_image
 FROM game g
 JOIN game_photoes gp ON g.id = gp.game_id
 WHERE g.title LIKE ? AND g.price <= ?";

// Створення базового запиту для DLC
$queryDlc = "SELECT 'dlc' AS type, d.id, d.title, d.price, d.genre, d.tags, dp.main_image
 FROM dlc d
 JOIN dlc_photoes dp ON d.id = dp.dlc_id
 WHERE d.title LIKE ? AND d.price <= ?";

$params = ["%$search%", $maxPrice];
$types = "sd";

if (!empty($selectedGenres)) {
    $genrePlaceholder = implode(',', array_fill(0, count($selectedGenres), '?'));
    $queryGames .= " AND g.genre IN ($genrePlaceholder)";
    $queryDlc .= " AND d.genre IN ($genrePlaceholder)";
    $params = array_merge($params, $selectedGenres);
    $types .= str_repeat('s', count($selectedGenres));
}

if (!empty($selectedTags)) {
    $tagConditions = implode(" OR ", array_fill(0, count($selectedTags), 'tags LIKE ?'));
    $queryGames .= " AND ($tagConditions)";
    $queryDlc .= " AND ($tagConditions)";
    $params = array_merge($params, array_map(function($tag) { return "%$tag%"; }, $selectedTags));
    $types .= str_repeat('s', count($selectedTags));
}

// Підготовка та виконання запиту для ігор
$stmtGames = $conn->prepare($queryGames);
if (!$stmtGames) {
    die("Error preparing statement: " . $conn->error);
}
$stmtGames->bind_param($types, ...$params);
$stmtGames->execute();
$resultGames = $stmtGames->get_result();

// Підготовка та виконання запиту для DLC
$stmtDlc = $conn->prepare($queryDlc);
if (!$stmtDlc) {
    die("Error preparing statement: " . $conn->error);
}
$stmtDlc->bind_param($types, ...$params);
$stmtDlc->execute();
$resultDlc = $stmtDlc->get_result();

$games = [];
while ($row = $resultGames->fetch_assoc()) {
    $games[] = $row;
}
while ($row = $resultDlc->fetch_assoc()) {
    $games[] = $row;
}

$query = "SELECT g.*, gp.main_image, gp.screenshot1, gp.screenshot2, gp.screenshot3, gp.screenshot4 FROM game g JOIN game_photoes gp ON g.id = gp.game_id WHERE g.id = 3";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game) {
    die("Game not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин ігор</title>
    <script src="../js/script.js"></script>
    <link rel="stylesheet" href="../css/games.css">
</head>
<body>
    <?php
    include "header.php";
    ?>
    <div class="store">
        <div class="main-content">
            <!-- Блок з каруселлю останніх добавлених ігор -->
            <div class="main-block">
                <div class="main-photo">
                    <a href="game_details.php?id=<?php echo $game['id']; ?>&type=game"><img src="data:image/jpeg;base64,<?php echo base64_encode($game['main_image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>"></a>
                </div>
                <div class="thumbnails">
                    <h3 style="font-size:28px; text-align: right;">Gameplay</h3>
                    <h3 style="font-size:28px; text-align: left;">screenshots</h3>
                    <div class="thumbnail">
                        <a href="game_details.php?id=<?php echo $game['id']; ?>&type=game"><img class="img-right" src="data:image/jpeg;base64,<?php echo base64_encode($game['screenshot1']); ?>" alt="Screenshot 1"></a>
                    </div>
                    <div class="thumbnail">
                        <a href="game_details.php?id=<?php echo $game['id']; ?>&type=game"><img class="img-left" src="data:image/jpeg;base64,<?php echo base64_encode($game['screenshot2']); ?>" alt="Screenshot 2"></a>
                    </div>
                    <div class="thumbnail">
                        <a href="game_details.php?id=<?php echo $game['id']; ?>&type=game"><img class="img-right" src="data:image/jpeg;base64,<?php echo base64_encode($game['screenshot3']); ?>" alt="Screenshot 3"></a>
                    </div>
                    <div class="thumbnail">
                        <a href="game_details.php?id=<?php echo $game['id']; ?>&type=game"><img class="img-left" src="data:image/jpeg;base64,<?php echo base64_encode($game['screenshot4']); ?>" alt="Screenshot 4"></a>
                    </div>
                </div>
            </div>

            <!-- Жанри -->
            <div class="genres">
    <h2>Genres</h2>
    <div class="genre-list">
        <?php foreach ($genreImages as $genreImage): ?>
            <div class="genre-item">
                <a href="games.php?genres%5B%5D=<?php echo urlencode($genreImage['name']); ?>&max_price=100" class="genre">
                    <img src="<?php echo htmlspecialchars($genreImage['image_url']); ?>" alt="<?php echo htmlspecialchars($genreImage['name']); ?>" class="genre-image">
                    <span class="genre-label"><?php echo htmlspecialchars($genreImage['name']); ?></span>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

            <!-- Свіжі трейлери -->
            <div class="trailers">
                <h2>Trailers</h2>
                <div class="trailer-list">
                    <iframe style="margin-right: 20px; border-color: #2b2b2b" width="270" height="180" src="https://www.youtube.com/embed/c80dVYcL69E" frameborder="0" allowfullscreen></iframe>
                    <iframe style="margin-right: 20px; border-color: #2b2b2b" width="270" height="180" src="https://www.youtube.com/embed/d02lhvvVSy8" frameborder="0" allowfullscreen></iframe>
                    <iframe style="margin-right: 20px; border-color: #2b2b2b" width="270" height="180" src="https://www.youtube.com/embed/d02lhvvVSy8" frameborder="0" allowfullscreen></iframe>
                    <iframe style="margin-right: 20px; border-color: #2b2b2b" width="270" height="180" src="https://www.youtube.com/embed/MmB9b5njVbA" frameborder="0" allowfullscreen></iframe>
                    <iframe style="margin-right: 20px; border-color: #2b2b2b" width="270" height="180" src="https://www.youtube.com/embed/IRNOoOYVn80" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
