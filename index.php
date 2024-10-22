<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bunny Vibes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <div class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a href="#" class="navbar-brand">
                    <strong>Bunny Vibes</strong>
                </a>
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
                    <a href="#" class="btn btn-cesta">Cesta</a>
                    <a href="login.php" class="btn btn-cesta ms-2">Iniciar Sesión</a> 
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <article class="col">
                    <div class="card shadow-sm tarjeta-producto">
                        <img src="imagenes/zap1.png" alt="Adidas campu X Bad Bunny">
                        <div class="card-body">
                            <h5 class="card-title card-title-rosa">Adidas Campu X Bad Bunny</h5>
                            <p class="card-text card-text_letra">$300.00</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <!-- Botón de Detalles -->
                                    <a href="detalles_producto.php" class="btn btn-rosa" role="button">Detalles</a>
                                </div>
                                <!-- Botón de Agregar a la Cesta -->
                                <a href="agregar_cesta.php" class="btn btn-rosa" role="button">Agregar a la Cesta</a>
                            </div>
                        </div>
                    </div>
                </article>
                
        
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
