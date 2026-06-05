<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { RouterLink } from 'vue-router';

const token = localStorage.getItem('token');

const activeTab = ref('artistas');
const mensajeExito = ref('');
const mensajeError = ref('');

const artistas = ref([]);
const albumes = ref([]);
const canciones = ref([]);

const busquedaArtista = ref('');
const busquedaAlbum = ref('');
const busquedaCancion = ref('');

// ── Paginación ────────────────────────────────────────────────
const POR_PAGINA = 30;
const paginaArtistas  = ref(1);
const paginaAlbumes   = ref(1);
const paginaCanciones = ref(1);

const newArtist = ref({ nombre: '', pais: '', genero: '', debut: '', cantidad_albumes: null, premios: '', oyentes_mensuales: null });
const newAlbum  = ref({ nombre: '', id_artista: '', fecha_lanzamiento: '', cantidad_canciones: null, colaboraciones: false, premios: '', reproducciones: null });
const newSong   = ref({ titulo: '', id_artista: '', id_album: '', registration_date: '', pais: '', anio: '', genero: '', reproducciones: null });

// ── Filtrado ──────────────────────────────────────────────────
const artistasFiltrados = computed(() => {
    if (!busquedaArtista.value.trim()) return artistas.value;
    const q = busquedaArtista.value.toLowerCase();
    return artistas.value.filter(a => a.nombre.toLowerCase().includes(q) || a.genero?.toLowerCase().includes(q));
});

const albumesFiltrados = computed(() => {
    if (!busquedaAlbum.value.trim()) return albumes.value;
    const q = busquedaAlbum.value.toLowerCase();
    return albumes.value.filter(a => a.nombre.toLowerCase().includes(q) || a.artist?.nombre?.toLowerCase().includes(q));
});

const cancionesFiltradas = computed(() => {
    if (!busquedaCancion.value.trim()) return canciones.value;
    const terminos = busquedaCancion.value.toLowerCase().split(' ').filter(t => t);
    return canciones.value.filter(c => {
        const texto = `${c.titulo} ${c.artist?.nombre || ''}`.toLowerCase();
        return terminos.every(t => texto.includes(t));
    });
});

// ── Paginado (resetea página al buscar) ───────────────────────
const artistasPaginados = computed(() => {
    const start = (paginaArtistas.value - 1) * POR_PAGINA;
    return artistasFiltrados.value.slice(start, start + POR_PAGINA);
});

const albumesPaginados = computed(() => {
    const start = (paginaAlbumes.value - 1) * POR_PAGINA;
    return albumesFiltrados.value.slice(start, start + POR_PAGINA);
});

const cancionesPaginadas = computed(() => {
    const start = (paginaCanciones.value - 1) * POR_PAGINA;
    return cancionesFiltradas.value.slice(start, start + POR_PAGINA);
});

const totalPaginasArtistas  = computed(() => Math.ceil(artistasFiltrados.value.length / POR_PAGINA));
const totalPaginasAlbumes   = computed(() => Math.ceil(albumesFiltrados.value.length / POR_PAGINA));
const totalPaginasCanciones = computed(() => Math.ceil(cancionesFiltradas.value.length / POR_PAGINA));

// Resetear página al buscar
const onBusquedaArtista  = () => { paginaArtistas.value  = 1; };
const onBusquedaAlbum    = () => { paginaAlbumes.value   = 1; };
const onBusquedaCancion  = () => { paginaCanciones.value = 1; };

// ── Datos ─────────────────────────────────────────────────────
const cargarDatos = async () => {
    try {
        const [resA, resAl, resC] = await Promise.all([
            axios.get('http://localhost:8080/api/artists',  { headers: { Authorization: `Bearer ${token}` } }),
            axios.get('http://localhost:8080/api/albums',   { headers: { Authorization: `Bearer ${token}` } }),
            axios.get('http://localhost:8080/api/songs',    { headers: { Authorization: `Bearer ${token}` } }),
        ]);
        artistas.value  = resA.data;
        albumes.value   = resAl.data;
        canciones.value = resC.data;
    } catch (error) {
        console.error("Error cargando datos:", error);
    }
};

// ── CRUD ──────────────────────────────────────────────────────
const crearArtista = async () => {
    mensajeError.value = ''; mensajeExito.value = '';
    try {
        await axios.post('http://localhost:8080/api/artists', newArtist.value, { headers: { Authorization: `Bearer ${token}` } });
        mensajeExito.value = "Artista añadido correctamente.";
        newArtist.value = { nombre: '', pais: '', genero: '', debut: '', cantidad_albumes: null, premios: '', oyentes_mensuales: null };
        cargarDatos();
    } catch { mensajeError.value = "Error al guardar el artista."; }
};

