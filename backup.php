<!--This is just a backup file for something i need to edit.. in this case the playlist.php i guess. and the added shuffle and repeat button icons.
it never worked tho so im currently tweaking right now. i decided to just not include the playlist file in the website but i'll keep the shuffle and 
repeat icons because they look pretty although ic ouldnt get them to work as intended. not even chatgpt can save me from this madness that ive created!
oh heavens! may hell open up before me and swallow me as a whole. amen.-->
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
$stmt = $db->pdo->prepare("SELECT * FROM tracks");
$stmt->execute();
$tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VRTX</title>
    <link rel="stylesheet" href="homepage.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/75375cc347.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="pics/vrtx.ico">
</head>
<body>

<div class="main-cont">
    <div class="vertnav">
        <center><h1 class = "logo">vrtx</h1></center>
        <a href="homepage.php" id="options"><i class="fa-solid fa-house" id="nav-icon"></i> HOME</a>
        <a href="search.php" id="options"><i class="fa-solid fa-magnifying-glass" id="nav-icon"></i> SEARCH</a>
        <a href="playlist.php" id="options"><i class="fa-solid fa-music" id="nav-icon"></i> PLAYLISTS</a>
        <a href="account.php" id="options"><i class="fa-solid fa-user" id="nav-icon"></i> ACCOUNT</a>
        <a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket" id="nav-icon"></i> LOGOUT</a> 
    </div>

    <div class="content-homepage">
        <h1>TRACKS</h1>
        <div class="tracks">
            <?php
            $db = new Database();// ORDER BY RAND()
            $stmt = $db->pdo->query("SELECT * FROM tracks");
            while ($row = $stmt->fetch()) {
                echo '<div class="track-item" data-name="' . $row['name'] . '" data-artist="' . $row['artist'] . '" data-cover="' . $row['cover'] . '" data-path="' . $row['path'] . '">';
                echo '<img src="' . $row['cover'] . '" alt="Cover" class="track-cover">';
                echo '<span class="track-name">' . $row['name'] . '</span>';
                echo '<span class="track-artist">' . $row['artist'] . '</span>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>

<div class="play-cont"><!--<i class="fa-solid fa-shuffle"></i> shuffle-->
    <div class="album-info">
        <img id="album-cover" src="cover/Diamond Eyes.jpg" alt="Album Cover" class="album-cover">
        <div class="track-details">
            <span id="track-name" class="track-name">Diamond Eyes</span>
            <span id="track-artist" class="track-artist">Deftones</span>
        </div>
        <div class="shuffle-cont"><i class="fa-solid fa-shuffle" id="shuffle-icon"></i></div>
        <div class="repeat-cont"><i class="fa-solid fa-repeat" id="repeat-icon"></i></div>
        <style>
            .shuffle-cont{
                position: fixed;
                left: 92vh;
                bottom: 8vh;
            }
            .shuffle-cont i{
                font-size: 17px;
                cursor: pointer;
                color:#9c9c9c;
            }
            .shuffle-cont i:hover{
                color: #6114C0;
            }

            .repeat-cont{
                position: fixed;
                left: 138vh;
                bottom: 8.5vh;
            }
            .repeat-cont i{
                font-size: 16px;
                cursor: pointer;
                color:#9c9c9c;
            }
            .repeat-cont i:hover{
                color: #6114C0;
            }

            #shuffle-icon.active {
            color: #6114C0 !important;
            }

            #repeat-icon.active {
            color: #6114C0 !important;
            }

        </style>
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

<script>
let isShuffling = false; // True when shuffle is active
let isRepeating = false; // True when repeat is active
// let currentTrackIndex = 0;

let tracks = Array.from(document.querySelectorAll('.track-item')); // All tracks
let shuffledTracks = [...tracks]; // Make a copy for shuffling logic

const shuffleIcon = document.getElementById('shuffle-icon');
const repeatIcon = document.getElementById('repeat-icon');
const nextButton = document.getElementById('next');
const prevButton = document.getElementById('prev');

function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

// Shuffle button functionality
    shuffleIcon.addEventListener('click', function () {
    isShuffling = !isShuffling; // Toggle shuffle mode
    isRepeating = false; // Disable repeat if shuffle is enabled
    repeatIcon.classList.remove('active'); // Deactivate repeat
    shuffleIcon.classList.toggle('active', isShuffling); // Change shuffle icon color
    
    if (isShuffling) {
        shuffledTracks = shuffleArray([...tracks]); // Randomize the track order
        currentTrackIndex = 0; // Reset to the first track
    } else {
        shuffledTracks = [...tracks]; // Go back to original order
    }
});

// Repeat button functionality
    repeatIcon.addEventListener('click', function () {
    isRepeating = !isRepeating; // Toggle repeat mode
    isShuffling = false; // Disable shuffle if repeat is enabled
    shuffleIcon.classList.remove('active'); // Deactivate shuffle
    repeatIcon.classList.toggle('active', isRepeating); // Change repeat icon color
});

// Function to load the current track
    function loadTrack(index) {
    const track = shuffledTracks[index]; // Get the current track from the shuffled list
    const trackName = track.dataset.name;
    const trackArtist = track.dataset.artist;
    const trackCover = track.dataset.cover;
    const trackPath = track.dataset.path;

    document.getElementById('album-cover').src = trackCover;
    document.getElementById('track-name').textContent = trackName;
    document.getElementById('track-artist').textContent = trackArtist;

    const audio = new Audio(trackPath); // Load audio for the track
    audio.play(); // Play the track
}

// Next button functionality
nextButton.addEventListener('click', function () {
    if (isRepeating) {
        // If repeat is on, replay the same track
        loadTrack(currentTrackIndex);
    } else {
        // If shuffle is on, follow the shuffled order
        currentTrackIndex = (currentTrackIndex + 1) % shuffledTracks.length;
        loadTrack(currentTrackIndex);
    }
});

// Previous button functionality
prevButton.addEventListener('click', function () {
    currentTrackIndex = (currentTrackIndex - 1 + shuffledTracks.length) % shuffledTracks.length;
    loadTrack(currentTrackIndex);
});


</script>

<script src="music-player.js"></script>
</body>
</html>
