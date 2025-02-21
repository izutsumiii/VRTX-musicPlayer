<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

class Database {
    private $host = 'localhost';
    private $dbname = 'pf117.sql';
    private $username = 'root';
    private $password = '';
    public $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit();
        }
    }
}

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$db = new Database();
$stmt = $db->pdo->prepare("SELECT * FROM tracks WHERE name LIKE :searchTerm OR artist LIKE :searchTerm");
$stmt->execute(['searchTerm' => '%' . $searchTerm . '%']);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search | VRTX</title>
    <link rel="stylesheet" href="homepage.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/75375cc347.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="pics/vrtx.ico">
</head>
<body>

<div class="main-cont">
    <div class="vertnav">
        <center><h1 class="logo">vrtx</h1></center>
        <a href="homepage.php" id="options"><i class="fa-solid fa-house" id="nav-icon"></i> HOME</a>
        <a href="search.php" id="options"><i class="fa-solid fa-magnifying-glass" id="nav-icon"></i> SEARCH</a>
        <!-- <a href="playlist.php" id="options"><i class="fa-solid fa-music" id="nav-icon"></i> PLAYLISTS</a> -->
        <a href="account.php" id="options"><i class="fa-solid fa-user" id="nav-icon"></i> ACCOUNT</a>
        <a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket" id="nav-icon"></i> LOGOUT</a> 
    </div>

    <div class="content-homepage">
        
        <form method="get" action="search.php">
            <input type="text" name="search" class="search-bar" placeholder="Lookin' for some tunes?" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="search-btn">Seek</button>
        </form>
        <h2>LOOK UP 'YO FAVORITE TRACK!</h2>
        <?php if (!empty($results)): ?>
        <div class="tracks">
        <?php 
        shuffle($results);
        $count = 0;
            foreach ($results as $row): 
                if ($count >= 5) break;?>
        <div class="track-item" data-name="<?php echo $row['name']; ?>" data-artist="<?php echo $row['artist']; ?>" data-cover="<?php echo $row['cover']; ?>" data-path="<?php echo $row['path']; ?>">
            <img src="<?php echo $row['cover']; ?>" alt="Cover" class="track-cover">
            <span class="track-name"><?php echo $row['name']; ?></span>
            <span class="track-artist"><?php echo $row['artist']; ?></span>
        </div>
        <?php 
            $count++; 
            endforeach; ?>
            </div>

            <?php else: ?>
                <p style="text-align: center; color: #aaa;">No tracks found for "<?php echo htmlspecialchars($searchTerm); ?>"</p>
            <?php endif; ?>
    </div>
</div>

<div class="play-cont">
    <div class="album-info">
        <img id="album-cover" src="cover/Diamond Eyes.jpg" alt="Album Cover" class="album-cover">
        <div class="track-details">
            <span id="track-name" class="track-name">Diamond Eyes</span>
            <span id="track-artist" class="track-artist">Deftones</span>
        </div>
        <div class="shuffle-cont"><i class="fa-solid fa-shuffle" id="shuffle-icon"></i></div>
        <div class="repeat-cont"><i class="fa-solid fa-repeat" id="repeat-icon"></i></div>
    </div>

    <div class="music-controls">
        <div id="prev" class="music-controls-item">
            <i class="fas fa-backward music-controls-item--icon"></i>
        </div>

        <div id="play" class="music-controls-item">
            <i class="fas fa-play music-controls-item--icon play-icon"></i>
            <div class="play-icon-background"></div>
        </div>

        <div id="next" class="music-controls-item">
            <i class="fas fa-forward music-controls-item--icon"></i>
        </div>
    </div>

    <div class="progress-bar">
        <input type="range" min="0" max="100" value="0" id="progress">
    </div>

    <div class="timestamps">
        <span id="current-time">0:00</span>
        <span id="duration-time">0:00</span>
    </div>

    <div class="volume-control">
        <i class="fas fa-volume-up" id="volume-icon"></i>
        <input type="range" id="volume-slider" min="0" max="100" value="100">
    </div>
</div>

<script src="music-player.js"></script>
</body>
</html>
