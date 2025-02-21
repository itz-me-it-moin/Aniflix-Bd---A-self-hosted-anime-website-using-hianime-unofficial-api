<?php
// Function to make a cURL request with SSL bypass
function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return ['error' => 'cURL Error: ' . curl_error($ch)];
    }
    curl_close($ch);
    return $response;
}

$episodeId = isset($_GET['ep']) ? $_GET['ep'] : null;
$skipTime = isset($_GET['skip']) ? $_GET['skip'] : null;

if ($episodeId) {
    $apiUrl = "https://ani-api.ct.ws/api.php?ep={$episodeId}&server=hd-1&cat=sub";
    $response = fetchData($apiUrl);

    if (isset($response['error'])) {
        echo "<div style='color: red;'>Error: " . $response['error'] . "</div>";
    } else {
        $data = json_decode($response, true);

        if ($data && isset($data['success']) && $data['success']) {
            $videoUrl = $data['source'];
            $subtitleUrl = $data['subtitles'] ?? null;

            // If the skip time is provided, convert it to seconds
            $skipSeconds = 0;
            if ($skipTime) {
                list($hours, $minutes, $seconds) = explode(':', $skipTime);
                $skipSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
            }

            echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JW Player HLS Demo</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            background-color: #121212;
            font-family: 'Poppins', sans-serif;
        }
        #player-container {
            width: 100%;
            height: 100%;
            position: relative;
        }
        .jwplayer {
            width: 100% !important;
            height: 100% !important;
        }
        #loading-screen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #121212;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .loading-circle {
            width: 80px;
            height: 80px;
            border: 4px solid transparent;
            border-top: 4px solid #0ff;
            border-radius: 50%;
            animation: spin 1s linear infinite, glow 1s ease-in-out infinite alternate;
            box-shadow: 0 0 20px #0ff;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes glow {
            from {
                box-shadow: 0 0 5px #0ff,
                           0 0 10px #0ff,
                           0 0 15px #0ff;
            }
            to {
                box-shadow: 0 0 10px #0ff,
                           0 0 20px #0ff,
                           0 0 30px #0ff;
            }
        }
        #loading-screen.hidden {
            display: none;
        }
        .jw-controlbar .jw-button-container .custom-seek-btn {
            margin: 0 8px;
            padding: 8px;
            transition: all 0.2s ease;
            border-radius: 50%;
        }
        .jw-controlbar .jw-button-container .custom-seek-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }
        .jw-controlbar .jw-button-container .custom-seek-btn img,
        .jw-controlbar .jw-button-container .custom-seek-btn svg {
            width: 24px;
            height: 24px;
            fill: #fff;
            display: block;
        }
        .jw-controlbar .jw-icon svg,
        .jw-controlbar .jw-icon use,
        .jw-controlbar .jw-icon path {
            fill: #fff !important;
            stroke: #fff !important;
        }
        .jw-icon-volume {
            display: none !important;
        }
        .jw-icon-cast {
            order: 3;
        }
        .jw-title {
            padding: 20px !important;
        }
        .jw-title-primary {
            color: #fff !important;
            font-size: 18px !important;
            font-weight: bold !important;
        }
        .jw-title-secondary {
            color: #fff !important;
            font-size: 14px !important;
        }
        .jw-button-container {
            display: flex !important;
            align-items: center !important;
            flex-direction: row !important;
        }
        .jw-icon-playback {
            order: 1;
        }
        .jw-text-elapsed {
            order: 2;
        }
        .jw-text-duration {
            order: 3;
        }
        .jw-icon-rewind {
            order: 4;
        }
        .jw-icon-forward {
            order: 5;
        }
        .jw-icon-cast {
            order: 6;
        }
        .jw-icon-cc {
            order: 7;
        }
        .jw-icon-settings {
            order: 8;
        }
        .jw-icon-fullscreen {
            order: 9;
        }
        .jw-svg-icon-fullscreen-on,
        .jw-svg-icon-fullscreen-off {
            display: none !important;
        }
        .jw-icon-fullscreen {
            background-image: url('https://img.icons8.com/?size=100&id=XYNsk0jtpmKn&format=png&color=FFFFFF') !important;
            background-size: 24px !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            width: 32px !important;
            height: 32px !important;
            opacity: 0.9;
        }
        .jw-icon-fullscreen:hover {
            opacity: 1;
        }
        .jw-cast .jw-menu {
            background: rgba(0,0,0,0.9);
            border: 1px solid #444;
            padding: 15px;
        }
        .jw-cast-label {
            font-size: 16px !important;
            margin-bottom: 10px !important;
            color: #fff !important;
            text-align: center !important;
        }
        .jw-cast .jw-option {
            color: #fff;
            padding: 12px;
        }
        .jw-cast .jw-option:hover {
            background: rgba(255,255,255,0.1);
        }
    </style>
    <script src="https://ssl.p.jwpcdn.com/player/v/8.22.0/jwplayer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jwplayer-chromecast@1/dist/chromecast.min.js"></script>
