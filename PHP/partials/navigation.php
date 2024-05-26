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
      <ul class="navbar-nav ms-auto flex-wrap">
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
            Estudiantes
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="estudiantes.php">
                Ver estudiantes
              </a>
            </li>
            <?php if ($role === 'A') : ?>
              <li>
                <a class="dropdown-item" href="nuevo_estudiante.php">
                  Registrar estudiante
                </a>
              </li>
            <?php endif ?>
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
        <li class="nav-item dropdown">
          <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            Años y Secciones
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="javascript:">
                Ver años y secciones
              </a>
            </li>
            <?php if ($role === 'A') : ?>
              <li>
                <a class="dropdown-item" href="javascript:">
                  Aperturar sección
                </a>
              </li>
            <?php endif ?>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            Materias
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="materias.php">
                Ver materias
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="secciones.php">
                Ver secciones
              </a>
            </li>
            <?php if ($role === 'A') : ?>
              <li>
                <a class="dropdown-item" href="nueva_materia.php">
                  Aperturar materia
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="asignar-materias.php">
                  Asignar materia a años
                </a>
              </li>
            <?php endif ?>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            Períodos
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="periodos.php">
                Ver períodos
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="nuevo_periodo.php">
                Aperturar período
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="nuevo_momento.php">
                Asignar momentos<br />por período
              </a>
            </li>
          </ul>
        </li>
        <?php if ($role === 'A') : ?>
          <li class="nav-item dropdown">
            <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              Seguridad
            </button>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="respaldo.php">
                  Respaldar
                </a>
              </li>
              <?php if (file_exists(__DIR__ . '/../backups/full_backup.mysql.sql')) : ?>
                <li>
                  <a class="dropdown-item" href="restauracion.php">
                    Restaurar
                  </a>
                </li>
              <?php else : ?>
                <li>
                  <a class="dropdown-item disabled">
                    Restaurar
                  </a>
                </li>
              <?php endif ?>
              <li>
                <a class="dropdown-item" href="moduloa.php">
                  Control de usuarios
                </a>
              </li>
            </ul>
          </li>
        <?php endif ?>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item dropdown me-md-3">
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
        <li class="nav-item">
          <a class="nav-link" href="./salir.php">Salir</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
