<script setup>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router'; 

const router = useRouter();
const busqueda = ref("");
const listarAlbums = ref([]);
const albumSecreto = ref(null);
const mostrarModalGanador = ref(false);
const mostrarModalPerdedor = ref(false);
const victoria = ref(false);
const intentos = ref([]);
const modo = 'album';
const userStorage = localStorage.getItem('user');
const userId = userStorage ? JSON.parse(userStorage).id_usuario : null;

onMounted(async () => {
    try {
        const cargar = await axios.get('http://localhost:8080/api/albums');
        listarAlbums.value = cargar.data;

        if (listarAlbums.value.length > 0) {
            const aleatorio = Math.floor(Math.random() * listarAlbums.value.length);
            albumSecreto.value = listarAlbums.value[aleatorio];
            console.log("🤫 El álbum secreto es:", albumSecreto.value.nombre);
        }
    } catch (error) {
        console.error("Error cargando álbums:", error);
    }
});

const albumsFiltrados = computed(() => {
    if (busqueda.value.trim() === "") return [];

    const terminosBusqueda = busqueda.value.toLowerCase().split(' ').filter(t => t !== '');

    return listarAlbums.value.filter(album => {
        const yaIntentado = intentos.value.some(i => i.id_album === album.id_album);
        const textoCompleto = `${album.nombre} ${album.artist?.nombre || ''}`.toLowerCase();
        const coincide = terminosBusqueda.every(termino => textoCompleto.includes(termino));
        return coincide && !yaIntentado;
    });
});

const seleccionar = (album) => {
    intentos.value.unshift(album);
    busqueda.value = "";
    if (album.id_album === albumSecreto.value.id_album) {
        victoria.value = true;
        mostrarModalGanador.value = true; 
    } 
    else if (intentos.value.length >= 5) {
        mostrarModalPerdedor.value = true;
    }
}

const PartidaJugada = () => {
    router.push({
        name: 'estadisticas',
        params: { id: userId },
        query: { 
            partida: intentos.value.length,
            victoria: victoria.value,
            modo: modo
        }
    });
}

const getAnio = (fecha) => {
    if (!fecha) return 0;
    return new Date(fecha).getFullYear();
}
</script>