</head>
<body>
    <div id="loading-screen">
        <div class="loading-circle"></div>
    </div>

    <div id="player-container">
        <div id="player"></div>
    </div>
    <script>
        jwplayer.key = "cLGMn8T20tGvW+0eXPhq4NNmLB57TrscPjd1IyJF84o=";

        // Force unmute before player setup
        localStorage.setItem('jwplayer.mute', 'false');

        const jwp = jwplayer('player').setup({
            file: "$videoUrl",
            type: "hls",
            width: "100%",
            height: "100%",
            autostart: true,
            preload: "auto",
            mute: false,
            startmute: false,
            cast: {
                enable: true,
                css: true,
                customStyle: {
                    buttonColor: '#FFF',
                    menuBackground: 'rgba(0,0,0,0.9)',
                    menuColor: '#FFF'
                }
            },
            tracks: [{
                file: "$subtitleUrl",
                kind: "subtitles",
                label: "English",
                default: true
            }],
            skin: {
                name: "glow"
            },
            display: {
                displayDuration: false
            },
            title: `Episode ${episodeId}`,
            description: 'Made by 💕 Moinul Islam',
            displaytitle: true,
            displaydescription: true
        });

        const loadingScreen = document.getElementById('loading-screen');

        // Store the current volume
        let currentVolume = 50; // Default volume (can be adjusted)

        jwp.on('ready', function() {
            console.log('Player is ready');
            jwp.setMute(false);  // Force unmute on ready
            jwp.setVolume(currentVolume);  // Set volume to the stored value
            if ($skipSeconds > 0) {
                jwp.seek($skipSeconds);
            }
        });

        jwp.on('play', function() {
            console.log('Video started playing');
            loadingScreen.classList.add('hidden');
            jwp.setMute(false);  // Force unmute on play
            jwp.setVolume(currentVolume);  // Ensure volume is set to the stored value
        });

        jwp.on('buffer', function() {
            console.log('Buffering...');
            loadingScreen.classList.remove('hidden');
        });

        jwp.on('bufferChange', function() {
            console.log('Buffering complete');
            loadingScreen.classList.add('hidden');
        });

        jwp.on('seek', function() {
            console.log('Seeking...');
            jwp.setVolume(currentVolume);  // Ensure volume doesn't reset after seeking
        });

        jwp.on('volume', function(event) {
            console.log('Volume changed:', event.volume);
            currentVolume = event.volume;  // Update the stored volume
        });

        jwp.on('castConnected', function(event) {
            console.log('Connected to:', event.deviceName);
            jwp.setVolume(currentVolume);  // Set volume to the stored value when casting starts
        });

        jwp.on('castDisconnected', function() {
            console.log('Disconnected from Chromecast');
            jwp.setVolume(currentVolume);  // Set volume to the stored value when casting ends
        });

        // Add rewind and forward buttons
        jwp.on('ready', function() {
            jwp.addButton(
                'https://i.ibb.co.com/7NgZ9k4j/20250221-005254.png',
                'Rewind 10 seconds',
                function() {
                    const currentTime = jwp.getPosition();
                    jwp.seek(currentTime - 10);
                },
                'rewind',
                {
                    className: 'custom-seek-btn'
                }
            );

            jwp.addButton(
                'https://i.ibb.co.com/bgX4rJyz/20250221-004959.png',
                'Forward 10 seconds',
                function() {
                    const currentTime = jwp.getPosition();
                    jwp.seek(currentTime + 10);
                },
                'forward',
                {
                    className: 'custom-seek-btn'
                }
            );
        });

        document.addEventListener('keydown', (e) => {
            if (e.code === 'ArrowRight') {
                const currentTime = jwp.getPosition();
                jwp.seek(currentTime + 10);
            } else if (e.code === 'ArrowLeft') {
                const currentTime = jwp.getPosition();
                jwp.seek(currentTime - 10);
            }
        });
    </script>
</body>
</html>
HTML;
        } else {
            echo "<div style='color: red;'>API Error: Invalid response or episode not found.</div>";
        }
    }
} else {
    echo "<div style='color: red;'>Error: Episode ID is missing.</div>";
}
?>