const eliminarArtista = async (id) => {
    mensajeError.value = ''; mensajeExito.value = '';
    try {
        await axios.delete(`http://localhost:8080/api/artists/${id}`, { headers: { Authorization: `Bearer ${token}` } });
        mensajeExito.value = "Artista eliminado.";
        cargarDatos();
    } catch { mensajeError.value = "Error al eliminar el artista."; }
};

const crearAlbum = async () => {
    mensajeError.value = ''; mensajeExito.value = '';
    try {
        await axios.post('http://localhost:8080/api/albums', newAlbum.value, { headers: { Authorization: `Bearer ${token}` } });
        mensajeExito.value = "Álbum añadido correctamente.";
        newAlbum.value = { nombre: '', id_artista: '', fecha_lanzamiento: '', cantidad_canciones: null, colaboraciones: false, premios: '', reproducciones: null };
        cargarDatos();
    } catch { mensajeError.value = "Error al guardar el álbum."; }
};

const eliminarAlbum = async (id) => {
    mensajeError.value = ''; mensajeExito.value = '';
    try {
        await axios.delete(`http://localhost:8080/api/albums/${id}`, { headers: { Authorization: `Bearer ${token}` } });
        mensajeExito.value = "Álbum eliminado.";
        cargarDatos();
    } catch { mensajeError.value = "Error al eliminar el álbum."; }
};

const crearCancion = async () => {
    mensajeError.value = ''; mensajeExito.value = '';
    try {
        await axios.post('http://localhost:8080/api/songs', newSong.value, { headers: { Authorization: `Bearer ${token}` } });
        mensajeExito.value = "Canción añadida correctamente.";
        newSong.value = { titulo: '', id_artista: '', id_album: '', registration_date: '', pais: '', anio: '', genero: '', reproducciones: 0 };
        cargarDatos();
    } catch { mensajeError.value = "Error al guardar la canción."; }
};

const eliminarCancion = async (id) => {
    mensajeError.value = ''; mensajeExito.value = '';
    try {
        await axios.delete(`http://localhost:8080/api/songs/${id}`, { headers: { Authorization: `Bearer ${token}` } });
        mensajeExito.value = "Canción eliminada.";
        cargarDatos();
    } catch { mensajeError.value = "Error al eliminar la canción."; }
};

onMounted(() => cargarDatos());
</script>

