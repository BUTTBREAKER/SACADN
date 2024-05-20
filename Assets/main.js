// Animations
const registerButton = document.getElementById('register')
const loginButton = document.getElementById('login')
const container = document.getElementById('container')

if (registerButton) {
  registerButton.addEventListener('click', () => {
    container.classList.add('right-panel-active')
  })
}

loginButton.addEventListener('click', () => {
  container.classList.remove('right-panel-active')
})

// Función para mostrar error
function showError(input, message) {
  const formControl = input.parentElement
  const small = formControl.querySelector('.error-message')

  formControl.className = 'form-control error'
  small.innerText = message
}

// Función para mostrar éxito
function showSuccess(input) {
  const formControl = input.parentElement
  const small = formControl.querySelector('.error-message')

  formControl.className = 'form-control success'
  small.innerText = ''
}

// Función para verificar la longitud de un campo
function checkLength(input, minLength, maxLength) {
  const value = input.value.trim()

  if (value.length < minLength || value.length > maxLength) {
    showError(
      input,
      `*${getFieldName(input)} debe tener entre ${minLength} y ${maxLength} caracteres.`
    )

    return false
  }

  showSuccess(input)

  return true
}

// Obtener nombre del campo
function getFieldName(input) {
  return input.id.charAt(0).toUpperCase() + input.id.slice(1)
}

// Validar formulario al enviar
function validateForm(formId, fields, numAdministradores) {
  const form = document.getElementById(formId)

  if (!form) {
    return
  }

  form.addEventListener('submit', e => {
    e.preventDefault()

    let isValid = true

    for (const field of fields) {
      const input = document.getElementById(field.id)

      if (!checkLength(input, field.minLength, field.maxLength)) {
        isValid = false
      }
    }

    if (isValid) {
      // Verificar si se ha alcanzado el límite de administradores
      // Supongamos que el límite es 5
      const limiteAdministradores = 5 // Límite de administradores permitidos

      // Si se ha alcanzado el límite, cambiar el valor del campo oculto a 'U' (usuario)
      if (numAdministradores >= limiteAdministradores) {
        document.getElementById('rol').value = 'U'
      }

      form.submit()
    }
  })
}

// Definir campos y sus restricciones
const registerFields = [
  { id: 'registerUsuario', minLength: 4, maxLength: 20 },
  { id: 'nombre', minLength: 3, maxLength: 50 },
  { id: 'apellido', minLength: 3, maxLength: 50 },
  { id: 'registerCedula', minLength: 7, maxLength: 11 },
  { id: 'registerContrasena', minLength: 4, maxLength: 20 }
]

const loginFields = [
  { id: 'loginUsuario', minLength: 4, maxLength: 20 },
  { id: 'loginContrasena', minLength: 4, maxLength: 20 }
]

// Obtener el número de administradores desde el contenedor principal
const numAdministradores = Number.parseInt(
  container.getAttribute('data-num-administradores')
)

// Validar formularios
validateForm('registerForm', registerFields, numAdministradores)
validateForm('loginForm', loginFields, numAdministradores)
