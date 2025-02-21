window.addEventListener('DOMContentLoaded', function() {
    const playButton = document.getElementById('play');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');
    const playIcon = playButton.querySelector('.play-icon');
    const progress = document.getElementById('progress');
    const currentTimeElement = document.getElementById('current-time');
    const durationElement = document.getElementById('duration-time');
    const contentTrackItems = document.querySelectorAll('.track-item');
    const volumeIcon = document.getElementById('volume-icon');
    const volumeSlider = document.getElementById('volume-slider');

    let currentTrackIndex = 0;
    let isPlaying = false;
    let audioElement = null;
    let lastVolume = 1.0;

    function initializeAudio(trackPath) {
        if (audioElement) {
            audioElement.pause();
        }
        audioElement = new Audio(trackPath);
        audioElement.volume = volumeSlider.value / 100; // Sync initial volume with slider
        audioElement.addEventListener('timeupdate', updateProgress);
        audioElement.addEventListener('loadedmetadata', function() {
            durationElement.textContent = formatTime(audioElement.duration);
        });
        audioElement.addEventListener('ended', playNextTrack);
    }

    playButton.addEventListener('click', function() {
        if (!audioElement) return;

        if (audioElement.paused) {
            audioElement.play();
            playIcon.classList.add('fa-pause');
            playIcon.classList.remove('fa-play');
        } else {
            audioElement.pause();
            playIcon.classList.add('fa-play');
            playIcon.classList.remove('fa-pause');
        }
    });

    prevButton.addEventListener('click', function() {
        currentTrackIndex = (currentTrackIndex - 1 + contentTrackItems.length) % contentTrackItems.length;
        changeTrack();
    });

    //  next Track
    nextButton.addEventListener('click', function() {
        playNextTrack();
    });

    function playNextTrack() {
        currentTrackIndex = (currentTrackIndex + 1) % contentTrackItems.length;
        changeTrack();
    }

    // Change track 
    function changeTrack() {
        const trackPath = contentTrackItems[currentTrackIndex].getAttribute('data-path');
        const trackCover = contentTrackItems[currentTrackIndex].getAttribute('data-cover');
        const trackName = contentTrackItems[currentTrackIndex].getAttribute('data-name');
        const trackArtist = contentTrackItems[currentTrackIndex].getAttribute('data-artist');

        initializeAudio(trackPath);
        audioElement.play();

        // Update cover or something
        document.getElementById('album-cover').src = trackCover;
        document.getElementById('track-name').textContent = trackName;
        document.getElementById('track-artist').textContent = trackArtist;

        playIcon.classList.add('fa-pause');
        playIcon.classList.remove('fa-play');
    }


    contentTrackItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            currentTrackIndex = index;
            changeTrack();
        });
    });

    function updateProgress() {
        if (!audioElement) return;

        const progressValue = (audioElement.currentTime / audioElement.duration) * 100;
        progress.value = progressValue;


        currentTimeElement.textContent = formatTime(audioElement.currentTime);


        const progressColor = progressValue === 100 
            ? '#6114C0' // Full violet if track is done
            : `linear-gradient(to right, #6114C0 ${progressValue}%, #585757 ${progressValue}%)`;
        progress.style.background = progressColor;
    }

    progress.addEventListener('input', function() {
        if (!audioElement) return;

        audioElement.currentTime = (progress.value / 100) * audioElement.duration;
    });

    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
    }

    volumeIcon.addEventListener('click', () => {
    if (audioElement.volume > 0) {
        lastVolume = audioElement.volume; 
        audioElement.volume = 0;
        volumeSlider.value = 0; 
        volumeIcon.classList.remove('fa-volume-up');
        volumeIcon.classList.add('fa-volume-mute');
    } else {
        audioElement.volume = lastVolume; 
        volumeSlider.value = lastVolume * 100; 
        volumeIcon.classList.remove('fa-volume-mute');
        volumeIcon.classList.add('fa-volume-up');
    }
});

// ** Control Volume Using Slider **
    volumeSlider.addEventListener('input', (e) => {
    const volume = e.target.value / 100; 
    audioElement.volume = volume;
    lastVolume = volume; 

  
    if (volume === 0) {
        volumeIcon.classList.remove('fa-volume-up');
        volumeIcon.classList.add('fa-volume-mute');
    } else {
        volumeIcon.classList.remove('fa-volume-mute');
        volumeIcon.classList.add('fa-volume-up');
    }

 
    const volumeValue = volume * 100; 
    const volumeColor = `linear-gradient(to right, #6114C0 ${volumeValue}%, #585757 ${volumeValue}%)`;
    volumeSlider.style.background = volumeColor;
});
});

//playlist.php part-- i never got it to work so this one is uselesssssssssssssssssssssssssssssssssssssssssssssssssssssss
document.getElementById('addPlaylistBtn').addEventListener('click', function() {
    document.getElementById('playlist-form').style.display = 'block';
    document.getElementById('playlist-overlay').style.display = 'block';
});

document.getElementById('exit-btn').addEventListener('click', function() {
    document.getElementById('playlist-form').style.display = 'none';
    document.getElementById('playlist-overlay').style.display = 'none';
});

document.getElementById('playlist-overlay').addEventListener('click', function() {
    document.getElementById('playlist-form').style.display = 'none';
    document.getElementById('playlist-overlay').style.display = 'none';
});

document.getElementById('createPlaylistBtn').addEventListener('click', function() {
    const playlistName = document.getElementById('playlist-name-input').value;
    if (playlistName) {
 
        console.log('Playlist created: ' + playlistName);
        document.getElementById('playlist-form').style.display = 'none';
        document.getElementById('playlist-overlay').style.display = 'none';
    } else {
        alert('Please enter a playlist name');
    }
});


