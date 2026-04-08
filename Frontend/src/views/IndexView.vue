<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { RouterLink, useRouter } from 'vue-router';
import { onMounted } from 'vue';

const router = useRouter();
const artistaFiltro = ref('');
const Seleccionarcancion = ref(false)
const Seleccionarartista = ref(false)
const Seleccionaralbum = ref(false)
const cancionClasico = ref(false)
const cancionFiltrado = ref(false)
const albumClasico = ref(false)
const albumFiltrado = ref(false)
const artistas = ref([])

// --- NUEVAS VARIABLES PARA EL DESPLEGABLE CUSTOM ---
const dropdownAbierto = ref(false);
const artistaSeleccionadoNombre = ref('');

const seleccionarArtista = (id, nombre) => {
  artistaFiltro.value = id;
  artistaSeleccionadoNombre.value = nombre;
  dropdownAbierto.value = false; // Cierra el menú al elegir
}
// ---------------------------------------------------

const opcionesArtistas = async  () => {
 const cargar = await axios.get('http://localhost:8080/api/artists');
  artistas.value = cargar.data;
  console.log(artistas.value)
}

const JugarCancionFiltrada = () => {
  router.push({
    name:'cancion',
    params: {id:artistaFiltro.value}
  })
}

const JugarAlbumFiltrado = () => {
  router.push({
    name:'album',
    params:{id: artistaFiltro.value}
  })
}

onMounted(() => {
  opcionesArtistas()
})
</script>

<template>
  <div class="app-container">
    <header class="header">
      <div class="header-left">
        <div class="icon-group">
          <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
          </svg>
          <span class="icon-text">DIARIO</span>
        </div>
        <div class="icon-group">
          <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10"></line>
            <line x1="12" y1="20" x2="12" y2="4"></line>
            <line x1="6" y1="20" x2="6" y2="14"></line>
          </svg>
          <span class="icon-text">ESTADÍSTICAS</span>
        </div>
      </div>
      
      <div class="header-center">
        <h1 class="logo">SongDle</h1>
        <p class="subtitle">CONFIGURACIÓN DEL JUEGO</p>
      </div>
      
      <div class="header-right">
        <div class="avatar-circle">
          <span class="avatar-icon">👤</span>
        </div>
      </div>
    </header>

    <main class="main-content">
      <div class="config-card">
        
        <nav class="tabs-container">
          <button 
            @click="Seleccionarcancion = true, Seleccionaralbum = false, Seleccionarartista = false" 
            class="tab-btn" 
            :class="{ active: Seleccionarcancion }"
          >
            Canción
          </button>
          <button 
            @click="Seleccionarcancion = false, Seleccionaralbum = false, Seleccionarartista = true" 
            class="tab-btn" 
            :class="{ active: Seleccionarartista }"
          >
            Artista
          </button>
          <button 
            @click="Seleccionaralbum = true, Seleccionarartista = false, Seleccionarcancion = false" 
            class="tab-btn" 
            :class="{ active: Seleccionaralbum }"
          >
            Álbum
          </button>
        </nav>

        <div class="card-content">
          
          <div v-if="!Seleccionarcancion && !Seleccionarartista && !Seleccionaralbum" class="step-1-neutral">
            <p class="instruction-text">Paso 1: Elige tu categoría para empezar.</p>
          </div>

          <div v-if="Seleccionarcancion" class="step-animation">
            <p class="step-title">Paso 2: Elige el Modo de Juego</p>
            
            <div class="modes-layout">
              <div class="column-left">
                <button 
                  @click="cancionClasico = true, cancionFiltrado = false" 
                  class="mode-btn-outline"
                  :class="{ selected: cancionClasico }"
                >
                  JUEGO CLÁSICO
                </button>
                <button 
                  @click="cancionClasico = false, cancionFiltrado = true" 
                  class="mode-btn-outline"
                  :class="{ selected: cancionFiltrado }"
                >
                  MODO ARTISTA
                </button>
              </div>

              <div class="column-right">
                <div v-if="cancionFiltrado" class="dropdown-wrapper">
                  <div class="custom-select-dark" @click="dropdownAbierto = !dropdownAbierto" :class="{ 'is-open': dropdownAbierto }">
                    <span>{{ artistaSeleccionadoNombre || 'Filtrar por artista (opcional)' }}</span>
                  </div>
                  
                  <ul v-if="dropdownAbierto" class="custom-options-list">
                    <li @click="seleccionarArtista('', '')" class="default-option">Filtrar por artista (opcional)</li>
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
                <button v-if="cancionClasico || cancionFiltrado" @click="JugarCancionFiltrada()" class="play-btn-gradient">¡A JUGAR CANCIÓN!</button>
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
            
            <div class="modes-layout">
              <div class="column-left">
                <button 
                  @click="albumClasico = true, albumFiltrado = false" 
                  class="mode-btn-outline"
                  :class="{ selected: albumClasico }"
                >
                  JUEGO CLÁSICO
                </button>
                <button 
                  @click="albumClasico = false, albumFiltrado = true" 
                  class="mode-btn-outline"
                  :class="{ selected: albumFiltrado }"
                >
                  MODO ARTISTA
                </button>
              </div>

              <div class="column-right">
                <div v-if="albumFiltrado" class="dropdown-wrapper">
                  <div class="custom-select-dark" @click="dropdownAbierto = !dropdownAbierto" :class="{ 'is-open': dropdownAbierto }">
                    <span>{{ artistaSeleccionadoNombre || 'Filtrar por artista (opcional)' }}</span>
                  </div>
                  
                  <ul v-if="dropdownAbierto" class="custom-options-list">
                    <li @click="seleccionarArtista('', '')" class="default-option">Filtrar por artista (opcional)</li>
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
                <button v-if="albumClasico || albumFiltrado" @click="JugarAlbumFiltrado()" class="play-btn-gradient">¡A JUGAR ÁLBUM!</button>
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

