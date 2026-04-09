<script setup>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute()
const busqueda = ref("");
const listarCanciones = ref([]);
const cancionSecreta = ref(null);

const intentos = ref([]);

onMounted(async () => {
    try {
        const id = route.params.id
        let url = 'http://localhost:8080/api/songs'
        if (id) {
            url = url +'?id_artista='+id
        }
        const cargar = await axios.get(url);
        listarCanciones.value = cargar.data;

        const aleatorio = Math.floor(Math.random() * listarCanciones.value.length);
        cancionSecreta.value = listarCanciones.value[aleatorio];
        
        console.log("🤫 La canción secreta es:", cancionSecreta.value.titulo);
    } catch (error) {
        console.error("Error cargando canciones:", error);
    }
});

const cancionesFiltradas = computed(() => {
    if (busqueda.value === "") return []; 

    const buscar = busqueda.value.toLowerCase();

    return listarCanciones.value.filter(cancion => {
        // Evitamos que aparezcan en el buscador canciones que ya intentamos
        const yaIntentado = intentos.value.some(i => i.id_song === cancion.id_song);
        
        // Separamos el título de la canción en una lista de palabras
        const palabras = cancion.titulo.toLowerCase().split(' ');
        
        // Comprobamos si ALGUNA de esas palabras EMPIEZA con lo que se ha escrito
        const coincide = palabras.some(palabra => palabra.startsWith(buscar));

        return coincide && !yaIntentado;
    });
});

const seleccionar = (cancion) => {
    intentos.value.unshift(cancion);
    busqueda.value = "";
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
        <h1>SongDle - Canciones</h1><br>
        
        <div class="buscador-wrapper">
            <input 
                type="text" 
                v-model="busqueda" 
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
                
                <div class="caja-dato" :class="intento.titulo === cancionSecreta.titulo ? 'acierto' : 'fallo'">
                    <small>Título</small><br>
                    {{ intento.titulo }}
                </div>

                <div class="caja-dato" :class="intento.id_artista === cancionSecreta.id_artista ? 'acierto' : 'fallo'">
                    <small>Artista</small><br>
                    {{ intento.artist?.nombre || 'Desconocido' }}
                </div>

                <div class="caja-dato" :class="intento.pais === cancionSecreta.pais ? 'acierto' : 'fallo'">
                    <small>País</small><br>
                    {{ intento.pais }}
                </div>

                <div class="caja-dato" :class="obtenerClaseMultiple(intento.genero, cancionSecreta.genero)">
                    <small>Género</small><br>
                    {{ intento.genero }}
                </div>

                <div class="caja-dato" :class="intento.anio === cancionSecreta.anio ? 'acierto' : 'fallo'">
                    <small>Año</small><br>
                    {{ intento.anio }}
                    <span v-if="intento.anio < cancionSecreta.anio" class="flecha">
                        <svg class="icono-flecha" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                    </span>
                    <span v-else-if="intento.anio > cancionSecreta.anio" class="flecha">
                        <svg class="icono-flecha" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                    </span>
                </div>

                <div class="caja-dato" :class="intento.reproducciones === cancionSecreta.reproducciones ? 'acierto' : 'fallo'">
                    <small>Reproducciones</small><br>
                    {{ intento.reproducciones.toLocaleString() }}
                    <span v-if="intento.reproducciones < cancionSecreta.reproducciones" class="flecha">
                        <svg class="icono-flecha" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                    </span>
                    <span v-else-if="intento.reproducciones > cancionSecreta.reproducciones" class="flecha">
                        <svg class="icono-flecha" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                    </span>
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
    letter-spacing: normal;
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

.caja-dato:hover { transform: translateY(-2px); }

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

.acierto small { color: rgba(74, 222, 128, 0.8); }
.fallo small { color: rgba(248, 113, 113, 0.8); }
.masomenos small { color: rgba(250, 204, 21, 0.8); }

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>