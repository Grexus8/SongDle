<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { onMounted } from 'vue';
import { computed } from 'vue';
import axios from 'axios';
const route = useRoute();
const router = useRouter()
const userStorage = localStorage.getItem('user')
const userId = userStorage ? JSON.parse(userStorage).id_usuario : null;
const intentos = Number(route.query.partida);
const victoria = route.query.victoria;
const modo = route.query.modo;
const modoActivo = ref('cancion');

const statsCancion = ref({ 
    partidas_jugadas: 0, 
    partidas_ganadas: 0, 
    historial_intentos: [] 
});

const statsAlbum = ref({ 
    partidas_jugadas: 0, 
    partidas_ganadas: 0, 
    historial_intentos: [] 
});

const statsArtista = ref({ 
    partidas_jugadas: 0, 
    partidas_ganadas: 0, 
    historial_intentos: [] 
});
onMounted(async () => {
    if (!userId) return;

    try {
        const respuesta = await axios.get(`http://localhost:8080/api/estadisticas/usuario/${userId}`);
        const datosBackend = respuesta.data;

        datosBackend.forEach(estadisticas => {
            if (estadisticas.modo_juego === 'cancion'){
            statsCancion.value = estadisticas
        }
        if (estadisticas.modo_juego === 'album'){
            statsAlbum.value = estadisticas
        }
        if (estadisticas.modo_juego === 'artista'){
            statsArtista.value = estadisticas
        }            
        });
        await procesarPartidaNueva()
        
    } catch (error) {
        console.error("Error al cargar las estadísticas:", error);
    }
});
const procesarPartidaNueva = async () => {
    if (!modo) return;

    let datoModo = null;
    if (modo === 'cancion') datoModo = statsCancion.value;
    else if (modo === 'album') datoModo = statsAlbum.value;
    else if (modo === 'artista') datoModo = statsArtista.value;

    if (datoModo) {
        datoModo.partidas_jugadas++;
        
        if (victoria === 'true') {
            datoModo.partidas_ganadas++;
            datoModo.historial_intentos.push(intentos);
        }

        // 3. Enviamos a la BD
        try {
            await axios.post('http://localhost:8080/api/estadisticas', {
                id_usuario: userId,
                modo_juego: modo,
                partidas_jugadas: datoModo.partidas_jugadas,
                partidas_ganadas: datoModo.partidas_ganadas,
                historial_intentos: datoModo.historial_intentos,
            });
            
            // Limpiamos la URL
            router.replace({ query: {} });
        } catch (error) {
            console.error("Error al guardar la partida:", error);
        }
    }
};
// 1. Obtener qué datos estamos visualizando según la pestaña activa
const datosActuales = computed(() => {
    if (modoActivo.value === 'cancion') return statsCancion.value;
    if (modoActivo.value === 'album') return statsAlbum.value;
    return statsArtista.value;
});

// 2. Cálculo del porcentaje
const porcentajeVictorias = computed(() => {
    if (!datosActuales.value || datosActuales.value.partidas_jugadas === 0) return 0;
    return Math.round((datosActuales.value.partidas_ganadas / datosActuales.value.partidas_jugadas) * 100);
});

// 3. Distribución de intentos (del 1 al 10)
// Esto transforma un array tipo [1, 3, 3, 4] en un array de conteo: [1, 0, 2, 1, 0, 0, 0, 0, 0, 0]
const distribucionIntentos = computed(() => {
    const conteo = Array(10).fill(0);
    const historial = datosActuales.value?.historial_intentos;

    if (Array.isArray(historial)) {
        historial.forEach(intento => {
            if (intento >= 1 && intento <= 10) {
                conteo[intento - 1]++;
            }
        });
    }
    return conteo;
});

// 4. Calculamos el valor máximo de aciertos para saber cuánto debe medir la barra más larga
const maxIntentos = computed(() => {
    const maximo = Math.max(...distribucionIntentos.value);
    return maximo > 0 ? maximo : 1;
});

