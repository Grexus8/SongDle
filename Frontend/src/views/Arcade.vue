<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const puntos_totales = ref(0);
const canciones_adivinadas = ref(0);
const vidas = ref(3);
const canciones = ref([]);
const userStorage = localStorage.getItem('user');
const userId = userStorage ? JSON.parse(userStorage).id_usuario : null;
const cancionActual = ref(null);
const busqueda = ref("");
const intentos = ref([]);
const mostrarModalPerdedor = ref(false);

onMounted(async () => {
    try {
        const respuesta = await axios.get('http://localhost:8080/api/songs');
        canciones.value = respuesta.data;
        nuevaCancion();
    } catch (error) {
        console.error("Error cargando las canciones:", error);
    }
});

const nuevaCancion = () => {
    if (canciones.value.length > 0) {
        const aleatorio = Math.floor(Math.random() * canciones.value.length);
        cancionActual.value = canciones.value[aleatorio];
        
        intentos.value = [];
        busqueda.value = "";
        console.log("🤫 La canción actual es:", cancionActual.value.titulo);
    }
}

const guardarPartida = async () => {
    try {
        const respuesta = await axios.post(`http://localhost:8080/api/arcade/guardar-partida/${userId}`, {
            puntos_totales: puntos_totales.value,
            canciones_adivinadas: canciones_adivinadas.value
        });
        console.log("Datos guardados en la base de datos:", respuesta.data);
    } catch (error) {
        console.error("Hubo un fallo al intentar guardar la partida:", error);
    }
}

const cancionesFiltradas = computed(() => {
    if (busqueda.value.trim() === "") return []; 

    const terminosBusqueda = busqueda.value.toLowerCase().split(' ').filter(t => t !== '');

    return canciones.value.filter(cancion => {
        const yaIntentado = intentos.value.some(i => i.id_song === cancion.id_song);
        const textoCompleto = `${cancion.titulo} ${cancion.artist?.nombre || ''}`.toLowerCase();
        const coincide = terminosBusqueda.every(termino => textoCompleto.includes(termino));
        return coincide && !yaIntentado;
    });
});

const seleccionar = (cancion) => {
    intentos.value.unshift(cancion);
    busqueda.value = "";

    if (cancion.id_song === cancionActual.value.id_song) {
        canciones_adivinadas.value++;
        puntos_totales.value += (1000 - (intentos.value.length * 100)); 
        nuevaCancion();
    } 
    else if (intentos.value.length >= 8) {
        vidas.value--;
        
        if (vidas.value <= 0) {
            mostrarModalPerdedor.value = true;
            guardarPartida(); 
        } else {
            nuevaCancion(); 
        }
    }
}

const PartidaJugada = () => {
    router.push({
        name: 'ranking'
    });
}

const obtenerClaseMultiple = (intentoValor, secretoValor) => {
    if (!intentoValor || !secretoValor) return 'fallo';
    if (intentoValor === secretoValor) return 'acierto';

    const arrayIntento = String(intentoValor).split(',').map(p => p.trim().toLowerCase());
    const arraySecreto = String(secretoValor).split(',').map(p => p.trim().toLowerCase());

    const coincidencias = arrayIntento.filter(v => arraySecreto.includes(v));

    if (coincidencias.length === arraySecreto.length && arrayIntento.length === arraySecreto.length) {
        return 'acierto';
    } else if (coincidencias.length > 0) {
        return 'masomenos';
    } else {
        return 'fallo';
    }
}
</script>

