<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Variables de datos
const usuarios = ref([]);
const newUser = ref();
const name = ref('');
const email = ref('');
const phone = ref('');
const password = ref('');
const profile = ref(null); 

// Variables para los mensajes visuales
const mensajeError = ref('');
const mensajeExito = ref('');

// Función para listar usuarios
const listarUsuarios = async () => {
    try {
        const respuesta = await axios.get('http://localhost:8080/api/users');
        usuarios.value = respuesta.data; 
    } catch (error) {
        console.error("Error al cargar los usuarios:", error);
    }
}

onMounted(() => {
    listarUsuarios();
});

// Función que captura el archivo cuando el usuario lo selecciona
const capturarFoto = (event) => {
    profile.value = event.target.files[0];
}

// Función para crear un usuario
const crearusuario = async () => {
    // Limpiamos mensajes previos
    mensajeError.value = '';
    mensajeExito.value = '';

    // 1. REVISAMOS TODOS LOS USUARIOS UNO POR UNO
    for (const usuario of usuarios.value) {
        if (name.value === usuario.name) {
            mensajeError.value = "El nombre de usuario ya existe.";
            return; 
        } 
        else if (email.value === usuario.email) {
            mensajeError.value = "Este correo electrónico ya está en uso.";
            return; 
        }
    }

    // 2. PREPARAMOS EL PAQUETE CON LA FOTO (FORMDATA)
    try {
        const formData = new FormData();
        formData.append('name', name.value);
        formData.append('email', email.value);
        formData.append('phone', phone.value);
        formData.append('password', password.value);
        formData.append('registration_date', new Date().toISOString().slice(0, 19).replace('T', ' '));
        formData.append('administrador', 0); 
        
        if (profile.value) {
            formData.append('profile_img', profile.value);
        }

        // 3. ENVIAMOS LOS DATOS A LARAVEL
        const respuesta = await axios.post('http://localhost:8080/api/auth/register', formData, {
            headers: {
                'Content-Type': 'multipart/form-data' 
            }
        });
        
        newUser.value = respuesta.data;
        mensajeExito.value = "¡Usuario creado con éxito! Ya puedes iniciar sesión.";
        
        // Limpiamos el formulario tras el éxito
        name.value = '';
        email.value = '';
        phone.value = '';
        password.value = '';
        profile.value = null;

        listarUsuarios();

    } catch (error) {
        console.error("Error al crear el usuario:", error);
        mensajeError.value = "Ha ocurrido un error al conectar con el servidor.";
    }
}
</script>

<template>
<div class="register-container">
    <div class="register-card">
        
        <!-- Cabecera -->
        <div class="header-center">
            <h1 class="logo">SongDle</h1>
            <p class="subtitle">CREAR CUENTA</p>
        </div>

        <div class="register-form">
            <!-- Inputs -->
            <div class="input-group">
                <label>Nombre de usuario</label>
                <input type="text" v-model="name" placeholder="Introduce tu nombre" class="custom-input">
            </div>
            
            <div class="input-group">
                <label>Correo electrónico</label>
                <input type="email" v-model="email" placeholder="ejemplo@correo.com" class="custom-input">
            </div>
            
            <div class="input-group">
                <label>Teléfono</label>
                <input type="number" v-model="phone" placeholder="Tu número de teléfono" class="custom-input">
            </div>
            
            <div class="input-group">
                <label>Contraseña</label>
                <input type="password" v-model="password" placeholder="••••••••" class="custom-input">
            </div>
            
            <div class="input-group">
                <label>Foto de perfil</label>
                <input type="file" @change="capturarFoto" accept="image/*" class="custom-file-input">
            </div>

            <!-- Mensajes de Error y Éxito -->
            <div v-if="mensajeError" class="alert-box error-box">
                <svg xmlns="http://www.w3.org/2000/svg" class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ mensajeError }}</span>
            </div>

            <div v-if="mensajeExito" class="alert-box success-box">
                <svg xmlns="http://www.w3.org/2000/svg" class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ mensajeExito }}</span>
            </div>

            <!-- Botón -->
            <button @click="crearusuario" class="play-btn-gradient">REGISTRARSE</button>
            
            <!-- Enlace volver a login -->
            <div class="login-link">
                ¿Ya tienes cuenta? <RouterLink to="/login" class="link">Inicia sesión aquí</RouterLink>
            </div>
        </div>
        
    </div>
</div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Montserrat:wght@400;500;600;700&display=swap');

.register-container {
    font-family: 'Montserrat', sans-serif;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: radial-gradient(circle at top, #141824 0%, #0a0c13 100%);
    color: #f1f5f9;
    padding: 40px 20px;
}

.register-card {
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
    margin-bottom: 2.5rem;
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

.register-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.input-group label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #cbd5e1;
    margin-left: 5px;
}

.custom-input {
    width: 100%;
    background-color: #0b0d14;
    color: #f8fafc;
    border: 1px solid #334155;
    padding: 1.2rem;
    border-radius: 12px;
    font-family: inherit;
    font-size: 1.05rem;
    outline: none;
    transition: all 0.3s ease;
}

.custom-input:focus {
    border-color: #ec4899;
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.15);
}

.custom-input::placeholder { color: #475569; }

/* Estilo avanzado para el input file */
.custom-file-input {
    width: 100%;
    background-color: #0b0d14;
    color: #94a3b8;
    border: 1px solid #334155;
    padding: 0.8rem;
    border-radius: 12px;
    font-family: inherit;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.custom-file-input::file-selector-button {
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    font-family: inherit;
    font-weight: 600;
    cursor: pointer;
    margin-right: 1rem;
    transition: all 0.3s ease;
}

.custom-file-input::file-selector-button:hover {
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
    transform: translateY(-1px);
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

.login-link {
    text-align: center;
    font-size: 0.9rem;
    color: #94a3b8;
    margin-top: 0.5rem;
}

.link {
    color: #d8b4fe;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}

.link:hover {
    color: #ec4899;
    text-decoration: underline;
}

/* Cajas de Alerta */
.alert-box {
    margin-top: 0.5rem;
    padding: 1rem 1.2rem;
    border-radius: 10px;
    font-size: 0.95rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.error-box {
    background-color: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #f87171;
    animation: shake 0.4s ease-in-out;
}

.success-box {
    background-color: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #4ade80;
}

.alert-icon {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
    75% { transform: translateX(-5px); }
}

/* Quitar flechas del input number */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
}
</style>