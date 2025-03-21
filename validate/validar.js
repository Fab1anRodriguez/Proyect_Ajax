const form = document.getElementById('form1');
const inputs = document.querySelectorAll('#form1 input');

const expressiones = {
    username: /^[a-zA-ZÀ-ÿ\s]{1,50}$/,
    email: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/
}

const errorMensajes = {
    username: 'El usuario solo puede contener letras y espacios (máximo 50 caracteres)',
    email: 'El correo debe tener un formato válido (ejemplo@dominio.com)',
    password: 'La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número'
}

const validateForm = (e) => {
    switch (e.target.name){
        case "username":
            state(expressiones.username, e.target, 'username', errorMensajes.username);
        break;
        case "email":
            state(expressiones.email, e.target, 'email', errorMensajes.email);
        break;
        case "password":
            state(expressiones.password, e.target, 'password', errorMensajes.password);
        break;
    }
}

const state = (expressiones, input, x, message) => {
    const errorElement = document.querySelector(`#x_${x} .x_typerror`);

    if(expressiones.test(input.value)){
        document.getElementById(`x_${x}`).classList.remove('x_grupo-incorrecto');
        document.getElementById(`x_${x}`).classList.add('x_grupo-correcto');
        document.querySelector(`#x_${x} i`).classList.remove('bi-exclamation-circle-fill');
        document.querySelector(`#x_${x} i`).classList.add('bi-check-circle-fill');
        errorElement.classList.remove('x_typerror-block');
        campos[x] = true;
    } 
    else {
        document.getElementById(`x_${x}`).classList.add('x_grupo-incorrecto');
        document.getElementById(`x_${x}`).classList.remove('x_grupo-correcto');
        document.querySelector(`#x_${x} i`).classList.add('bi-exclamation-circle-fill');
        document.querySelector(`#x_${x} i`).classList.remove('bi-check-circle-fill');
        errorElement.textContent = message;
        errorElement.classList.add('x_typerror-block');
        campos[x] = false;
    }
}

inputs.forEach((input) => {
    input.addEventListener('keyup', validateForm);
    input.addEventListener('blur', validateForm);
});