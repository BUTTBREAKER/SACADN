<header class="header">
  <nav class="nav">
    <div class="nav__data">
      <a href="#" class="nav__logo">
        SACADN
        <img src="../favicon.ico" />
      </a>
      <div class="nav__toggle" id="nav-toggle">
        <i class="ri-menu-line nav__burger"></i>
        <i class="ri-close-line nav__close"></i>
      </div>
    </div>
    <ul class="nav__list">
      <li class="dropdown__item">
        <div class="nav__link">
          Notas
          <i class="ri-arrow-down-s-line dropdown__arrow"></i>
        </div>
        <ul class="dropdown__menu">
          <li>
            <a href="Notas.php" class="dropdown__link">
              <i class="ri-pie-chart-line"></i>
              Ver Notas
            </a>
          </li>
          <li>
            <a href="Periodos.php" class="dropdown__link">
              <i class="ri-arrow-up-down-line"></i>
              Consultar Notas
            </a>
          </li>
        </ul>
      </li>
      <li class="dropdown__item">
        <div class="nav__link">
          Años Escolares
          <i class="ri-arrow-down-s-line dropdown__arrow"></i>
        </div>
        <ul class="dropdown__menu">
          <li>
            <a href="#" class="dropdown__link">
              <i class="ri-pie-chart-line"></i>
              Año Escolar-Semestre
            </a>
          </li>
          <li>
            <a href="Periodos.php" class="dropdown__link">
              <i class="ri-arrow-up-down-line"></i>
              Ver Periodo
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
      <li>
        <a href="salir.php" class="nav__link">Salir</a>
      </li>
    </ul>
  </nav>
</header>