<template>
    <div class="contenedor-juego">
        
        <RouterLink to="/" class="btn-volver" title="Volver al inicio">
            <svg xmlns="http://www.w3.org/2000/svg" class="icono-volver">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </RouterLink>

        <h1>Arcade</h1>
        
        <div class="marcador-arcade">
            <div class="stat">
                <span>Vidas</span>
                <strong :class="{'texto-rojo': vidas === 1}">{{ '❤️'.repeat(vidas) }}</strong>
            </div>
            <div class="stat stat-centro">
                <span>Puntos</span>
                <strong>{{ puntos_totales }}</strong>
            </div>
            <div class="stat">
                <span>Adivinadas</span>
                <strong>{{ canciones_adivinadas }}</strong>
            </div>
        </div>
        
        <div class="buscador-wrapper">
            <input 
                type="text" 
                v-model="busqueda" 
                :disabled="vidas <= 0"
                placeholder="Escribe el nombre de una canción..."
                class="input-buscador"
            >
            
            <ul v-if="cancionesFiltradas.length > 0" class="lista-resultados">
                <li 
                    v-for="cancion in cancionesFiltradas" 
                    :key="cancion.id_song" 
                    @click="seleccionar(cancion)"
                    class="item-lista"
                > 
                    {{ cancion.titulo }} - {{ cancion.artist?.nombre }}
                </li>
            </ul>
        </div>

        <p v-if="cancionesFiltradas.length === 0 && busqueda !== ''" class="no-results">
            No se encontraron coincidencias
        </p>

        <div class="historial-intentos">
            <div v-for="intento in intentos" :key="intento.id_song" class="fila-comparacion">
                
                <div class="caja-dato" :class="intento.titulo === cancionActual.titulo ? 'acierto' : 'fallo'">
                    <small>Título</small>
                    <span>{{ intento.titulo }}</span>
                </div>

                <div class="caja-dato" :class="intento.id_artista === cancionActual.id_artista ? 'acierto' : 'fallo'">
                    <small>Artista</small>
                    <span>{{ intento.artist?.nombre || 'Desconocido' }}</span>
                </div>

                <div class="caja-dato" :class="intento.pais === cancionActual.pais ? 'acierto' : 'fallo'">
                    <small>País</small>
                    <span>{{ intento.pais }}</span>
                </div>

                <div class="caja-dato" :class="obtenerClaseMultiple(intento.genero, cancionActual.genero)">
                    <small>Género</small>
                    <span>{{ intento.genero }}</span>
                </div>

                <div class="caja-dato" :class="intento.anio === cancionActual.anio ? 'acierto' : 'fallo'">
                    <small>Año</small>
                    <span>
                        {{ intento.anio }}
                        <span v-if="intento.anio < cancionActual.anio" class="flecha">
                            <svg class="icono-flecha"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                        </span>
                        <span v-else-if="intento.anio > cancionActual.anio" class="flecha">
                            <svg class="icono-flecha"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                        </span>
                    </span>
                </div>

                <div class="caja-dato" :class="intento.reproducciones === cancionActual.reproducciones ? 'acierto' : 'fallo'">
                    <small>Reproducciones</small>
                    <span>
                        {{ intento.reproducciones ? intento.reproducciones.toLocaleString() : 0 }}
                        <span v-if="intento.reproducciones < cancionActual.reproducciones" class="flecha">
                            <svg class="icono-flecha"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                        </span>
                        <span v-else-if="intento.reproducciones > cancionActual.reproducciones" class="flecha">
                            <svg class="icono-flecha"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                        </span>
                    </span>
                </div>

            </div>
        </div>

        <Teleport to="body">
            <div v-if="mostrarModalPerdedor" class="modal-overlay"> 
                <div class="modal-content" @click.stop>
                    <h2>GAME OVER</h2>
                    <p>Puntos conseguidos: {{ puntos_totales }}</p>
                    <button class="btn-volver-inicio" @click="PartidaJugada">Ver estadísticas</button>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Montserrat:wght@400;500;600;700&display=swap');