<template>
    <div class="contenedor-juego">

        <RouterLink to="/" class="btn-volver" title="Volver al inicio">
            <svg xmlns="http://www.w3.org/2000/svg" class="icono-volver">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </RouterLink>

        <h1>SongDle - Admin</h1>

        <div class="marcador-arcade">
            <button @click="activeTab = 'artistas'" class="stat" :class="{'tab-activa': activeTab === 'artistas'}">
                <span>Gestión</span><strong>Artistas</strong>
            </button>
            <button @click="activeTab = 'albumes'" class="stat stat-centro" :class="{'tab-activa': activeTab === 'albumes'}">
                <span>Gestión</span><strong>Álbumes</strong>
            </button>
            <button @click="activeTab = 'canciones'" class="stat" :class="{'tab-activa': activeTab === 'canciones'}">
                <span>Gestión</span><strong>Canciones</strong>
            </button>
        </div>

        <p v-if="mensajeError" class="no-results error-texto">{{ mensajeError }}</p>
        <p v-if="mensajeExito" class="no-results exito-texto">{{ mensajeExito }}</p>

        <div class="historial-intentos">

            <!-- ARTISTAS -->
            <div v-if="activeTab === 'artistas'" class="fade-in">
                <form @submit.prevent="crearArtista" class="formulario-admin">
                    <h2 class="titulo-form">Añadir Artista</h2>
                    <div class="grid-form">
                        <input type="text"   v-model="newArtist.nombre"           placeholder="Nombre del artista"    class="input-buscador" required>
                        <input type="text"   v-model="newArtist.pais"             placeholder="País"                  class="input-buscador" required>
                        <input type="text"   v-model="newArtist.genero"           placeholder="Género"                class="input-buscador" required>
                        <input type="number" v-model="newArtist.debut"            placeholder="Año de debut"          class="input-buscador" required>
                        <input type="number" v-model="newArtist.cantidad_albumes" placeholder="Cantidad de álbumes"   class="input-buscador" required>
                        <input type="number" v-model="newArtist.oyentes_mensuales" placeholder="Oyentes mensuales"   class="input-buscador" required>
                        <input type="text"   v-model="newArtist.premios"          placeholder="Premios (opcional)"    class="input-buscador grid-span-2">
                    </div>
                    <button type="submit" class="play-btn-gradient">GUARDAR ARTISTA</button>
                </form>

                <input v-model="busquedaArtista" @input="onBusquedaArtista" type="text"
                    placeholder="🔍 Buscar artista..." class="input-buscador buscador-lista">

                <!-- Info paginación -->
                <div class="paginacion-info">
                    Mostrando {{ artistasPaginados.length }} de {{ artistasFiltrados.length }} artistas
                </div>

                <div v-for="artista in artistasPaginados" :key="artista.id_artista" class="fila-comparacion">
                    <div class="caja-dato acierto">
                        <small>Nombre</small>
                        <span>{{ artista.nombre }}</span>
                    </div>
                    <div class="caja-dato">
                        <small>Género</small>
                        <span>{{ artista.genero }}</span>
                    </div>
                    <div class="caja-dato caja-accion">
                        <small>Acción</small>
                        <button @click="eliminarArtista(artista.id_artista)" class="btn-eliminar">ELIMINAR</button>
                    </div>
                </div>

                <!-- Paginación artistas -->
                <div class="paginacion" v-if="totalPaginasArtistas > 1">
                    <button class="btn-pagina" :disabled="paginaArtistas === 1" @click="paginaArtistas--">‹</button>
                    <template v-for="p in totalPaginasArtistas" :key="p">
                        <button
                            v-if="p === 1 || p === totalPaginasArtistas || Math.abs(p - paginaArtistas) <= 2"
                            class="btn-pagina"
                            :class="{ 'btn-pagina-activa': p === paginaArtistas }"
                            @click="paginaArtistas = p"
                        >{{ p }}</button>
                        <span v-else-if="Math.abs(p - paginaArtistas) === 3" class="puntos">…</span>
                    </template>
                    <button class="btn-pagina" :disabled="paginaArtistas === totalPaginasArtistas" @click="paginaArtistas++">›</button>
                </div>
            </div>

            <!-- ÁLBUMES -->
            <div v-if="activeTab === 'albumes'" class="fade-in">
                <form @submit.prevent="crearAlbum" class="formulario-admin">
                    <h2 class="titulo-form">Añadir Álbum</h2>
                    <div class="grid-form">
                        <select v-model="newAlbum.id_artista" class="input-buscador grid-span-2" required>
                            <option value="" disabled>Selecciona un artista...</option>
                            <option v-for="a in artistas" :key="a.id_artista" :value="a.id_artista">{{ a.nombre }}</option>
                        </select>
                        <input type="text"   v-model="newAlbum.nombre"            placeholder="Nombre del álbum"  class="input-buscador" required>
                        <input type="date"   v-model="newAlbum.fecha_lanzamiento"                                 class="input-buscador">
                        <input type="number" v-model="newAlbum.cantidad_canciones" placeholder="Cant. Canciones" class="input-buscador">
                        <input type="number" v-model="newAlbum.reproducciones"    placeholder="Reproducciones"   class="input-buscador">
                        <input type="text"   v-model="newAlbum.premios"           placeholder="Premios"          class="input-buscador">
                        <label class="toggle-switch-wrapper">
                            <span class="dificultad-texto">Colaboraciones</span>
                            <div class="toggle-switch">
                                <input type="checkbox" v-model="newAlbum.colaboraciones">
                                <span class="slider"></span>
                            </div>
                        </label>
                    </div>
                    <button type="submit" class="play-btn-gradient">GUARDAR ÁLBUM</button>
                </form>

                <input v-model="busquedaAlbum" @input="onBusquedaAlbum" type="text"
                    placeholder="🔍 Buscar álbum..." class="input-buscador buscador-lista">

                <div class="paginacion-info">
                    Mostrando {{ albumesPaginados.length }} de {{ albumesFiltrados.length }} álbumes
                </div>

                <div v-for="album in albumesPaginados" :key="album.id_album" class="fila-comparacion">
                    <div class="caja-dato acierto">
                        <small>Álbum</small>
                        <span>{{ album.nombre }}</span>
                    </div>
                    <div class="caja-dato">
                        <small>Artista</small>
                        <span>{{ album.artist?.nombre }}</span>
                    </div>
                    <div class="caja-dato caja-accion">
                        <small>Acción</small>
                        <button @click="eliminarAlbum(album.id_album)" class="btn-eliminar">ELIMINAR</button>
                    </div>
                </div>

                <!-- Paginación álbumes -->
                <div class="paginacion" v-if="totalPaginasAlbumes > 1">
                    <button class="btn-pagina" :disabled="paginaAlbumes === 1" @click="paginaAlbumes--">‹</button>
                    <template v-for="p in totalPaginasAlbumes" :key="p">
                        <button
                            v-if="p === 1 || p === totalPaginasAlbumes || Math.abs(p - paginaAlbumes) <= 2"
                            class="btn-pagina"
                            :class="{ 'btn-pagina-activa': p === paginaAlbumes }"
                            @click="paginaAlbumes = p"
                        >{{ p }}</button>
                        <span v-else-if="Math.abs(p - paginaAlbumes) === 3" class="puntos">…</span>
                    </template>
                    <button class="btn-pagina" :disabled="paginaAlbumes === totalPaginasAlbumes" @click="paginaAlbumes++">›</button>
                </div>
            </div>

            <!-- CANCIONES -->
            <div v-if="activeTab === 'canciones'" class="fade-in">
                <form @submit.prevent="crearCancion" class="formulario-admin">
                    <h2 class="titulo-form">Añadir Canción</h2>
                    <div class="grid-form">
                        <input type="text" v-model="newSong.titulo" placeholder="Título de la canción" class="input-buscador grid-span-2" required>
                        <select v-model="newSong.id_artista" class="input-buscador" required>
                            <option value="" disabled>Selecciona un artista...</option>
                            <option v-for="a in artistas" :key="a.id_artista" :value="a.id_artista">{{ a.nombre }}</option>
                        </select>
                        <select v-model="newSong.id_album" class="input-buscador">
                            <option value="">Sin álbum (Single)</option>
                            <option v-for="al in albumes.filter(a => a.id_artista === newSong.id_artista)" :key="al.id_album" :value="al.id_album">
                                {{ al.nombre }}
                            </option>
                        </select>
                        <input type="text"   v-model="newSong.genero"           placeholder="Género"          class="input-buscador">
                        <input type="text"   v-model="newSong.pais"             placeholder="País"            class="input-buscador">
                        <input type="number" v-model="newSong.anio"             placeholder="Año"             class="input-buscador">
                        <input type="number" v-model="newSong.reproducciones"   placeholder="Reproducciones"  class="input-buscador">
                        <input type="date"   v-model="newSong.registration_date"                              class="input-buscador">
                    </div>
                    <button type="submit" class="play-btn-gradient">GUARDAR CANCIÓN</button>
                </form>

                <input v-model="busquedaCancion" @input="onBusquedaCancion" type="text"
                    placeholder="🔍 Buscar canción por título o artista..." class="input-buscador buscador-lista">

                <div class="paginacion-info">
                    Mostrando {{ cancionesPaginadas.length }} de {{ cancionesFiltradas.length }} canciones
                </div>

                <div v-for="cancion in cancionesPaginadas" :key="cancion.id_song" class="fila-comparacion">
                    <div class="caja-dato acierto">
                        <small>Título</small>
                        <span>{{ cancion.titulo }}</span>
                    </div>
                    <div class="caja-dato">
                        <small>Artista</small>
                        <span>{{ cancion.artist?.nombre }}</span>
                    </div>
                    <div class="caja-dato caja-accion">
                        <small>Acción</small>
                        <button @click="eliminarCancion(cancion.id_song)" class="btn-eliminar">ELIMINAR</button>
                    </div>
                </div>

                <!-- Paginación canciones -->
                <div class="paginacion" v-if="totalPaginasCanciones > 1">
                    <button class="btn-pagina" :disabled="paginaCanciones === 1" @click="paginaCanciones--">‹</button>
                    <template v-for="p in totalPaginasCanciones" :key="p">
                        <button
                            v-if="p === 1 || p === totalPaginasCanciones || Math.abs(p - paginaCanciones) <= 2"
                            class="btn-pagina"
                            :class="{ 'btn-pagina-activa': p === paginaCanciones }"
                            @click="paginaCanciones = p"
                        >{{ p }}</button>
                        <span v-else-if="Math.abs(p - paginaCanciones) === 3" class="puntos">…</span>
                    </template>
                    <button class="btn-pagina" :disabled="paginaCanciones === totalPaginasCanciones" @click="paginaCanciones++">›</button>
                </div>
            </div>

        </div>
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
    margin-bottom: 30px;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    background-color: #11141d;
    border: 1px solid #334155;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    overflow: hidden;
}

.stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    background: none;
    border: none;
    padding: 15px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.stat:hover { background-color: rgba(255, 255, 255, 0.05); }

.tab-activa {
    background-color: rgba(168, 85, 247, 0.1) !important;
    box-shadow: inset 0 -3px 0 #a855f7;
}

.stat span {
    font-size: 0.8rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 5px;
}

.stat strong {
    font-size: 1.2rem;
    color: #f8fafc;
    font-family: 'Dela Gothic One', cursive;
}

.stat-centro {
    border-left: 1px solid #334155;
    border-right: 1px solid #334155;
}

.formulario-admin {
    background-color: #11141d;
    border: 1px solid #334155;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

.titulo-form {
    font-size: 1.2rem;
    color: #d8b4fe;
    margin-top: 0;
    margin-bottom: 1.5rem;
    text-align: center;
}

.grid-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 20px;
}

.grid-span-2 { grid-column: 1 / -1; }

.input-buscador {
    width: 100%;
    background-color: #0b0d14;
    color: #f8fafc;
    border: 1px solid #334155;
    padding: 1.2rem;
    border-radius: 12px;
    font-family: inherit;
    font-size: 1rem;
    outline: none;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.input-buscador:focus {
    border-color: #ec4899;
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.15);
}

.input-buscador::placeholder { color: #64748b; }

.toggle-switch-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    background-color: #0b0d14;
    border: 1px solid #334155;
    border-radius: 12px;
    padding: 1.2rem;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 26px;
}

