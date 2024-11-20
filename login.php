<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <div class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a href="index.php" class="navbar-brand"><strong>Bunny Vibes</strong></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">Categoría</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Contáctanos</a>
                        </li>
                    </ul>
                    <a href="" class="btn btn-cesta">Cesta</a>
                
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container mt-5">
            <div class="login-container">
                <h2 class="text-center">Iniciar Sesión</h2>
                <form>
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="usuario" placeholder="Ingresa tu usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="contraseña" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="contraseña" placeholder="Ingresa tu contraseña" required>
                    </div>
                    <div class="text-center">
                        <a href="#" class="text-decoration-none">¿Has olvidado la contraseña?</a>
                    </div>
                    <button type="submit" class="btn btn-cesta w-100 mt-3">Ingresar</button>
                </form>
                <div class="line"></div>
                <div class="text-center">
                    <p>No tienes cuenta? <a href="registro.php" class="text-decoration-none">Regístrate aquí</a></p>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
