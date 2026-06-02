<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const usuario = ref(null);

onMounted(async () => {
    try {
        const userStorage = localStorage.getItem('user');
        const userId = userStorage ? JSON.parse(userStorage).id_usuario : null;
        const token  = localStorage.getItem('token'); 

        if (userId && token) {
            const respuesta = await axios.get(`http://localhost:8080/api/users/${userId}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            
            usuario.value = respuesta.data;
            localStorage.setItem('user', JSON.stringify(respuesta.data));
        }
    } catch (error) {
        console.error("Error al cargar el perfil:", error);
    }
});

const formatearFecha = (fechaCruda) => {
    if (!fechaCruda) return '';
    const fecha = new Date(fechaCruda);
    
    return fecha.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: 'long', 
        year: 'numeric'
    });
}
</script>

<template>
<div class="profile-container">
    <div class="profile-card">
        
        <div class="header-center">
            <h1 class="logo">SongDle</h1>
            <p class="subtitle">PERFIL DE JUGADOR</p>
        </div>
        
        <div v-if="usuario" class="profile-content">
            
            <div class="avatar-section">
                <div class="avatar-wrapper">
                    <img 
                        v-if="usuario.profile_img" 
                        :src="`http://localhost:8080/storage/${usuario.profile_img}`" 
                        alt="Foto de perfil" 
                        class="avatar-img"
                    >
                    <img 
                        v-else 
                        :src="`https://ui-avatars.com/api/?name=${usuario.name}&background=random&color=fff&size=150`" 
                        alt="Avatar por defecto"
                        class="avatar-img"
                    >
                </div>
                <h2 class="username">{{ usuario.name }}</h2>
            </div>

            <div class="info-section">
                <div class="info-group">
                    <label>Correo electrónico</label>
                    <p class="info-value">{{ usuario.email }}</p>
                </div>
                
                <div class="info-group">
                    <label>Teléfono</label>
                    <p class="info-value">{{ usuario.phone ? usuario.phone : 'No especificado' }}</p>
                </div>
                
                <div class="info-group">
                    <label>Antigüedad</label>
                    <p class="info-value highlight-text">Miembro desde el {{ formatearFecha(usuario.registration_date) }}</p>
                </div>
            </div>
            
            <RouterLink to="/" class="play-btn-gradient" style="display: block; text-align: center; text-decoration: none; box-sizing: border-box;">
                VOLVER AL JUEGO
            </RouterLink>

        </div>

        <div v-else class="loading-state">
            <p>Cargando datos del perfil...</p>
        </div>

    </div>
</div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Montserrat:wght@400;500;600;700&display=swap');

.profile-container {
    font-family: 'Montserrat', sans-serif;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: radial-gradient(circle at top, #141824 0%, #0a0c13 100%);
    color: #f1f5f9;
    padding: 40px 20px;
}

.profile-card {
    width: 100%;
    max-width: 500px;
    background-color: #11141d;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 3.5rem 2.5rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
    animation: fadeUp 0.5s ease-out forwards;
}

.header-center {
    text-align: center;
    margin-bottom: 2rem;
}

.logo {
    font-family: 'Dela Gothic One', cursive;
    font-size: 3.5rem;
    margin: 0;
    line-height: 1;
    background: linear-gradient(to right, #ffffff, #d8b4fe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 4px 20px rgba(171, 71, 188, 0.3);
}

.subtitle {
    font-size: 0.8rem;
    letter-spacing: 3px;
    margin-top: 0.5rem;
    color: #64748b;
    font-weight: 600;
}

.profile-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.avatar-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.avatar-wrapper {
    padding: 4px;
    background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
    border-radius: 50%;
    box-shadow: 0 10px 25px -5px rgba(236, 72, 153, 0.4);
}

.avatar-img {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    border: 4px solid #11141d;
}

.username {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    color: #f8fafc;
}

.info-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-group {
    background-color: #0b0d14;
    border: 1px solid #334155;
    padding: 1.2rem;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    transition: all 0.3s ease;
}

.info-group:hover {
    border-color: #475569;
    transform: translateY(-2px);
}

.info-group label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.info-value {
    font-size: 1.05rem;
    color: #f1f5f9;
    font-weight: 500;
    margin: 0;
}

.highlight-text {
    color: #d8b4fe;
    font-weight: 600;
}

.loading-state {
    text-align: center;
    color: #94a3b8;
    padding: 2rem 0;
    font-weight: 500;
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
    margin-top: 1rem;
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

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>