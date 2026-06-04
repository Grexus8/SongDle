<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { RouterLink, useRouter } from 'vue-router';

const router = useRouter();
const artistaFiltro = ref('');
const Seleccionarcancion = ref(false)
const Seleccionarartista = ref(false)
const Dificil = ref(false)
const Seleccionaralbum = ref(false)
const SeleccionarArcade = ref(false)
const cancionClasico = ref(false)
const cancionFiltrado = ref(false)
const artistas = ref([])

const dropdownAbierto = ref(false);
const artistaSeleccionadoNombre = ref('');

// SACAMOS AL USUARIO DIRECTAMENTE DE FORMA SEGURA
const userStorage = localStorage.getItem('user');
const usuario = ref(userStorage ? JSON.parse(userStorage) : null);
const userId = ref(usuario.value ? usuario.value.id_usuario : '');

// COMPROBACIÓN ROBUSTA DE ADMINISTRADOR
const esAdmin = computed(() => {
    if (!usuario.value) return false;
    const admin = usuario.value.administrador;
    return admin === 1 || admin === '1' || admin === true;
});

const seleccionarArtista = (id, nombre) => {
  artistaFiltro.value = id;
  artistaSeleccionadoNombre.value = nombre;
  dropdownAbierto.value = false;
}

const opcionesArtistas = async () => {
  try {
    const cargar = await axios.get('http://localhost:8080/api/artists');
    artistas.value = cargar.data;
  } catch (error) {
    console.error("Error al cargar los artistas:", error);
  }
}

const JugarCancionFiltrada = () => {
  router.push({
    name: 'cancion',
    params: { id: artistaFiltro.value }
  })
}

const JugarCancionDificil = () => {
  router.push({
      name: 'cancion',
      query: { Dificil: Dificil.value }
  })
}

// FUNCION DE LOGOUT
const cerrarSesion = async () => {
    try {
        const token = localStorage.getItem('token');
        if (token) {
            await axios.post('http://localhost:8080/api/auth/logout', {}, {
                headers: { Authorization: `Bearer ${token}` }
            });
        }
    } catch (error) {
        console.error("Error cerrando sesión en el servidor:", error);
    } finally {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        router.push('/login');
    }
}

onMounted(() => {
  opcionesArtistas();
})
</script>

