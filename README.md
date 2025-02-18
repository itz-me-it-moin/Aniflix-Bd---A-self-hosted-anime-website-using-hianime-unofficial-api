# 🎥 AniflixBD - Ultimate Anime Streaming Platform  

AniflixBD is a **locally hosted** anime streaming platform that integrates with the **HiAnime Unofficial API**, providing a high-quality and feature-rich anime watching experience.  

## 🚀 Features  

✅ **Dynamic Anime Episode Fetching** - Automatically retrieves the latest anime episodes from the API.  
✅ **High-Quality Adaptive Streaming** - Uses HLS.js for smooth playback at different resolutions.  
✅ **Subtitle Support** - Auto-sync subtitles in multiple languages.  
✅ **Intro & Outro Skip** - Detects and skips intros and outros for seamless viewing.  
✅ **Multi-Server Support** - Choose between different servers for better performance.  
✅ **Responsive & Dark Mode UI** - Optimized for all devices with a stylish dark theme.  
✅ **History & Watch Later** - Save progress and continue watching anytime.  
✅ **Download Episodes** - Download videos for offline viewing.  
✅ **Customizable Player** - Modify playback settings such as speed, quality, and audio tracks.  
✅ **Secure & Fast** - Optimized for speed, caching, and low latency.  

---

## 📜 Table of Contents  

- [Features](#-features)  
- [Installation](#-installation)  
- [Usage](#-usage)  
- [API Endpoints](#-api-endpoints)  
- [Customization](#-customization)  
- [Contributing](#-contributing)  
- [License](#-license)  

---

## 🔧 Installation  

### 📌 Prerequisites  
Ensure you have the following installed:  
- **PHP (>=7.4)**  
- **Apache or Nginx Web Server**  
- **HiAnime API Access**  

### 💻 Steps to Set Up Locally  

1️⃣ **Clone the Repository**  
```bash
git clone https://github.com/your-username/aniflixbd.git
```

2️⃣ **Navigate to the Project Directory**  
```bash
cd aniflixbd
```

3️⃣ **Configure the API**  
Open `config.php` and replace the `API_BASE_URL` with the HiAnime API:  
```php
define('API_BASE_URL', 'https://hianime-api.com');
```

4️⃣ **Move the Project to Your Server**  
```bash
# Apache
mv aniflixbd /var/www/html/

# Nginx
mv aniflixbd /usr/share/nginx/html/
```

5️⃣ **Start Your Local Server**  
```bash
php -S localhost:8000
```
Now, open your browser and visit:  
```
http://localhost:8000
```

---

## 🎬 Usage  

1️⃣ **Access the Homepage**  
Simply open your browser and go to:  
```
http://localhost:8000
```

2️⃣ **Watch an Episode**  
Pass query parameters to fetch and play episodes:  
```
http://localhost:8000/?episodeId=123&category=sub
```
- `episodeId` → (Required) Episode ID.  
- `category` → (Optional) `sub` (default) or `dub`.  

3️⃣ **Download an Episode**  
Click on the **Download** button or use:  
```
http://localhost:8000/download.php?episodeId=123
```

---

## 📡 API Endpoints  

### 🎥 Fetch Anime Details  
**Request:**  
```http
GET /anime/{animeId}
```
**Response:**  
```json
{
  "id": "naruto",
  "title": "Naruto",
  "episodes": 500,
  "status": "Completed",
  "image": "https://cdn.aniflixbd.com/images/naruto.jpg"
}
```

### 🎞 Fetch Episode Stream  
**Request:**  
```http
GET /episode/{episodeId}
```
**Response:**  
```json
{
  "episodeId": "naruto-220",
  "videoUrl": "https://cdn.aniflixbd.com/stream/naruto-220.mp4",
  "subtitles": [
    {
      "lang": "English",
      "url": "https://cdn.aniflixbd.com/subs/naruto-220-en.vtt"
    },
    {
      "lang": "Japanese",
      "url": "https://cdn.aniflixbd.com/subs/naruto-220-jp.vtt"
    }
  ],
  "introSkip": {
    "start": "00:00:10",
    "end": "00:01:30"
  },
  "outroSkip": {
    "start": "00:22:00",
    "end": "00:23:30"
  }
}
```

### 📥 Download Episode  
**Request:**  
```http
GET /download/{episodeId}
```
**Response:**  
```json
{
  "status": "success",
  "message": "Download started",
  "downloadUrl": "https://cdn.aniflixbd.com/downloads/naruto-220.mp4"
}
```

---

## 🎨 Customization  

- **Modify UI Colors & Theme**  
  - Edit `styles.css` for color changes.  

- **Change the Logo & Branding**  
  - Replace `logo.png` in the `assets` folder.  

- **Modify Player Settings**  
  - Adjust settings in `player.js`  

---

## 🤝 Contributing  

We welcome contributions! To contribute:  

1️⃣ **Fork the repository**  
2️⃣ **Create a feature branch** (`git checkout -b feature-name`)  
3️⃣ **Commit your changes** (`git commit -m "Added feature"`)  
4️⃣ **Push to the branch** (`git push origin feature-name`)  
5️⃣ **Create a Pull Request**  

---

## 📜 License  

This project is licensed under the [MIT License](LICENSE).  
