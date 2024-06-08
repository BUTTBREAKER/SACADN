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
            <i class="ri-parent-line"></i>
            Representantes
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="representantes.php">
                <i class="ri-table-line"></i>
                Ver representantes
              </a>
            </li>
            <?php if ($role === 'A') : ?>
              <li>
                <a class="dropdown-item" href="nuevo_representante.php">
                  <i class="ri-user-add-line"></i>
                  Registrar representante
                </a>
              </li>
            <?php endif ?>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            <i class="ri-group-line"></i>
            Estudiantes
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="estudiantes.php">
                <i class="ri-table-line"></i>
                Ver estudiantes
              </a>
            </li>
            <?php if ($role === 'A') : ?>
              <li>
                <a class="dropdown-item" href="nuevo_estudiante.php">
                  <i class="ri-user-add-line"></i>
                  Inscribir estudiante
                </a>
              </li>
            <?php endif ?>
            <li>
              <a class="dropdown-item" href="notas.php">
                <i class="ri-sticky-note-line"></i>
                Ver notas
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="#">
                <i class="ri-git-repository-commits-line"></i>
                Cargar notas
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            <i class="ri-team-line"></i>
            Profesores
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="profesores.php">
                <i class="ri-table-line"></i>
                Ver profesores
              </a>
            </li>
            <?php if ($role === 'A') : ?>
              <li>
                <a class="dropdown-item" href="nuevo_profesor.php">
                  <i class="ri-user-add-line"></i>
                  Registrar profesor
                </a>
              </li>
            <?php endif ?>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            <i class="ri-booklet-line"></i>
            Materias
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="materias.php">
                <i class="ri-table-line"></i>
                Ver materias
              </a>
            </li>
            <?php if ($role === 'A') : ?>
              <li>
                <a class="dropdown-item" href="nueva_materia.php">
                  <i class="ri-add-large-line"></i>
                  Aperturar materia
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="asignar-materias.php">
                  <i class="ri-guide-line"></i>
                  Asignar materia a años
                </a>
              </li>
            <?php endif ?>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            <i class="ri-barricade-line"></i>
            Años y Secciones
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="javascript:">
                <i class="ri-table-line"></i>
                Ver años y secciones
              </a>
            </li>
            <?php if ($role === 'A') : ?>
              <li>
                <a class="dropdown-item" href="nuevo_curso.php">
                  <i class="ri-database-line"></i>
                  Aperturar sección
                </a>
              </li>
            <?php endif ?>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            <i class="ri-calendar-line"></i>
            Períodos
          </button>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="periodos.php">
                <i class="ri-calendar-view"></i>
                Ver períodos
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="nuevo_periodo.php">
                <i class="ri-add-large-line"></i>
                Aperturar período
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="nuevo_momento.php">
                <i class="ri-guide-line"></i>
                Asignar Lapsos<br />por período
              </a>
            </li>
          </ul>
        </li>
        <?php if ($role === 'A') : ?>
          <li class="nav-item dropdown">
            <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <i class="ri-lock-line"></i>
              Seguridad
            </button>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="respaldo.php">
                  <i class="ri-upload-line"></i>
                  Respaldar
                </a>
              </li>
              <?php if (file_exists(__DIR__ . '/../backups/full_backup.mysql.sql')) : ?>
                <li>
                  <a class="dropdown-item" href="restauracion.php">
                    <i class="ri-download-line"></i>
                    Restaurar
                  </a>
                </li>
              <?php else : ?>
                <li>
                  <a class="dropdown-item disabled">
                    <i class="ri-download-line"></i>
                    Restaurar
                  </a>
                </li>
              <?php endif ?>
              <li>
                <a class="dropdown-item" href="moduloa.php">
                  <i class="ri-team-line"></i>
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
            <i class="ri-contrast-line"></i>
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
          <a class="nav-link d-flex" href="./salir.php">
            <i class="ri-logout-circle-line me-2"></i>
            Salir
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
