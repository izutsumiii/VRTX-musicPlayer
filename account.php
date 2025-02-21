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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['song']) && isset($_FILES['cover']) && isset($_POST['name']) && isset($_POST['artist'])) {
    $songFile = $_FILES['song'];
    $coverFile = $_FILES['cover'];
    $name = $_POST['name'];
    $artist = $_POST['artist'];

    $songDirectory = 'songs/';
    $coverDirectory = 'covers/';

    $songPath = $songDirectory . basename($songFile['name']);
    $coverPath = $coverDirectory . basename($coverFile['name']);


    if (move_uploaded_file($songFile['tmp_name'], $songPath) && move_uploaded_file($coverFile['tmp_name'], $coverPath)) {

        try {
            $db = new Database();
            $stmt = $db->pdo->prepare("INSERT INTO tracks (name, artist, path, cover) VALUES (:name, :artist, :path, :cover)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':artist', $artist);
            $stmt->bindParam(':path', $songPath);
            $stmt->bindParam(':cover', $coverPath);
            
            if (!$stmt->execute()) {
                echo "Error inserting the track: ";
                print_r($stmt->errorInfo());
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }        
    } else {
        echo "Error uploading files.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_name']) && isset($_POST['delete_artist'])) {
    $deleteName = $_POST['delete_name'];
    $deleteArtist = $_POST['delete_artist'];

    try {
        $db = new Database();
        $stmt = $db->pdo->prepare("SELECT path, cover FROM tracks WHERE name = :name AND artist = :artist");
        $stmt->bindParam(':name', $deleteName);
        $stmt->bindParam(':artist', $deleteArtist);
        $stmt->execute();

        $track = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($track) {
            if (file_exists($track['path'])) {
                unlink($track['path']);
            }
            if (file_exists($track['cover'])) {
                unlink($track['cover']);
            }

            $deleteStmt = $db->pdo->prepare("DELETE FROM tracks WHERE name = :name AND artist = :artist");
            $deleteStmt->bindParam(':name', $deleteName);
            $deleteStmt->bindParam(':artist', $deleteArtist);
            if ($deleteStmt->execute()) {
                echo "";
            } else {
                echo "Error deleting the track: ";
                print_r($deleteStmt->errorInfo());
            }
        } else {
            echo "No track found with that name and artist.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | VRTX</title>
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
<h1>ACCOUNT</h1>
<h2>CUSTOMIZATION</h2>

<div class="song-cont">
    <div class="song-item">
        <div class="add-song-cont" id="add-song-btn" title="Add some Tunes!">
            <i class="fa-solid fa-plus"></i>
        </div>
        <div class="cont-text">Add Some Tunes</div>
    </div>

    <!-- Deleterrrr Song -->
    <div class="song-item">
        <div class="delete-song-cont" id="delete-song-btn" title="Delete a Track">
            <i class="fa-solid fa-trash"></i>
        </div>
        <div class="cont-text">Delete a Track</div>
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

        <!-- add song form toggle it btchhhh -->
    <div id="song-overlay"></div>
        <div id="add-song-form" class="add-song-form">
            <h2>UPLOAD YOUR TUNES</h2>
            <div class="back" id="back-btn"><i class="fa-solid fa-xmark"></i></div>
            <form action="account.php" method="POST" enctype="multipart/form-data">
                <label for="name" class = "add-song-lbl">Track Title </label>
                <input type="text" name="name" placeholder="Track Title..." class = "add-song-input" required><br>
                <label for="artist" class = "add-song-lbl">Artist Name</label>
                <input type="text" name="artist" placeholder="Artist..." class = "add-song-input" required><br>
                <label for="song" class = "add-song-lbl">Upload Track</label>
                <input type="file" name="song" accept="audio/*"required><br>
                <label for="cover" class = "add-song-lbl">Upload Cover</label>
                <input type="file" name="cover" accept="image/*" required><br><br>
                <button type="submit" class = "file-upload-btn">Upload Track</button>
            </form>
        </div>
    </div>
</div>

<!-- lil screen for the deleting songs -->
<div id="delete-song-overlay"></div>
<div id="delete-song-form" class="delete-song-form">
    <h2>DELETE YOUR TUNES</h2>
    <div class="back" id="delete-back-btn"><i class="fa-solid fa-xmark"></i></div>
    <form action="account.php" method="POST">
        <label for="delete_name" class="delete-song-lbl">Track Title</label>
        <input type="text" name="delete_name" placeholder="Track Title..." class="delete-song-input" required><br>
        
        <label for="delete_artist" class="delete-song-lbl">Artist Name</label>
        <input type="text" name="delete_artist" placeholder="Artist Name..." class="delete-song-input" required><br><br>
        
        <button type="submit" class="delete-track-btn">Delete Track</button>
    </form>
</div>

<script>//putting this in inline script bc external js never worked
    //add song
    document.getElementById('add-song-btn').addEventListener('click', function() {
    document.getElementById('add-song-form').classList.toggle('visible');
    document.getElementById('song-overlay').classList.toggle('visible');
});

    document.getElementById('back-btn').addEventListener('click', function() {
    document.getElementById('add-song-form').classList.remove('visible');
    document.getElementById('song-overlay').classList.remove('visible');
});

    document.getElementById('song-overlay').addEventListener('click', function() {
    document.getElementById('add-song-form').classList.remove('visible');
    document.getElementById('song-overlay').classList.remove('visible');
});


    document.getElementById('delete-song-btn').addEventListener('click', function() {
    document.getElementById('delete-song-form').classList.toggle('visible');
    document.getElementById('delete-song-overlay').classList.toggle('visible');
});

    document.getElementById('delete-back-btn').addEventListener('click', function() {
    document.getElementById('delete-song-form').classList.remove('visible');
    document.getElementById('delete-song-overlay').classList.remove('visible');
});

document.getElementById('delete-song-overlay').addEventListener('click', function() {
    document.getElementById('delete-song-form').classList.remove('visible');
    document.getElementById('delete-song-overlay').classList.remove('visible');
});
</script>

<script src="music-player.js"></script>
</body>
</html>
