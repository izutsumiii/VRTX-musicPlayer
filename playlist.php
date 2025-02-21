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

$db = new Database();
$stmt = $db->pdo->prepare("SELECT * FROM playlists WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlists | VRTX</title>
    <link rel="stylesheet" href="homepage.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/75375cc347.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="pics/vrtx.ico">
</head>
<body>

<div class="main-cont">
<div class="vertnav">
    <center><h1 class = "logo">vrtx</h1></center>
    <a href="homepage.php" id="options"><i class="fa-solid fa-house" id = "nav-icon"></i> HOME</a>
    <a href="search.php" id="options"><i class="fa-solid fa-magnifying-glass" id = "nav-icon"></i> SEARCH</a>
    <a href="playlist.php" id="options"><i class="fa-solid fa-music" id = "nav-icon"></i> PLAYLISTS</a>
    <a href="account.php" id="options"><i class="fa-solid fa-user" id = "nav-icon"></i> ACCOUNT</a>
    <a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket" id = "nav-icon"></i> LOGOUT</a> 
</div>

<div class="content-homepage">
    <h1>PLAYLISTS</h1>
    <div class="playlist-cont">
        <div class="add-playlist-cont" title="Create a playlist" id="addPlaylistBtn">
            <i class="fa-solid fa-plus"></i>
        </div>
        <div class="play-con-text">Create a Playlist</div>

        <div class="playlists">
        <?php foreach ($playlists as $playlist): ?>
        <div class="playlist-item" data-playlist-id="<?= $playlist['id'] ?>">
            <h3><?= htmlspecialchars($playlist['playlist_name']) ?></h3>
            <a href="viewPlaylist.php?playlist_id=<?= $playlist['id'] ?>">View Songs</a>
        </div>
    <?php endforeach; ?>
</div>

    </div>
</div>

<div class="playlist-form" id="playlist-form">
    <div class="exit" id="exit-btn"><i class="fa-solid fa-xmark"></i></div>
    <input type="text" placeholder="Playlist Name..." class="playlist-name-bar" id="playlist-name-input">
    <center><h1>FEATURE COMING SOON!</h1></center>
    <button class="playlist-btn" id="createPlaylistBtn">Create</button>
    <div class="add-playlist-songs"></div>
</div>

<div class="playlist-overlay" id="playlist-overlay"></div>



    
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
