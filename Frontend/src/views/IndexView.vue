<template>
  <div class="app-container">
    <div class="top-bar">
      <h1 class="game-title">SongDle</h1>
      <button class="profile-btn">
        <img src="../img/user_icon.png" alt="Usuario">
      </button>
    </div>

    <div class="menu-area">
      <router-link to="/cancion"><div class="menu-card">
        <p>Canción</p>
      </div> </router-link>

      <router-link to="/Artista"><div class="menu-card">
        <p>Artista</p>
      </div></router-link>

      <router-link to="/album"><div class="menu-card">
        <p>Álbum</p>
      </div></router-link>
    </div>
  </div>
</template>

<style scoped>
* {
  box-sizing: border-box;
  text-decoration: none;
  color:#fff
}

.app-container {
  background: linear-gradient(135deg, #121212 0%, #1e2530 100%);
  color: #fff;
  min-height: 100vh;
  width: 100%;
  font-family: 'Segoe UI', sans-serif;
  display: flex;
  flex-direction: column;
  position: absolute;
  top: 0;
  left: 0;
}

.top-bar {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  padding: 30px 20px;
  background: transparent;
}

.game-title {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0;
  text-shadow: 0 4px 10px rgba(0,0,0,0.5);
}

.profile-btn {
  position: absolute;
  right: 20px;
  background: none;
  border: none;
  cursor: pointer;
  transition: transform 0.2s;
}

.profile-btn:hover {
  transform: scale(1.1);
}

.profile-btn img {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  border: 2px solid rgba(255,255,255,0.3);
}

.menu-area {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 25px;
  padding-bottom: 50px;
}

a {
  background-color: #2c3e50;
  width: 90%;
  max-width: 400px;
  padding: 25px;
  border-radius: 15px;
  text-align: center;
  cursor: pointer;
  border: 1px solid rgba(255,255,255,0.05);
  box-shadow: 0 4px 6px rgba(0,0,0,0.2);
  transition: all 0.3s ease;
}

a:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.4);
}

.menu-card p {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 500;
  letter-spacing: 1px;
}
</style>