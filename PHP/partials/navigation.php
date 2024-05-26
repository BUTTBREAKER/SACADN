<nav class="navbar navbar-expand-lg py-0" data-bs-theme="dark">
  <div class="container-fluid px-md-5">
    <a class="navbar-brand" href="./BienvenidoA.php">
      <img src="../favicon.ico" width="75" />
      SACADN
    </a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navigationMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navigationMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 1em; height: 1em" fill="currentColor" viewBox="0 0 512 512">
              <path d="M448 256c0-106-86-192-192-192V448c106 0 192-86 192-192zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z" />
            </svg>
            <span class="d-lg-none ms-2">Alternar tema</span>
          </a>
          <ul class="dropdown-menu">
            <li>
              <button class="dropdown-item d-flex align-items-center" data-bs-theme-value="light">
                <i class="ri-sun-fill"></i>
                <span class="ms-2">Claro</span>
              </button>
            </li>
            <li>
              <button class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark">
                <i class="ri-moon-clear-fill"></i>
                <span class="ms-2">Oscuro</span>
              </button>
            </li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            Notas
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="notas.php">Ver notas</a>
            </li>
            <li>
              <a class="dropdown-item" href="notas.php">Cargar notas</a>
            </li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            Representantes
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="representantes.php">
                Ver representantes
              </a>
            </li>
            <?php if ($role === 'A') : ?>
              <li>
                <a class="dropdown-item" href="nuevo_representante.php">
                  Registrar representante
                </a>
              </li>
            <?php endif ?>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            Profesores
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="profesores.php">
                Ver profesores
              </a>
            </li>
            <?php if ($role === 'A') : ?>
              <li>
                <a class="dropdown-item" href="nuevo_profesor.php">
                  Registrar profesor
                </a>
              </li>
            <?php endif ?>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="./salir.php">Salir</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- <header class="header">
  <nav class="nav">
    <div class="nav__data">
      <div class="nav__toggle" id="nav-toggle">
        <i class="ri-menu-line nav__burger"></i>
        <i class="ri-close-line nav__close"></i>
      </div>
    </div>
    <ul class="nav__list">
      <li class="dropdown__item">
        <div class="nav__link">
          Años Escolares
          <i class="ri-arrow-down-s-line dropdown__arrow"></i>
        </div>
        <ul class="dropdown__menu">
          <li>
            <a href="#" class="dropdown__link">
              <i class="ri-pie-chart-line"></i>
              Año Escolar-Lapso
            </a>
          </li>
          <li>
            <a href="Periodos.php" class="dropdown__link">
              <i class="ri-arrow-up-down-line"></i>
              Ver Periodo
            </a>
          </li>
          <li>
            <a href="nuevo_periodo.php" class="dropdown__link">
              <i class="ri-arrow-up-down-line"></i>
              Crear periodo
            </a>
          </li>
          <li>
            <a href="nuevo_momento.php" class="dropdown__link">
              <i class="ri-arrow-up-down-line"></i>
              Crear momento
            </a>
          </li>
        </ul>
      </li>
      <li class="dropdown__item">
        <div class="nav__link">
          Asignaturas
          <i class="ri-arrow-down-s-line dropdown__arrow"></i>
        </div>
        <ul class="dropdown__menu">
          <li>
            <a href="Asignaturas.php" class="dropdown__link">
              <i class="ri-pie-chart-line"></i>
              Ver Asignaturas
            </a>
          </li>
          <li>
            <a href="./asignar-materias.php" class="dropdown__link">
              <i class="ri-pie-chart-line"></i>
              Asignar materias
            </a>
          </li>
          <li>
            <a href="./materias.php" class="dropdown__link">
              <i class="ri-arrow-up-down-line"></i>
              Materias
            </a>
          </li>
        </ul>
      </li>
      <li class="dropdown__item">
        <div class="nav__link">
          Profesores
          <i class="ri-arrow-down-s-line dropdown__arrow"></i>
        </div>
        <ul class="dropdown__menu">
          <li>
            <a href="profesores.php" class="dropdown__link">
              <i class="ri-pie-chart-line"></i>
              Lista de Profesores
            </a>
            <?php if ($role === 'A') : ?>
              <a href="nuevo_profesor.php" class="dropdown__link">
                <i class="ri-pie-chart-line"></i>
                Registrar Profesor
              </a>
            <?php endif ?>
          </li>
        </ul>
      </li>
      <li class="dropdown__item">
        <div class="nav__link">
          Estudiantes
          <i class="ri-arrow-down-s-line dropdown__arrow"></i>
        </div>
        <ul class="dropdown__menu">
          <li>
            <a href="Estudiantes.php" class="dropdown__link">
              <i class="ri-pie-chart-line"></i>
              Lista De Estudiantes
            </a>
            <?php if ($role === 'A') : ?>
              <a href="nuevo_estudiante.php" class="dropdown__link">
                <i class="ri-pie-chart-line"></i>
                Registrar Estudiante
              </a>
            <?php endif ?>
          </li>
        </ul>
      </li>
      <?php if ($role === 'A') : ?>
        <li class="dropdown__item">
          <div class="nav__link">
            Configuración
            <i class="ri-arrow-down-s-line dropdown__arrow"></i>
          </div>
          <ul class="dropdown__menu">
            <li>
              <a href="respaldo.php" class="dropdown__link">
                <i class="ri-pie-chart-line"></i>
                Respaldo
              </a>
            </li>
            <li>
              <a href="restauracion.php" class="dropdown__link">
                <i class="ri-arrow-up-down-line"></i>
                Restauración
              </a>
            </li>
            <li>
              <a href="moduloA.php" class="dropdown__link">
                <i class="ri-pie-chart-line"></i>
                Control de Usuarios
              </a>
            </li>
          </ul>
        </li>
      <?php endif ?>
      <li class="dropdown__item">
        <div class="nav__link">
          Representantes
          <i class="ri-arrow-down-s-line dropdown__arrow"></i>
        </div>
        <ul class="dropdown__menu">
          <li>
            <a href="Representantes.php" class="dropdown__link">
              <i class="ri-pie-chart-line"></i>
              Lista De Representantes
            </a>
            <?php if ($role === 'A') : ?>
              <a href="nuevo_representante.php" class="dropdown__link">
                <i class="ri-pie-chart-line"></i>
                Registrar Representante
              </a>
            <?php endif ?>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</header> -->
