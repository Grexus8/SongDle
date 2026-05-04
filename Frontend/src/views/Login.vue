<script setup>
import { ref } from 'vue';
import router from '../routes/routes';
import axios from 'axios';

const nombre = ref('');
const contrasena = ref('');
const mensajeError = ref('');

const iniciarSesion = async () => {
    mensajeError.value = '';

    try {
        const respuesta = await axios.post('http://localhost:8080/api/auth/login', {
            name: nombre.value, 
            password: contrasena.value
        });
        
        router.push('/'); 

    } catch (error) {
        console.error("Error al iniciar sesión", error);
        
        if (error.response && error.response.status === 401) {
            mensajeError.value = 'Usuario o contraseña incorrectos.';
        } else {
            mensajeError.value = 'Error al conectar con el servidor.';
        }
    }
};
</script>

<template>
  <div class="login-container">
    <div class="login-card">
      <div class="header-center">
        <h1 class="logo">SongDle</h1>
        <p class="subtitle">INICIAR SESIÓN</p>
        <p class="welcome-text">Conéctate y pon a prueba tu cultura musical.</p>
      </div>

      <form @submit.prevent="iniciarSesion" class="login-form">
        
        <div class="input-group">
          <label>Nombre de usuario</label>
          <div class="input-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <input v-model="nombre" type="text" placeholder="Introduce tu nombre" required class="custom-input">
          </div>
        </div>
        
        <div class="input-group">
          <label>Contraseña</label>
          <div class="input-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <input type="password" v-model="contrasena" placeholder="••••••••" required class="custom-input">
          </div>
        </div>
        
        <button type="submit" class="play-btn-gradient">ENTRAR</button>
      </form>

      <!-- Mensaje de error mejorado -->
      <div v-if="mensajeError" class="error-box">
        <svg xmlns="http://www.w3.org/2000/svg" class="error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <span>{{ mensajeError }}</span>
      </div>

      <!-- Enlace para registrarse -->
      <div class="register-link">
        ¿Aún no tienes equipo? <RouterLink to="/register" class="link">Regístrate aquí</RouterLink>
      </div>
      
    </div>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Montserrat:wght@400;500;600;700&display=swap');

.login-container {
  font-family: 'Montserrat', sans-serif;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: radial-gradient(circle at top, #141824 0%, #0a0c13 100%);
  color: #f1f5f9;
  padding: 20px;
}

.login-card {
  width: 100%;
  max-width: 450px;
  background-color: #11141d;
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 16px;
  padding: 3.5rem 2.5rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
  animation: fadeUp 0.5s ease-out forwards;
}

/* --- Logo y Títulos --- */
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

.welcome-text {
  font-size: 0.9rem;
  color: #94a3b8;
  margin-top: 1rem;
  font-weight: 500;
}

/* --- Formulario e Inputs --- */
.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.input-group {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.input-group label {
  font-size: 0.9rem;
  font-weight: 600;
  color: #cbd5e1;
  margin-left: 5px;
}

.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.input-icon {
  position: absolute;
  left: 1.2rem;
  width: 20px;
  height: 20px;
  color: #64748b;
  transition: color 0.3s ease;
}

.custom-input {
  width: 100%;
  background-color: #0b0d14;
  color: #f8fafc;
  border: 1px solid #334155;
  padding: 1.2rem 1.2rem 1.2rem 3.2rem; /* Más padding a la izquierda para el icono */
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

.custom-input:focus + .input-icon, 
.custom-input:not(:placeholder-shown) ~ .input-icon {
  color: #ec4899; /* El icono se pinta de rosa si hay foco o texto */
}

.custom-input::placeholder {
  color: #475569;
}

/* --- Botón Entrar --- */
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

/* --- Enlace de registro --- */
.register-link {
  text-align: center;
  font-size: 0.9rem;
  color: #94a3b8;
  margin-top: 1.5rem;
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

/* --- Caja de Error Estilizada --- */
.error-box {
  margin-top: 1.5rem;
  padding: 1rem 1.2rem;
  background-color: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.3);
  border-radius: 10px;
  color: #f87171;
  font-size: 0.95rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.6rem;
  animation: shake 0.4s ease-in-out;
}

.error-icon {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
}

/* --- Animaciones --- */
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
</style>