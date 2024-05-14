/*=============== SHOW MENU ===============*/
const showMenu = (toggleId, navId) => {
  const toggle = document.getElementById(toggleId)
  const nav = document.getElementById(navId)

  toggle.addEventListener('click', () => {
    nav.classList.toggle('show-menu')
    toggle.classList.toggle('show-icon')
  })
}

// TODO: 'nav-menu' id is not being found
showMenu('nav-toggle', 'nav-menu')