.toggle-switch input { opacity: 0; width: 0; height: 0; }

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

input:checked + .slider:before { transform: translateX(24px); }

.dificultad-texto {
    color: #f8fafc;
    font-weight: 600;
    font-size: 0.95rem;
}

.play-btn-gradient {
    background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
    color: #ffffff;
    border: none;
    border-radius: 12px;
    padding: 1.2rem;
    font-family: inherit;
    font-size: 1.15rem;
    font-weight: 700;
    letter-spacing: 1px;
    cursor: pointer;
    width: 100%;
    box-shadow: 0 10px 25px -5px rgba(236, 72, 153, 0.4);
    transition: all 0.3s ease;
}

.play-btn-gradient:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px -5px rgba(236, 72, 153, 0.6);
}

.play-btn-gradient:active { transform: translateY(1px); }

.btn-eliminar {
    background-color: rgba(239, 68, 68, 0.1);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.3);
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-eliminar:hover {
    background-color: rgba(239, 68, 68, 0.2);
    transform: translateY(-2px);
}

.historial-intentos {
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-width: 800px;
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
    background-color: #11141d;
    border: 1px solid #334155;
    transition: transform 0.2s ease;
    word-break: break-word;
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

.acierto {
    background-color: rgba(168, 85, 247, 0.05);
    border: 1px solid rgba(168, 85, 247, 0.3);
    color: #d8b4fe;
}

.acierto small { color: rgba(216, 180, 254, 0.8); }

.no-results {
    text-align: center;
    font-weight: bold;
    margin-top: -10px;
    margin-bottom: 20px;
}

.error-texto  { color: #ef4444; }
.exito-texto  { color: #4ade80; }

.fade-in { animation: fadeIn 0.3s ease-in-out; }

.buscador-lista {
    margin-bottom: 0.8rem;
    width: 100%;
    display: block;
}

.caja-accion {
    max-width: 140px;
    flex: 0 0 140px;
}

/* ── Paginación ───────────────────────────────── */
.paginacion-info {
    font-size: 0.8rem;
    color: #64748b;
    text-align: right;
    margin-bottom: 0.8rem;
}

.paginacion {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    margin-top: 1.5rem;
    flex-wrap: wrap;
}

.btn-pagina {
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    background-color: #11141d;
    border: 1px solid #334155;
    border-radius: 8px;
    color: #94a3b8;
    font-family: inherit;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-pagina:hover:not(:disabled) {
    border-color: #a855f7;
    color: #d8b4fe;
    background-color: rgba(168, 85, 247, 0.1);
}

.btn-pagina:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.btn-pagina-activa {
    background: linear-gradient(135deg, #8b5cf6, #ec4899) !important;
    border-color: transparent !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
}

.puntos {
    color: #475569;
    font-size: 0.9rem;
    padding: 0 4px;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(15px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>