<template>
    <div class="contenedor-juego">
        
        <RouterLink to="/" class="btn-volver" title="Volver a Inicio">
            <svg xmlns="http://www.w3.org/2000/svg" class="icono-volver">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </RouterLink>

        <h1>SongDle - Álbums</h1>
        
        <div class="buscador-wrapper">
            <input 
                type="text" 
                v-model="busqueda" 
                placeholder="Escribe el nombre de un álbum..."
                class="input-buscador"
            >
            
            <ul v-if="albumsFiltrados.length > 0" class="lista-resultados">
                <li 
                    v-for="album in albumsFiltrados" 
                    :key="album.id_album" 
                    @click="seleccionar(album)"
                    class="item-lista"
                > 
                    {{ album.nombre }} - {{ album.artist?.nombre }}
                </li>
            </ul>
        </div>

        <p v-if="albumsFiltrados.length === 0 && busqueda !== ''" class="no-results">
            No se encontraron coincidencias
        </p>

        <div class="historial-intentos">
            <div v-for="intento in intentos" :key="intento.id_album" class="fila-comparacion">
                
                <div class="caja-dato" :class="intento.nombre === albumSecreto.nombre ? 'acierto' : 'fallo'">
                    <small>Álbum</small>
                    <span>{{ intento.nombre }}</span>
                </div>

                <div class="caja-dato" :class="intento.id_artista === albumSecreto.id_artista ? 'acierto' : 'fallo'">
                    <small>Artista</small>
                    <span>{{ intento.artist?.nombre || 'Desconocido' }}</span>
                </div>

                <div class="caja-dato" :class="getAnio(intento.fecha_lanzamiento) === getAnio(albumSecreto.fecha_lanzamiento) ? 'acierto' : 'fallo'">
                    <small>Año</small>
                    <span>
                        {{ getAnio(intento.fecha_lanzamiento) }}
                        <span v-if="getAnio(intento.fecha_lanzamiento) < getAnio(albumSecreto.fecha_lanzamiento)" class="flecha">
                            <svg class="icono-flecha"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                        </span>
                        <span v-else-if="getAnio(intento.fecha_lanzamiento) > getAnio(albumSecreto.fecha_lanzamiento)" class="flecha">
                            <svg class="icono-flecha"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                        </span>
                    </span>
                </div>

                <div class="caja-dato" :class="intento.cantidad_canciones === albumSecreto.cantidad_canciones ? 'acierto' : 'fallo'">
                    <small>Canciones</small>
                    <span>
                        {{ intento.cantidad_canciones }}
                        <span v-if="intento.cantidad_canciones < albumSecreto.cantidad_canciones" class="flecha">
                            <svg class="icono-flecha"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                        </span>
                        <span v-else-if="intento.cantidad_canciones > albumSecreto.cantidad_canciones" class="flecha">
                            <svg class="icono-flecha"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                        </span>
                    </span>
                </div>

                <div class="caja-dato" :class="Boolean(intento.colaboraciones) === Boolean(albumSecreto.colaboraciones) ? 'acierto' : 'fallo'">
                    <small>Feat.</small>
                    <span>{{ intento.colaboraciones ? 'Sí' : 'No' }}</span>
                </div>

                <div class="caja-dato" :class="intento.premios === albumSecreto.premios ? 'acierto' : 'fallo'">
                    <small>Premios</small>
                    <span>{{ intento.premios ?? 'Ninguno' }}</span>
                </div>

                <div class="caja-dato" :class="intento.reproducciones === albumSecreto.reproducciones ? 'acierto' : 'fallo'">
                    <small>Streams</small>
                    <span>
                        {{ intento.reproducciones?.toLocaleString() }}
                        <span v-if="intento.reproducciones < albumSecreto.reproducciones" class="flecha">
                            <svg class="icono-flecha"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                        </span>
                        <span v-else-if="intento.reproducciones > albumSecreto.reproducciones" class="flecha">
                            <svg class="icono-flecha"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                        </span>
                    </span>
                </div>

            </div>
        </div>

        <Teleport to="body">
            <div v-if="mostrarModalGanador" class="modal-overlay"> 
                <div class="modal-content" @click.stop>
                    <h2>¡FELICIDADES HAS GANADO!</h2>
                    <button class="btn-volver-inicio" @click="PartidaJugada">Ver estadísticas</button>
                </div>
            </div>
        </Teleport>
        <Teleport to="body">
            <div v-if="mostrarModalPerdedor" class="modal-overlay"> 
                <div class="modal-content" @click.stop>
                    <h2>HAS PERDIDO</h2>
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
    margin-bottom: 35px;
    background: linear-gradient(to right, #ffffff, #d8b4fe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 4px 20px rgba(171, 71, 188, 0.3);
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

.input-buscador::placeholder { color: #64748b; }

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
.item-lista:last-child { border-bottom: none; }
.item-lista:hover { background-color: #1a1f2e; color: #ffffff; }

.lista-resultados::-webkit-scrollbar { width: 8px; }
.lista-resultados::-webkit-scrollbar-track { background: #0b0d14; border-radius: 0 12px 12px 0; }
.lista-resultados::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
.lista-resultados::-webkit-scrollbar-thumb:hover { background: #ec4899; }

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
    max-width: 1050px;
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

.caja-dato:hover { transform: translateY(-2px); }

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

.flecha { display: inline-block; margin-left: 4px; }

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

.acierto { background-color: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.4); color: #4ade80; box-shadow: inset 0 0 15px rgba(34, 197, 94, 0.05); }
.fallo { background-color: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171; }
.masomenos { background-color: rgba(234, 179, 8, 0.1); border: 1px solid rgba(234, 179, 8, 0.4); color: #facc15; box-shadow: inset 0 0 15px rgba(234, 179, 8, 0.05); }

.acierto small { color: rgba(74, 222, 128, 0.8); }
.fallo small { color: rgba(248, 113, 113, 0.8); }
.masomenos small { color: rgba(250, 204, 21, 0.8); }

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

.modal-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    background-color: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(8px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal-content {
    background-color: #11141d;
    border: 1px solid #ec4899;
    padding: 3rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 25px 50px rgba(0,0,0,0.5);
    max-width: 400px;
    width: 90%;
    animation: fadeUp 0.3s ease-out;
}

.modal-content h2 {
    font-family: 'Dela Gothic One', cursive;
    color: #f8fafc;
    margin-bottom: 2rem;
    background: linear-gradient(to right, #ffffff, #d8b4fe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.btn-volver-inicio {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
    width: 100%;
    font-family: inherit;
    font-size: 1.1rem;
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-volver-inicio:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(236, 72, 153, 0.4);
}
</style>