<template>
  <div class="app-container">
    <header class="header">
      <div class="header-left">
        
        <div class="icon-group group-stats">
          <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon">
            <line x1="18" y1="20" x2="18" y2="10"></line>
            <line x1="12" y1="20" x2="12" y2="4"></line>
            <line x1="6" y1="20" x2="6" y2="14"></line>
          </svg>
          <RouterLink v-if="userId" :to="{ name: 'estadisticas', params: { id: userId } }">
              <span class="icon-text">ESTADÍSTICAS</span>
          </RouterLink>
        </div>

        <div class="icon-group group-stats">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="nav-icon">
                <path d="M8 21h8"></path>
                <path d="M12 17v4"></path>
                <path d="M7 4h10"></path>
                <path d="M17 4v8a5 5 0 0 1-10 0V4"></path>
                <path d="M7 9H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2"></path>
                <path d="M17 9h2a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-2"></path>
            </svg>
            <RouterLink :to="{ name: 'ranking'}">
                <span class="icon-text">RANKING</span>
            </RouterLink>
        </div>
      </div>
      
      <div class="header-center">
        <h1 class="logo">SongDle</h1>
        <p class="subtitle">CONFIGURACIÓN DEL JUEGO</p>
      </div>
      
      <div class="header-right">
        
        <RouterLink v-if="esAdmin" to="/admin" class="btn-action-small" title="Panel de Administración">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
        </RouterLink>

        <div class="avatar-circle">
          <RouterLink v-if="userId" :to="{ name: 'perfil', params: { id: userId } }" style="display: block; width: 100%; height: 100%;">
                <img 
                    v-if="usuario && usuario.profile_img" 
                    :src="`http://localhost:8080/storage/${usuario.profile_img}`" 
                    alt="Foto de perfil" 
                    class="avatar-img"
                >
                <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="avatar-img default-avatar">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
          </RouterLink>
          <div v-else style="display: block; width: 100%; height: 100%;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="avatar-img default-avatar">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
          </div>
        </div>

        <button @click="cerrarSesion" class="btn-action-small btn-logout" title="Cerrar sesión">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        </button>

      </div>
    </header>

    <main class="main-content">
      <div class="config-card">
        
        <nav class="tabs-container">
          <button 
            @click="Seleccionarcancion = true; Seleccionaralbum = false; Seleccionarartista = false; SeleccionarArcade = false" 
            class="tab-btn" 
            :class="{ active: Seleccionarcancion }"
          >
            Canción
          </button>
          <button 
            @click="Seleccionarcancion = false; Seleccionaralbum = false; Seleccionarartista = true; SeleccionarArcade = false" 
            class="tab-btn" 
            :class="{ active: Seleccionarartista }"
          >
            Artista
          </button>
          <button 
            @click="Seleccionaralbum = true; Seleccionarartista = false; Seleccionarcancion = false; SeleccionarArcade = false" 
            class="tab-btn" 
            :class="{ active: Seleccionaralbum }"
          >
            Álbum
          </button>
          <button 
            @click="Seleccionaralbum = false; Seleccionarartista = false; Seleccionarcancion = false; SeleccionarArcade = true" 
            class="tab-btn" 
            :class="{ active: SeleccionarArcade }"
          >
            Arcade
          </button>
        </nav>

        <div class="card-content">
          
          <div v-if="!Seleccionarcancion && !Seleccionarartista && !Seleccionaralbum && !SeleccionarArcade" class="step-1-neutral">
            <p class="instruction-text">Paso 1: Elige tu categoría para empezar.</p>
          </div>

          <div v-if="Seleccionarcancion" class="step-animation">
            <p class="step-title">Paso 2: Elige el Modo de Juego</p>
            
            <div class="modes-layout">
              <div class="column-left">
                <button 
                  @click="cancionClasico = true; cancionFiltrado = false; Dificil = false" 
                  class="mode-btn-outline"
                  :class="{ selected: cancionClasico }"
                >JUEGO CLÁSICO
                </button>
                  <div v-if="cancionClasico" class="dificultad-wrapper fade-in">
                    <label class="toggle-switch">
                      <input type="checkbox" v-model="Dificil">
                      <span class="slider"></span>
                    </label>
                    <span class="dificultad-texto">Activar Modo Difícil</span>
                  </div>  
                <button 
                  @click="cancionClasico = false; cancionFiltrado = true"
                  class="mode-btn-outline"
                  :class="{ selected: cancionFiltrado }"
                >MODO ARTISTA
                </button>
              </div>

              <div class="column-right">
                <div v-if="cancionFiltrado" class="dropdown-wrapper">
                  <div class="custom-select-dark" @click="dropdownAbierto = !dropdownAbierto" :class="{ 'is-open': dropdownAbierto }">
                    <span>{{ artistaSeleccionadoNombre || 'Filtrar por artista' }}</span>
                  </div>
                  
                  <ul v-if="dropdownAbierto" class="custom-options-list">
                    <li 
                      v-for="artista in artistas" 
                      :key="artista.id_artista" 
                      @click="seleccionarArtista(artista.id_artista, artista.nombre)"
                      :class="{ 'selected-item': artistaFiltro === artista.id_artista }"
                    >
                      {{ artista.nombre }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="play-wrapper">
                <button v-if="cancionClasico || cancionFiltrado" @click="cancionClasico ? JugarCancionDificil() : JugarCancionFiltrada()" class="play-btn-gradient">¡A JUGAR CANCIÓN!</button>
            </div>
          </div>
          
          <div v-if="Seleccionarartista" class="step-animation">
            <p class="step-title">Paso 2: Elige el Modo de Juego</p>
            <div class="single-mode-wrapper">
              <RouterLink to="artista" class="mode-link"><button class="play-btn-gradient">JUGAR MODO ARTISTA</button></RouterLink>
            </div>
          </div>

          <div v-if="Seleccionaralbum" class="step-animation">
            <p class="step-title">Paso 2: Elige el Modo de Juego</p>
            <div class="single-mode-wrapper">
              <RouterLink to="album" class="mode-link"><button class="play-btn-gradient">JUGAR MODO ALBUM</button></RouterLink>
            </div>
          </div>

          <div v-if="SeleccionarArcade" class="step-animation">
            <p class="step-title">Paso 2: Elige el Modo de Juego</p>
            <div class="single-mode-wrapper">
              <RouterLink to="Arcade" class="mode-link"><button class="play-btn-gradient">JUGAR ARCADE</button></RouterLink>
            </div>
          </div>

        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Montserrat:wght@400;500;600;700&display=swap');

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

.app-container {
  font-family: 'Montserrat', sans-serif;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  background: radial-gradient(circle at top, #141824 0%, #0a0c13 100%);
  color: #f1f5f9;
}

.main-content {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem 3rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  background-color: rgba(10, 12, 19, 0.8);
  backdrop-filter: blur(10px);
}

.header-left, .header-right { flex: 1; }
.header-left { display: flex; gap: 1rem; }
.header-center { flex: 2; text-align: center; }

/* ESTILOS HEADER RIGHT */
.header-right { 
  display: flex; 
  justify-content: flex-end; 
  align-items: center;
  gap: 15px;
}

.btn-action-small {
  background-color: #11141d;
  border: 1px solid #334155;
  border-radius: 50%;
  width: 42px;
  height: 42px;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #94a3b8;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
}

.btn-action-small:hover {
  color: #d8b4fe;
  border-color: #d8b4fe;
  background-color: rgba(216, 180, 254, 0.1);
}

.btn-logout:hover {
  color: #f87171;
  border-color: #f87171;
  background-color: rgba(248, 113, 113, 0.1);
}

.icon-group {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  font-size: 0.85rem;
  font-weight: 600;
  color: #94a3b8;
  cursor: pointer;
  padding: 0.6rem 1rem;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.icon-group:hover { 
  color: #ffffff; 
  background-color: rgba(255, 255, 255, 0.05); 
}

.nav-icon {
  width: 18px;
  height: 18px;
  fill: none;
  stroke: currentColor;
  stroke-width: 2px;
  stroke-linecap: round;
  stroke-linejoin: round;
  opacity: 0.8;
  transition: all 0.3s ease;
}

.group-ranking:hover .nav-icon { color: #fbbf24; opacity: 1; }
.group-stats:hover .nav-icon { color: #d8b4fe; opacity: 1; }

.logo {
  font-family: 'Dela Gothic One', cursive;
  font-size: 2.8rem;
  margin: 0;
  line-height: 1;
  background: linear-gradient(to right, #ffffff, #d8b4fe);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  text-shadow: 0 4px 20px rgba(171, 71, 188, 0.3);
}

.subtitle {
  font-size: 0.75rem;
  letter-spacing: 3px;
  margin-top: 0.5rem;
  color: #64748b;
  font-weight: 600;
}

.avatar-circle {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  border: 2px solid #334155;
  background-color: #1e293b;
  cursor: pointer;
  transition: border-color 0.2s;
  overflow: hidden; 
}

.avatar-circle:hover { border-color: #8b5cf6; }

.avatar-img {
  width: 100%;
  height: 100%;
  object-fit: cover; 
  display: block;
}

/* ESTILO DEL AVATAR POR DEFECTO (SVG) */
.default-avatar {
  fill: #94a3b8; 
  background-color: #1e293b;
  transform: scale(1.1);
}

.config-card {
  width: 100%;
  max-width: 800px; 
  border-radius: 16px;
  border: 1px solid rgba(255, 255, 255, 0.08);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  background-color: #11141d;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
}

.card-content {
  padding: 4rem 3rem;
  min-height: 380px; 
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.instruction-text {
  font-size: 1.2rem;
  text-align: center;
  font-weight: 500;
  color: #94a3b8;
}

.step-title {
  font-size: 1.2rem;
  color: #f8fafc;
  font-weight: 600;
  margin-bottom: 2.5rem;
  text-align: center;
  letter-spacing: 1px;
}

.tabs-container {
  display: flex;
  width: 100%;
  background-color: #0b0d14; 
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.tab-btn {
  flex: 1;
  border: none;
  font-family: inherit;
  font-size: 1.05rem;
  font-weight: 600;
  padding: 1.4rem;
  cursor: pointer;
  background-color: transparent;
  color: #64748b; 
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
}

.tab-btn:not(:last-child) {
  border-right: 1px solid rgba(255, 255, 255, 0.03);
}

.tab-btn:hover { color: #f1f5f9; background-color: rgba(255, 255, 255, 0.02); }

.tab-btn.active {
  color: #ffffff;
  background-color: #151923;
}

.tab-btn.active::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 3px;
  background: linear-gradient(90deg, #8b5cf6, #ec4899); 
  box-shadow: 0 -2px 10px rgba(236, 72, 153, 0.4);
}

.modes-layout {
  display: flex;
  gap: 2rem;
  margin-bottom: 1rem;
  width: 100%;
  align-items: flex-start;
}

.column-left, .column-right {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

.mode-btn-outline {
  background-color: rgba(255, 255, 255, 0.02);
  border: 1px solid #334155;
  color: #cbd5e1;
  font-family: inherit;
  font-size: 1rem;
  font-weight: 600;
  padding: 1.2rem;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.2s ease;
  text-align: center;
  letter-spacing: 1px;
}

.mode-btn-outline:hover {
  border-color: #64748b;
  background-color: rgba(255, 255, 255, 0.05);
}

.mode-btn-outline.selected {
  border-color: #a855f7;
  color: #ffffff;
  background-color: rgba(168, 85, 247, 0.1);
  box-shadow: inset 0 0 15px rgba(168, 85, 247, 0.15);
}

.play-wrapper, .single-mode-wrapper {
  width: 100%;
  display: flex;
  justify-content: center;
  margin-top: 3rem;
}

.play-btn-gradient {
  background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
  color: #ffffff;
  border: none;
  border-radius: 12px;
  padding: 1.2rem 3rem;
  font-family: inherit;
  font-size: 1.15rem;
  font-weight: 700;
  letter-spacing: 1px;
  cursor: pointer;
  width: 100%;
  max-width: 450px;
  box-shadow: 0 10px 25px -5px rgba(236, 72, 153, 0.4);
  transition: all 0.3s ease;
}

.play-btn-gradient:hover {
  transform: translateY(-3px);
  box-shadow: 0 15px 30px -5px rgba(236, 72, 153, 0.6);
}

.play-btn-gradient:active {
  transform: translateY(1px);
}

.mode-link {
  width: 100%;
  display: flex;
  justify-content: center;
  text-decoration: none;
}

.dropdown-wrapper {
  position: relative;
  width: 100%;
}

.custom-select-dark {
  background-color: #0b0d14; 
  color: #f8fafc;
  border: 1px solid #334155; 
  padding: 1.2rem;
  border-radius: 12px;
  font-family: inherit;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.custom-select-dark::after {
  content: '▼';
  font-size: 0.8rem;
  color: #94a3b8;
  transition: transform 0.3s ease;
}

.custom-select-dark.is-open {
  border-color: #ec4899; 
  box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.15);
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}

.custom-select-dark.is-open::after {
  transform: rotate(180deg);
}

.custom-options-list {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  background-color: #11141d;
  border: 1px solid #ec4899;
  border-top: none;
  border-bottom-left-radius: 12px;
  border-bottom-right-radius: 12px;
  max-height: 220px;
  overflow-y: auto;
  z-index: 100;
  list-style: none;
  box-shadow: 0 10px 25px rgba(0,0,0,0.5);
}

.custom-options-list li {
  padding: 1rem 1.2rem;
  color: #cbd5e1;
  cursor: pointer;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  transition: background-color 0.2s, color 0.2s;
}

.custom-options-list li:last-child { border-bottom: none; }
.custom-options-list li:hover { background-color: #1a1f2e; color: #ffffff; }

.custom-options-list li.selected-item {
  background-color: rgba(168, 85, 247, 0.15);
  color: #d8b4fe;
  font-weight: 600;
}

.default-option {
  font-style: italic;
  color: #64748b !important;
}

.custom-options-list::-webkit-scrollbar { width: 8px; }
.custom-options-list::-webkit-scrollbar-track { background: #0b0d14; }
.custom-options-list::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
.custom-options-list::-webkit-scrollbar-thumb:hover { background: #ec4899; }

.step-animation {
  animation: fadeUp 0.4s ease-out;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.dificultad-wrapper {
  display: flex;
  align-items: center;
  gap: 15px;
  background-color: rgba(139, 92, 246, 0.05); 
  border: 1px solid rgba(139, 92, 246, 0.3);
  padding: 1rem;
  border-radius: 12px;
  margin-top: -5px;
}

.dificultad-texto {
  color: #d8b4fe;
  font-weight: 600;
  font-size: 0.95rem;
}

.toggle-switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 26px;
}

.toggle-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: #334155;
  transition: .4s;
  border-radius: 34px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
  box-shadow: 0 0 10px rgba(236, 72, 153, 0.4);
}

input:checked + .slider:before {
  transform: translateX(24px);
}

.fade-in {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}

.icon-group a {
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
}
</style>