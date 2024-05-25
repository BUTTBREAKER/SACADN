<nav class="navbar navbar-expand-lg py-0">
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