.contenedor-juego {
    font-family: 'Montserrat', sans-serif;
    min-height: 100vh;
    background: radial-gradient(circle at top, #141824 0%, #0a0c13 100%);
    color: #f1f5f9;
    padding: 40px 20px;
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

h1 {
    text-align: center;
    font-family: 'Dela Gothic One', cursive;
    font-size: 3rem;
    margin-bottom: 20px;
    background: linear-gradient(to right, #ffffff, #d8b4fe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 4px 20px rgba(171, 71, 188, 0.3);
    letter-spacing: normal;
}

.marcador-arcade {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 30px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    background-color: #11141d;
    border: 1px solid #334155;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

.stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
}

.stat span {
    font-size: 0.8rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 5px;
}

.stat strong {
    font-size: 1.5rem;
    color: #f8fafc;
    font-family: 'Dela Gothic One', cursive;
}

.stat-centro {
    border-left: 1px solid #334155;
    border-right: 1px solid #334155;
}

.texto-rojo {
    color: #ef4444 !important;
    animation: parpadeo 1s infinite;
}

@keyframes parpadeo {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.buscador-wrapper {
    position: relative;
    margin-bottom: 40px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.input-buscador {
    width: 100%;
    background-color: #0b0d14;
    color: #f8fafc;
    border: 1px solid #334155;
    padding: 1.2rem;
    border-radius: 12px;
    font-family: inherit;
    font-size: 1.1rem;
    outline: none;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

.input-buscador:focus {
    border-color: #ec4899;
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.15), 0 10px 25px rgba(0,0,0,0.5);
}

.input-buscador:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.input-buscador::placeholder {
    color: #64748b;
}

.lista-resultados {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    right: 0;
    background-color: #11141d;
    border: 1px solid #ec4899;
    border-radius: 12px;
    z-index: 100;
    list-style: none;
    padding: 0;
    margin: 0;
    max-height: 250px;
    overflow-y: auto;
    box-shadow: 0 15px 35px rgba(0,0,0,0.6);
}

.item-lista {
    padding: 1rem 1.2rem;
    color: #cbd5e1;
    cursor: pointer;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    transition: background-color 0.2s, color 0.2s;
    font-weight: 500;
}

.item-lista:last-child {
    border-bottom: none;
}

.item-lista:hover {
    background-color: #1a1f2e;
    color: #ffffff;
}

.lista-resultados::-webkit-scrollbar {
    width: 8px;
}

.lista-resultados::-webkit-scrollbar-track {
    background: #0b0d14;
    border-radius: 0 12px 12px 0;
}

.lista-resultados::-webkit-scrollbar-thumb {
    background: #334155;
    border-radius: 4px;
}

.lista-resultados::-webkit-scrollbar-thumb:hover {
    background: #ec4899;
}

.no-results {
    text-align: center;
    color: #ec4899;
    font-style: italic;
    font-weight: 500;
    margin-top: -20px;
    margin-bottom: 30px;
}

.historial-intentos {
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-width: 1100px;
    margin: 0 auto;
}

.fila-comparacion {
    display: flex;
    gap: 10px;
    animation: fadeUp 0.4s ease-out forwards;
}

.caja-dato {
    flex: 1;
    padding: 15px 8px;
    border-radius: 10px;
    text-align: center;
    color: #f1f5f9;
    font-weight: 600;
    font-size: 0.9em;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 85px;
    background-color: #11141d;
    border: 1px solid #334155;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    word-break: break-word;
    transition: transform 0.2s ease;
}

.caja-dato:hover {
    transform: translateY(-2px);
}

.caja-dato small {
    font-weight: 600;
    font-size: 0.7em;
    text-transform: uppercase;
    margin-bottom: 8px;
    color: #94a3b8;
    letter-spacing: 1px;
}

.caja-dato span {
    display: flex;
    align-items: center;
    justify-content: center;
}

.flecha {
    display: inline-block;
    margin-left: 4px;
}

.icono-flecha {
    width: 18px;
    height: 18px;
    fill: none;
    stroke: currentColor;
    stroke-width: 3px;
    stroke-linecap: round;
    stroke-linejoin: round;
    transition: transform 0.2s;
}

.acierto {
    background-color: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.4);
    color: #4ade80;
    box-shadow: inset 0 0 15px rgba(34, 197, 94, 0.05);
}

.fallo {
    background-color: rgba(239, 68, 68, 0.05);
    border: 1px solid rgba(239, 68, 68, 0.2);
    color: #f87171;
}

.masomenos {
    background-color: rgba(234, 179, 8, 0.1);
    border: 1px solid rgba(234, 179, 8, 0.4);
    color: #facc15;
    box-shadow: inset 0 0 15px rgba(234, 179, 8, 0.05);
}

.acierto small {
    color: rgba(74, 222, 128, 0.8);
}

.fallo small {
    color: rgba(248, 113, 113, 0.8);
}

.masomenos small {
    color: rgba(250, 204, 21, 0.8);
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal-content {
    background-color: #11141d;
    border: 1px solid #ec4899;
    padding: 3rem;
    border-radius: 16px;
    text-align: center;
    color: #f8fafc;
    box-shadow: 0 20px 50px rgba(0,0,0,0.7);
    animation: fadeUp 0.3s ease-out;
    max-width: 400px;
    width: 90%;
}

.modal-content h2 {
    font-family: 'Dela Gothic One', cursive;
    font-size: 2rem;
    margin-top: 0;
    margin-bottom: 1rem;
    background: linear-gradient(to right, #ef4444, #ec4899);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.btn-volver-inicio {
    background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    font-family: inherit;
    width: 100%;
}

.btn-volver-inicio:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(236, 72, 153, 0.4);
}
</style>