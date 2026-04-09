<script setup>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';

const busqueda = ref("");
const listarArtistas = ref([]);
const artistSecreto = ref(null);

const intentos = ref([]);

onMounted(async () => {
    try {
        const cargar = await axios.get("http://localhost:8080/api/artists");
        listarArtistas.value = cargar.data;

        const aleatorio = Math.floor(Math.random() * listarArtistas.value.length);
        artistSecreto.value = listarArtistas.value[aleatorio];
        
        console.log("🤫 El artista secreto es:", artistSecreto.value.nombre);
    } catch (error) {
        console.error("Error cargando artistas:", error);
    }
});

const artistasFiltrados = computed(() => {
    if (busqueda.value === "") return []; 

    const buscar = busqueda.value.toLowerCase();

    return listarArtistas.value.filter(artista => {
        const yaIntentado = intentos.value.some(i => i.id_artista === artista.id_artista);
        
        // Buscador inteligente por inicio de palabras
        const palabras = artista.nombre.toLowerCase().split(' ');
        const coincide = palabras.some(palabra => palabra.startsWith(buscar));

        return coincide && !yaIntentado;
    });
});

const seleccionar = (artista) => {
    intentos.value.unshift(artista);
    busqueda.value = "";
}

const obtenerClasePremios = (intentoPremios, secretoPremios) => {
    if (!intentoPremios || !secretoPremios) return 'fallo';
    if (intentoPremios === secretoPremios) return 'acierto';

    const arrayIntento = String(intentoPremios).split(',').map(p => p.trim().toLowerCase());
    const arraySecreto = String(secretoPremios).split(',').map(p => p.trim().toLowerCase());

    const coincidencias = arrayIntento.filter(premio => arraySecreto.includes(premio));

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
        
        <RouterLink to="/" class="btn-volver" title="Volver a Inicio">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </RouterLink>

        <h1>SongDle - Artista</h1><br>
        
        <div class="buscador-wrapper">
            <input 
                type="text" 
                v-model="busqueda" 
                placeholder="Escribe el nombre de un artista..."
                class="input-buscador"
            >
            
            <ul v-if="artistasFiltrados.length > 0" class="lista-resultados">
                <li 
                    v-for="artist in artistasFiltrados" 
                    :key="artist.id_artista" 
                    @click="seleccionar(artist)"
                    class="item-lista"
                > 
                    {{ artist.nombre }}
                </li>
            </ul>
        </div>

        <p v-if="artistasFiltrados.length === 0 && busqueda !== ''" class="no-results">
            No se encontraron coincidencias
        </p>

        <div class="historial-intentos">
            <div v-for="(intento, index) in intentos" :key="intento.id_artista" class="fila-comparacion">
                
                <div class="caja-dato" :class="intento.nombre === artistSecreto.nombre ? 'acierto' : 'fallo'">
                    <small>Nombre</small><br>
                    {{ intento.nombre }}
                </div>

                <div class="caja-dato" :class="intento.pais === artistSecreto.pais ? 'acierto' : 'fallo'">
                    <small>País</small><br>
                    {{ intento.pais || '?' }}
                </div>

                <div class="caja-dato" :class="intento.genero === artistSecreto.genero ? 'acierto' : 'fallo'">
                    <small>Género</small><br>
                    {{ intento.genero }}
                </div>

                <div class="caja-dato" :class="intento.debut === artistSecreto.debut ? 'acierto' : 'fallo'">
                    <small>Debut</small><br>
                    {{ intento.debut }}
                    <span v-if="intento.debut < artistSecreto.debut" class="flecha">
                        <svg class="icono-flecha" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                    </span>
                    <span v-else-if="intento.debut > artistSecreto.debut" class="flecha">
                        <svg class="icono-flecha" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                    </span>
                </div>

                <div class="caja-dato" :class="intento.cantidad_albumes === artistSecreto.cantidad_albumes ? 'acierto' : 'fallo'">
                    <small>Álbumes</small><br>
                    {{ intento.cantidad_albumes }}
                    <span v-if="intento.cantidad_albumes < artistSecreto.cantidad_albumes" class="flecha">
                        <svg class="icono-flecha" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                    </span>
                    <span v-else-if="intento.cantidad_albumes > artistSecreto.cantidad_albumes" class="flecha">
                        <svg class="icono-flecha" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                    </span>
                </div>

                <div class="caja-dato" :class="intento.oyentes_mensuales === artistSecreto.oyentes_mensuales ? 'acierto' : 'fallo'">
                    <small>Oyentes</small><br>
                    {{ intento.oyentes_mensuales }}
                    <span v-if="intento.oyentes_mensuales < artistSecreto.oyentes_mensuales" class="flecha">
                        <svg class="icono-flecha" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                    </span>
                    <span v-else-if="intento.oyentes_mensuales > artistSecreto.oyentes_mensuales" class="flecha">
                        <svg class="icono-flecha" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                    </span>
                </div>

                <div class="caja-dato" :class="obtenerClasePremios(intento.premios, artistSecreto.premios)">
                    <small>Premios</small><br>
                    {{ intento.premios }}
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

h1 {
    text-align: center;
    font-family: 'Dela Gothic One', cursive;
    font-size: 3rem;
    margin-bottom: 10px;
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
.item-lista:hover { background-color: #1a1f2e; color: #ffffff; }

.lista-resultados::-webkit-scrollbar { width: 8px; }
.lista-resultados::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
.lista-resultados::-webkit-scrollbar-thumb:hover { background: #ec4899; }

.no-results {
    text-align: center;
    color: #ec4899;
    font-style: italic;
    margin-top: -20px;
    margin-bottom: 30px;
}

.historial-intentos {
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-width: 1000px;
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
}

.caja-dato small {
    font-weight: 600;
    font-size: 0.7em;
    text-transform: uppercase;
    margin-bottom: 8px;
    color: #94a3b8;
    letter-spacing: 1px;
}

.flecha {
    display: inline-block;
    margin-top: 6px;
    margin-left: 4px;
    vertical-align: middle;
}

.icono-flecha {
    width: 18px;
    height: 18px;
    stroke: currentColor; 
}

.acierto { background-color: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.4); color: #4ade80; }
.fallo { background-color: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171; }
.masomenos { background-color: rgba(234, 179, 8, 0.1); border: 1px solid rgba(234, 179, 8, 0.4); color: #facc15; }

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>