// 5. Función helper para el estilo CSS (ancho de la barra)
const calcularAnchoBarra = (cantidad) => {
    if (cantidad === 0) return '7%'; 
    const porcentaje = (cantidad / maxIntentos.value) * 100;
    return Math.max(7, porcentaje) + '%';
};
</script>
<template>
    <div class="app-container">
        <RouterLink to="/" class="btn-volver" title="Volver al inicio">
            <svg xmlns="http://www.w3.org/2000/svg" class="icono-volver">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </RouterLink>

        <main class="main-content">
            <div class="config-card">
                
                <div class="header-card">
                    <h1 class="logo">Estadísticas</h1>
                </div>

                <nav class="tabs-container">
                    <button 
                        @click="modoActivo = 'cancion'" 
                        class="tab-btn" 
                        :class="{ active: modoActivo === 'cancion' }"
                    >Canción</button>
                    <button 
                        @click="modoActivo = 'artista'" 
                        class="tab-btn" 
                        :class="{ active: modoActivo === 'artista' }"
                    >Artista</button>
                    <button 
                        @click="modoActivo = 'album'" 
                        class="tab-btn" 
                        :class="{ active: modoActivo === 'album' }"
                    >Álbum</button>
                </nav>

                <div class="card-content">
                    <div class="resumen-grid">
                        <div class="tarjeta-dato">
                            <span class="label">Partidas jugadas</span>
                            <span class="valor">{{ datosActuales.partidas_jugadas }}</span>
                        </div>
                        <div class="tarjeta-dato">
                            <span class="label">Victorias</span>
                            <span class="valor">{{ datosActuales.partidas_ganadas }}</span>
                        </div>
                        <div class="tarjeta-dato">
                            <span class="label">% victorias</span>
                            <span class="valor">{{ porcentajeVictorias }}%</span>
                        </div>
                    </div>

                    <div class="grafico-container">
                        <h3 class="step-title">DISTRIBUCIÓN DE INTENTOS</h3>
                        
                        <div class="filas-distribucion">
                            <div v-for="(cantidad, index) in distribucionIntentos" :key="index" class="fila">
                                <span class="numero-intento">{{ index + 1 }}</span>
                                <div class="barra-fondo">
                                    <div 
                                        class="barra-relleno" 
                                        :class="{ 'barra-vacia': cantidad === 0 }"
                                        :style="{ width: calcularAnchoBarra(cantidad) }"
                                    >
                                        <span class="cantidad-texto">{{ cantidad }}</span>
                                    </div>
                                </div>
                            </div>
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
    position: relative;
}

.btn-volver {
    position: absolute;
    top: 30px;
    left: 40px;
    width: 50px;
    height: 50px;
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
    z-index: 10;
}

.btn-volver:hover {
    color: #ffffff;
    border-color: #ec4899;
    background-color: #1a1e29;
    box-shadow: 0 0 15px rgba(236, 72, 153, 0.4);
    transform: translateX(-3px);
}

.icono-volver {
    width: 24px;
    height: 24px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2.5px;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.main-content {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem;
    margin-top: 2rem;
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

.header-card {
    padding: 2.5rem 0 1.5rem 0;
    text-align: center;
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
    padding: 3rem;
    display: flex;
    flex-direction: column;
}

.resumen-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 3.5rem;
}

.tarjeta-dato {
    background-color: rgba(255, 255, 255, 0.02);
    border: 1px solid #334155;
    padding: 1.5rem;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: all 0.3s ease;
}

.tarjeta-dato:hover {
    border-color: #64748b;
    background-color: rgba(255, 255, 255, 0.05);
}

.tarjeta-dato .label {
    font-size: 0.8rem;
    color: #94a3b8;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.tarjeta-dato .valor {
    font-size: 2.5rem;
    font-weight: 700;
    color: #ffffff;
}

.step-title {
    font-size: 1.1rem;
    color: #f8fafc;
    font-weight: 600;
    margin-bottom: 1.5rem;
    letter-spacing: 1px;
    text-align: left;
}

.filas-distribucion {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.fila {
    display: flex;
    align-items: center;
    gap: 15px;
}

.numero-intento {
    width: 20px;
    text-align: right;
    font-weight: 700;
    font-size: 1.1rem;
    color: #cbd5e1;
}

.barra-fondo {
    flex-grow: 1;
    display: flex;
}

.barra-relleno {
    background: linear-gradient(90deg, #8b5cf6, #ec4899);
    min-height: 32px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 12px;
    border-radius: 4px;
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 10px rgba(236, 72, 153, 0.15);
}

.cantidad-texto {
    font-weight: 700;
    font-size: 1rem;
    color: #ffffff;
}

.barra-vacia {
    background: #1e293b;
    border: none;
    box-shadow: none;
    justify-content: center;
    padding-right: 0;
}

.barra-vacia .cantidad-texto {
    color: #64748b;
}
</style>