/* --- Fondo Base --- */
.app-container {
  font-family: 'Montserrat', sans-serif;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  background: radial-gradient(circle at top, #141824 0%, #0a0c13 100%);
  color: #f1f5f9;
}

/* --- Encabezado --- */
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

/* NUEVOS ESTILOS PARA LOS ICONOS (Reemplazo de los emojis) */
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
  background-color: rgba(255, 255, 255, 0.05); /* Efecto pastilla al pasar el ratón */
}

.nav-icon {
  width: 18px;
  height: 18px;
  opacity: 0.8;
  transition: all 0.3s ease;
}

.icon-group:hover .nav-icon {
  opacity: 1;
  color: #d8b4fe; /* Toque morado sutil en el icono al hacer hover */
}

.header-center { flex: 2; text-align: center; }

/* --- Logo --- */
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

.header-right { display: flex; justify-content: flex-end; }

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
}
.avatar-circle:hover { border-color: #8b5cf6; }

/* --- Main --- */
.main-content {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;
}

/* --- Tarjeta de Configuración --- */
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

/* --- Pestañas --- */
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

/* Indicador de Pestaña Activa */
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

/* --- Contenido de la Tarjeta --- */
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

/* --- Layout de Modos --- */
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

/* --- Botones de Modo (Outline) --- */
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

/* Modo Seleccionado */
.mode-btn-outline.selected {
  border-color: #a855f7;
  color: #ffffff;
  background-color: rgba(168, 85, 247, 0.1);
  box-shadow: inset 0 0 15px rgba(168, 85, 247, 0.15);
}

/* ====================================================
   NUEVO DESPLEGABLE CUSTOMIZADO E IMPRESIONANTE
   ==================================================== */
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

/* Flechita animada que dibujamos con CSS */
.custom-select-dark::after {
  content: '▼';
  font-size: 0.8rem;
  color: #94a3b8;
  transition: transform 0.3s ease;
}

.custom-select-dark.is-open {
  border-color: #ec4899; /* Rosa neón */
  box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.15);
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}

.custom-select-dark.is-open::after {
  transform: rotate(180deg);
}

/* La lista mágica que reemplaza a tu caja blanca */
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

.custom-options-list li:last-child {
  border-bottom: none;
}

.custom-options-list li:hover {
  background-color: #1a1f2e;
  color: #ffffff;
}

.custom-options-list li.selected-item {
  background-color: rgba(168, 85, 247, 0.15);
  color: #d8b4fe;
  font-weight: 600;
}

.default-option {
  font-style: italic;
  color: #64748b !important;
}

/* ¡SCROLLBAR PERSONALIZADA! */
.custom-options-list::-webkit-scrollbar {
  width: 8px;
}
.custom-options-list::-webkit-scrollbar-track {
  background: #0b0d14; 
}
.custom-options-list::-webkit-scrollbar-thumb {
  background: #334155; 
  border-radius: 4px;
}
.custom-options-list::-webkit-scrollbar-thumb:hover {
  background: #ec4899; 
}
/* ==================================================== */

/* --- Botón Jugar --- */
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
  position: relative;
  overflow: hidden;
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

/* Animación de entrada suave */
.step-animation {
  animation: fadeUp 0.4s ease-out;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>