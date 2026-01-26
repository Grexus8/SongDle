<template>
    <main>
        <nav>
            <h1 id="titulo">SongDle</h1>
            <button id="perfil">
                <img src="../img/user_icon.png" alt="Usuario">
            </button>
        </nav>

        <div id="Games_Space">
            <div class="Games_Select">
                <p>Cancion</p>
            </div>

            <div class="Games_Select">
                <p>Artista</p>
            </div>

            <div class="Games_Select">
                <p>Album</p>
            </div>

            

        </div>
    </main>


</template>

<style scoped>
/* Fondo negro y texto blanco */
main {
  background-color: #000;
  color: #fff;
  min-height: 100vh;
  font-family: Arial, sans-serif;
  padding: 20px;
}

/* Navbar: título centrado, botón a la derecha */
nav {
      background-color: #000;

  display: flex;
  justify-content: center; /* centra el título horizontalmente */
  align-items: center;
  position: relative;
  margin-bottom: 40px;
}

#titulo {
  font-size: 2rem;
  text-align: center;
  margin: 0;
}

/* Botón de perfil pequeño a la derecha */
#perfil {

  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  padding: 5px;
}

#perfil img {
  width: 30px; /* tamaño pequeño */
  height: 30px;
  object-fit: cover;
  border-radius: 50%;
}

/* Contenedor de juegos */
#Games_Space {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
}

/* Clase Games_Select con borde blanco, sombra y borde redondeado */
.Games_Select {
  background-color: #000;
  border: 2px solid #fff;
  border-radius: 15px;
  box-shadow: 0 0 10px #fff;
  padding: 30px 50px;
  width: 80%;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: left;
}

/* Hover efecto */
.Games_Select:hover {
  background-color: #111; /* ligero cambio de fondo */
  box-shadow: 0 0 20px #fff; /* sombra más intensa */
  transform: scale(1.05); /* pequeño zoom */
}

/* Texto dentro de Games_Select */
.Games_Select p {
  margin: 0;
  font-size: 1.2rem;
}
</style>
