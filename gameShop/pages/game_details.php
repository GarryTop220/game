<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include '../database/connect.php';

$id = $_GET['id'];
$type = $_GET['type'];

if ($type == 'game') {
    $query = "SELECT g.*, gp.main_image, gp.screenshot1, gp.screenshot2, gp.screenshot3, gp.screenshot4 FROM game g JOIN game_photoes gp ON g.id = gp.game_id WHERE g.id = ?";
} else {
    $query = "SELECT d.*, dp.main_image, dp.screenshot1, dp.screenshot2, dp.screenshot3, dp.screenshot4 FROM dlc d JOIN dlc_photoes dp ON d.id = dp.dlc_id WHERE d.id = ?";
}

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game) {
    die("Game not found");
}

$developer_query = "SELECT name FROM developer WHERE id = ?";
$publisher_query = "SELECT name FROM publisher WHERE id = ?";

$dev_stmt = $conn->prepare($developer_query);
$dev_stmt->bind_param("i", $game['developer_id']);
$dev_stmt->execute();
$developer_result = $dev_stmt->get_result();
$developer = $developer_result->fetch_assoc();

$pub_stmt = $conn->prepare($publisher_query);
$pub_stmt->bind_param("i", $game['publisher_id']);
$pub_stmt->execute();
$publisher_result = $pub_stmt->get_result();
$publisher = $publisher_result->fetch_assoc();

$sys_query = "SELECT * FROM systemrequirements WHERE game_id = ? OR dlc_id = ?";
$sys_stmt = $conn->prepare($sys_query);
$sys_stmt->bind_param("ii", $id, $id);
$sys_stmt->execute();
$sys_result = $sys_stmt->get_result();
$system_requirements = $sys_result->fetch_assoc();

$reviews_query = "SELECT r.*, p.nickname, p.avatar FROM reviews r JOIN profile p ON r.user_id = p.id WHERE r.game_id = ?";
$reviews_stmt = $conn->prepare($reviews_query);
$reviews_stmt->bind_param("i", $id);
$reviews_stmt->execute();
$reviews_result = $reviews_stmt->get_result();
$reviews = $reviews_result->fetch_all(MYSQLI_ASSOC);

$user_id = $_SESSION['user_id'];
$has_reviewed_query = "SELECT COUNT(*) FROM reviews WHERE game_id = ? AND user_id = ?";
$has_reviewed_stmt = $conn->prepare($has_reviewed_query);
$has_reviewed_stmt->bind_param("ii", $id, $user_id);
$has_reviewed_stmt->execute();
$has_reviewed_result = $has_reviewed_stmt->get_result();
$has_reviewed = $has_reviewed_result->fetch_row()[0] > 0;

$has_purchased_query = "SELECT COUNT(*) FROM purchase_items pi JOIN purchases p ON pi.purchase_id = p.id WHERE (pi.game_id = ? OR pi.dlc_id = ?) AND p.user_id = ?";
$has_purchased_stmt = $conn->prepare($has_purchased_query);
$has_purchased_stmt->bind_param("iii", $id, $id, $user_id);
$has_purchased_stmt->execute();
$has_purchased_result = $has_purchased_stmt->get_result();
$has_purchased = $has_purchased_result->fetch_row()[0] > 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($game['title']); ?></title>
    <link rel="stylesheet" href="../css/game_details.css">
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function changeMainImage(src) {
            document.getElementById('pickedScreen').src = src;
        }
    </script>
