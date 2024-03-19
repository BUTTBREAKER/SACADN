</body>
<style>
    body {
        margin: 0;
        padding: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .content {
        flex: 1;
    }

    .footer {
        width: 50%;
        background-color: transparent;
        color: #333;
        padding: 20px;
        text-align: right;
        position: fixed;
        bottom: 1em;
        right: 1em;
        animation: blink 2000ms infinite;
        animation-direction: alternate;
    }

    .footer p {
        margin: 0;
        font-size: 16px;
        font-weight: bold;
    }

    @keyframes blink {
      from {
        text-shadow: 0 0 10px rgba(0, 0, 0, .25);
      }

      to {
        text-shadow: 0 0 10px #0756f299;
      }
    }
</style>
</head>
<body>

<div class="content">
    <!-- Contenido de la página aquí -->
</div>

<footer class="footer">
    <div class="container">
        <p>Registro y Control de Notas para Estudiantes</p>
    </div>
</footer>
</body>
</html>
