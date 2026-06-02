<script setup>
import { ref, onMounted } from 'vue';
import { RouterLink } from 'vue-router';
import axios from 'axios';

const rankingPuntos = ref([]);
const rankingCanciones = ref([]);
const miRecord = ref(null);

const vistaActiva = ref('puntos');

const userStorage = localStorage.getItem('user');
const userId = userStorage ? JSON.parse(userStorage).id_usuario : null;

onMounted(async () => {
    try {
        if (userId) {
            const record = await axios.get(`http://localhost:8080/api/arcade/record/${userId}`);
            miRecord.value = record.data;
        }
        
        const puntos = await axios.get('http://localhost:8080/api/arcade/ranking-puntos');
        rankingPuntos.value = puntos.data;
        
        const cancion = await axios.get('http://localhost:8080/api/arcade/ranking-canciones');
        rankingCanciones.value = cancion.data;
    } catch (error) {
        console.error("Error cargando los rankings:", error);
    }
});
</script>

<template>
    <div class="app-container">
        
        <header class="header">
            <div class="header-left">
                <RouterLink to="/" class="btn-volver" title="Volver al inicio">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icono-volver">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                </RouterLink>
            </div>
            
            <div class="header-center">
                <h1 class="logo">SongDle</h1>
                <p class="subtitle">RANKING GLOBAL</p>
            </div>
            
            <div class="header-right">
                </div>
        </header>

        <main class="main-content">
            <div class="ranking-card">
                
                <nav class="tabs-container">
                    <button 
                        @click="vistaActiva = 'puntos'" 
                        class="tab-btn" 
                        :class="{ active: vistaActiva === 'puntos' }"
                    >
                        🏆 Mejores Puntos
                    </button>
                    <button 
                        @click="vistaActiva = 'cancion'" 
                        class="tab-btn" 
                        :class="{ active: vistaActiva === 'cancion' }"
                    >
                        🎵 Más Canciones
                    </button>
                </nav>

                <div class="card-content">
                    
                    <div v-if="vistaActiva === 'puntos'" class="fade-in">
                        <ul class="lista-ranking">
                            <li v-for="(punto, index) in rankingPuntos" :key="punto.id_usuario" class="fila-jugador">
                                <span class="posicion">#{{ index + 1 }}</span>
                                <span class="nombre">{{ punto.user?.name || 'Jugador Desconocido' }}</span>
                                <span class="valor">{{ punto.puntos_maximos }} pts</span>
                            </li>
                        </ul>
                    </div>

                    <div v-if="vistaActiva === 'cancion'" class="fade-in">
                        <ul class="lista-ranking">
                            <li v-for="(cancion, index) in rankingCanciones" :key="cancion.id_usuario" class="fila-jugador">
                                <span class="posicion">#{{ index + 1 }}</span>
                                <span class="nombre">{{ cancion.user?.name || 'Jugador Desconocido' }}</span>
                                <span class="valor">{{ cancion.canciones_adivinadas_max }} hits</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <div v-if="miRecord" class="mi-perfil-tarjeta fade-up">
                <h3>Tu Récord Arcade</h3>
                
                <div class="info-perfil-wrapper">
                    <div class="bloque-puesto">
                        <span class="numero-puesto">{{ miRecord.puesto }}</span>
                        <small>Puesto</small>
                    </div>
                    
                    <div class="bloque-datos">
                        <p><strong>Puntuación Máxima:</strong> {{ miRecord.puntos_maximos }} pts</p>
                        <p><strong>Canciones Adivinadas:</strong> {{ miRecord.canciones_adivinadas_max }}</p>
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

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 3rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    background-color: rgba(10, 12, 19, 0.8);
    backdrop-filter: blur(10px);
}

.header-left, 
.header-right { 
    flex: 1; 
}

.header-center { 
    flex: 2; 
    text-align: center; 
}

