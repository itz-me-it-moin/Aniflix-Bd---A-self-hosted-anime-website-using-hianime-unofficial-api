
AniFlixbd

A self hosted anime streaming platform utilizing the HiAnime Unofficial API for seamless episode playback, subtitles, and advanced streaming features.

Features

✅ Fetch anime episode data dynamically using HiAnime API
✅ HLS.js for adaptive video streaming
✅ Subtitle support with real-time updates
✅ Intro & Outro Skip for a smoother experience
✅ Minimalist & Responsive UI optimized for all devices
✅ Dark Mode for comfortable viewing

Getting Started

Prerequisites

PHP (>=7.4)

Web server (Apache/Nginx recommended)

HiAnime API access


Installation

1. Clone this repository:

git clone https://github.com/your-username/anime-streamer.git


2. Navigate to the project directory:

cd anime-streamer


3. Configure the API:

Open config.php

Set your HiAnime API base URL:

define('API_BASE_URL', 'https://hianime-api.com');



4. Move the project to your web server directory:

# For Apache:
mv anime-streamer /var/www/html/

# For Nginx:
mv anime-streamer /usr/share/nginx/html/


5. Start the local server and access the platform:

http://localhost/anime-streamer



Usage

1. Open the website in your browser.


2. Use the query parameters to fetch episodes:

http://localhost/anime-streamer/?episodeId=123&category=sub

episodeId: Required, ID of the episode

category: Optional, sub (default) or dub



3. Enjoy streaming!



Customization

Modify overlay text in index.html (#overlay-text).

Change styles in styles.css.

Adjust skip time in player.js.


Contributing

Pull requests are welcome! Fork the repo, make changes, and submit a PR.

License

This project is licensed under the MIT License.