</head>
<body>
    <?php include "header.php"; ?>
    <div class="game-details">
        <div class="screenshots">
            <div class="picked-screen">
                <img id="pickedScreen" src="data:image/jpeg;base64,<?php echo base64_encode($game['screenshot1']); ?>" alt="Screenshot 1">
            </div>
            <div class="screenshot-container">
                <iframe src="https://www.youtube.com/embed/<?php echo htmlspecialchars($game['trailer']); ?>" frameborder="0" allowfullscreen></iframe>
                <img onclick="changeMainImage('data:image/jpeg;base64,<?php echo base64_encode($game['screenshot1']); ?>')" src="data:image/jpeg;base64,<?php echo base64_encode($game['screenshot1']); ?>" alt="Screenshot 1">
                <img onclick="changeMainImage('data:image/jpeg;base64,<?php echo base64_encode($game['screenshot2']); ?>')" src="data:image/jpeg;base64,<?php echo base64_encode($game['screenshot2']); ?>" alt="Screenshot 2">
                <img onclick="changeMainImage('data:image/jpeg;base64,<?php echo base64_encode($game['screenshot3']); ?>')" src="data:image/jpeg;base64,<?php echo base64_encode($game['screenshot3']); ?>" alt="Screenshot 3">
                <img onclick="changeMainImage('data:image/jpeg;base64,<?php echo base64_encode($game['screenshot4']); ?>')" src="data:image/jpeg;base64,<?php echo base64_encode($game['screenshot4']); ?>" alt="Screenshot 4">
            </div>
        </div>
        <div class="details">
            <div class="main-image">
                <img id="mainImage" src="data:image/jpeg;base64,<?php echo base64_encode($game['main_image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>">
            </div>
            <h1><?php echo htmlspecialchars($game['title']); ?></h1>
            <div class="info">
                <div class="tags-genre">
                    <?php foreach (explode(',', $game['genre']) as $genre): ?>
                        <p><?php echo htmlspecialchars($genre); ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="tags-genre">
                    <?php foreach (explode(',', $game['tags']) as $tag): ?>
                        <p><?php echo htmlspecialchars($tag); ?></p>
                    <?php endforeach; ?>
                </div>
                <p><?php echo htmlspecialchars($game['description']); ?></p>
                <div class="additional-info">
                    <p><strong>Developer:</strong> <a href="games.php?developer=<?php echo urlencode($developer['name']); ?>"><?php echo htmlspecialchars($developer['name']); ?></a></p>
                    <p><strong>Publisher:</strong> <a href="games.php?publisher=<?php echo urlencode($publisher['name']); ?>"><?php echo htmlspecialchars($publisher['name']); ?></a></p>
                    <p><strong>Release Date:</strong> <?php echo htmlspecialchars($game['release_date']); ?></p>
                    <div class="reviews">
                        <!--<p><strong>Positive Reviews:</strong> <?php echo $game['positive_reviews_percentage']; ?>%</p>
                        <img src="<?php echo ($game['positive_reviews_percentage'] > 70) ? 'https://cdn-icons-png.flaticon.com/512/4926/4926585.png' : 'https://cdn-icons-png.freepik.com/512/4926/4926589.png'; ?>" alt="Public Opinion">-->
                    </div>
            <form method="post" action="cart.php">
                <?php if ($type == 'game'): ?>
                    <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                <?php else: ?>
                    <input type="hidden" name="dlc_id" value="<?php echo $game['id']; ?>">
                <?php endif; ?>
                <button type="submit" class="buy-button">Додати до кошика</button>
            </form>
                </div>
            </div>
        </div>
    </div>

    <?php if ($type == 'dlc'): ?>
        <div class="main-game">
            <a href="game_details.php?id=<?php echo $game['game_id']; ?>&type=game">Main Game</a>
        </div>
    <?php endif; ?>

    <div class="system-requirements">
        <h2>System Requirements</h2>
        <table>
            <tr><td>Operating System:</td><td><?php echo htmlspecialchars($system_requirements['os']); ?></td></tr>
            <tr><td>Processor:</td><td><?php echo htmlspecialchars($system_requirements['processor']); ?></td></tr>
            <tr><td>Memory:</td><td><?php echo htmlspecialchars($system_requirements['memory']); ?></td></tr>
            <tr><td>Video Card:</td><td><?php echo htmlspecialchars($system_requirements['video_card']); ?></td></tr>
            <tr><td>Disk Space:</td><td><?php echo htmlspecialchars($system_requirements['hard_disk_space']); ?></td></tr>
            <tr><td>Other Requirements:</td><td><?php echo htmlspecialchars($system_requirements['other_requirements']); ?></td></tr>
        </table>
    </div>

    <div class="languages-reviews">
    <div class="languages">
        <h2>Languages</h2>
        <table>
            <tr><th>Language</th><th>Subtitles</th><th>Voice</th></tr>
            <?php
            $languages = explode(',', $game['subtitle_languages']);
            $voice_languages = explode(',', $game['voice_languages']);
            foreach ($languages as $language) {
                echo "<tr><td>" . htmlspecialchars($language) . "</td><td>" . (in_array($language, $languages) ? '+' : '') . "</td><td>" . (in_array($language, $voice_languages) ? '+' : '') . "</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="reviews-section">
        <h2>Reviews</h2>
        <?php if ($has_purchased && !$has_reviewed): ?>
            <div class="add-review">
                <form action="../database/submit_review.php" method="POST" onsubmit="return validateReviewForm()">
                    <input type="hidden" name="game_id" value="<?php echo $id; ?>">
                    <div>
                        <label for="review">Your Review:</label><br>
                        <textarea id="review" name="review" required></textarea>
                    </div>
                    <div>
                        <label for="rating">Rating:</label><br>
                        <select id="rating" name="rating" required>
                            <option value="positive">Positive</option>
                            <option value="negative">Negative</option>
                        </select>
                    </div>
                    <div>
                        <label for="review_language">Review Language:</label><br>
                        <input type="text" id="review_language" name="review_language" required value="English">
                    </div>
                    <button type="submit">Submit Review</button>
                </form>
            </div>
        <?php elseif (!$has_purchased): ?>
            <p>You need to purchase this game to leave a review.</p>
        <?php else: ?>
            <p>You have already reviewed this game.</p>
        <?php endif; ?>
    </div>  
</div>
    <div class="display-reviews">
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <div class="review-header">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($review['avatar']); ?>" alt="Avatar" class="avatar">
                    <h3><?php echo htmlspecialchars($review['nickname']); ?></h3>
                </div>
                <p><strong><?php echo htmlspecialchars($review['review_language']); ?>:</strong> <?php echo htmlspecialchars($review['review_text']); ?></p>
                <p><em><?php echo htmlspecialchars($review['review_date']); ?></em></p>
                <p><strong>Rating:</strong> <?php echo htmlspecialchars($review['rating']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>