.btn-volver {
    width: 45px;
    height: 45px;
    background-color: #11141d;
    border: 1px solid #334155;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #94a3b8;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}

.btn-volver:hover {
    color: #ffffff;
    border-color: #ec4899;
    background-color: #1a1e29;
    box-shadow: 0 0 15px rgba(236, 72, 153, 0.4);
    transform: translateX(-3px);
}

.icono-volver { 
    width: 22px; 
    height: 22px; 
    fill: none; 
    stroke: currentColor; 
    stroke-width: 2.5px; 
    stroke-linecap: round; 
    stroke-linejoin: round; 
}

.logo {
    font-family: 'Dela Gothic One', cursive;
    font-size: 2.5rem;
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

.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2rem;
    gap: 2rem;
    overflow-y: auto;
}

.ranking-card {
    width: 100%;
    max-width: 700px;
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    overflow: hidden;
    background-color: #11141d;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
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

.tab-btn:first-child { 
    border-right: 1px solid rgba(255, 255, 255, 0.03); 
}

.tab-btn:hover { 
    color: #f1f5f9; 
    background-color: rgba(255, 255, 255, 0.02); 
}

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

.card-content { 
    padding: 1.5rem; 
    min-height: 400px; 
}

/* ESTILOS DE LA TABLA */
.lista-ranking { 
    list-style: none; 
}

.fila-jugador {
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    transition: background-color 0.2s;
    border-radius: 8px;
}

.fila-jugador:hover { 
    background-color: #1a1f2e; 
}

.fila-jugador:last-child { 
    border-bottom: none; 
}

.posicion { 
    font-family: 'Dela Gothic One', cursive; 
    font-size: 1.3rem; 
    color: #ec4899; 
    width: 60px; 
    text-shadow: 0 0 10px rgba(236, 72, 153, 0.2); 
}

.nombre { 
    flex: 1; 
    font-weight: 600; 
    font-size: 1.1rem; 
    color: #f8fafc; 
}

.valor { 
    font-weight: 700; 
    color: #d8b4fe; 
    background-color: rgba(216, 180, 254, 0.1); 
    border: 1px solid rgba(216, 180, 254, 0.2); 
    padding: 6px 14px; 
    border-radius: 8px; 
    box-shadow: inset 0 0 10px rgba(216, 180, 254, 0.05); 
}

/* TOP 3 COLORES */
.fila-jugador:nth-child(1) .posicion { 
    color: #fbbf24; 
    text-shadow: 0 0 15px rgba(251, 191, 36, 0.4); 
}

.fila-jugador:nth-child(2) .posicion { 
    color: #cbd5e1; 
    text-shadow: 0 0 15px rgba(203, 213, 225, 0.3); 
}

.fila-jugador:nth-child(3) .posicion { 
    color: #b45309; 
    text-shadow: 0 0 15px rgba(180, 83, 9, 0.3); 
}

/* TARJETA MI PERFIL */
.mi-perfil-tarjeta {
    width: 100%;
    max-width: 700px;
    background-color: #11141d;
    border: 1px solid #ec4899;
    border-radius: 12px;
    padding: 1.5rem 2rem;
    box-shadow: 0 10px 25px rgba(236, 72, 153, 0.15);
}

.mi-perfil-tarjeta h3 { 
    margin-bottom: 1.5rem; 
    color: #f8fafc; 
    font-size: 1.2rem; 
    text-transform: uppercase; 
    letter-spacing: 1px; 
    text-align: center; 
    border-bottom: 1px solid #334155; 
    padding-bottom: 1rem; 
}

.info-perfil-wrapper { 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    gap: 3rem; 
}

.bloque-puesto { 
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    background-color: #0b0d14; 
    padding: 1.2rem 2rem; 
    border-radius: 12px; 
    border: 1px solid #334155; 
}

.numero-puesto { 
    font-family: 'Dela Gothic One', cursive; 
    font-size: 2.5rem; 
    color: #ec4899; 
    text-shadow: 0 0 15px rgba(236, 72, 153, 0.3); 
}

.bloque-puesto small { 
    color: #94a3b8; 
    text-transform: uppercase; 
    font-size: 0.8rem; 
    font-weight: 600; 
    margin-top: 5px; 
    letter-spacing: 1px; 
}

.bloque-datos p { 
    margin: 10px 0; 
    color: #cbd5e1; 
    font-size: 1.1rem; 
}

.bloque-datos strong { 
    color: #f8fafc; 
}

/* ANIMACIONES */
.fade-in { 
    animation: fadeIn 0.4s ease-in-out; 
}

.fade-up { 
    animation: fadeUp 0.5s ease-out; 
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>