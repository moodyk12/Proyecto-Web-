// app.js

function addProducto(id, token) {
    let url = 'clases/cesta.php';
    let formData = new FormData();
    formData.append('id', id);
    formData.append('token', token);

    fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
    }).then(response => response.json())
    .then(data => {
        if (data.ok) {
            let elemento = document.getElementById("num_cart");
            elemento.innerHTML = data.numero;
        }
    });
}

// de la pantalla checkout eliminar cualquier producto
let eliminaModal =document.getElementById('eliminaModal')
eliminaModal.addEventListener('show.bs.modal', function(event){
let button = event.relatedTarget
let id = button.getAttribute('data-bs-id')
let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
buttonElimina.value = id
});

function eliminar() {

    let botonElimina = document.getElementById('btn-elimina')
    let id = botonElimina.value
    let url = 'clases/Actu_cesta.php';
    let formData = new FormData();
    formData.append('action', 'eliminar');
    formData.append('id', id);


    fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
    }).then(response => response.json())
    .then(data => {
        if (data.ok) {
            location.reload()
        }
    })
}
//ajax ACORDARME SI ME DA ERROR ACTIVARLO 
// $(document).ready(function() {
//     $("#registro-form").submit(function(event) {
//         event.preventDefault(); // Evita la recarga de la página

//         var formData = $(this).serialize(); // Obtiene los datos del formulario

//         $.ajax({
//             url: 'registro.php', // URL donde se enviarán los datos
//             type: 'POST',
//             data: formData,
//             success: function(response) {
//                 // Mostrar la respuesta del servidor (éxito o error)
//                 $("#mensaje").html(response);
//             },
//             error: function() {
//                 // Muestra un error si no se puede enviar la solicitud AJAX
//                 $("#mensaje").html('<div class="alert alert-danger">Error en la solicitud. Por favor intente de nuevo.</div>');
//             }
//         });
//     });
// });

function verificarSesionYAgregar(productId, token) {
    // Obtener el botón específico para este producto usando el data-product-id
    const button = $(`button[data-product-id="${productId}"]`);

    // Deshabilitar el botón mientras verificamos la sesión
    button.prop('disabled', true);

    // Realizamos una llamada AJAX para verificar si el usuario está logueado
    $.ajax({
        url: 'verificar_sesion.php',  // Archivo PHP para verificar la sesión
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.loggedIn) {
                // Si está logueado, agregamos el producto al carrito
                addProducto(productId, token);
                // Mostrar alerta de éxito con botón rosa
                Swal.fire({
                    icon: 'success',
                    title: 'Producto agregado',
                    text: 'El producto ha sido añadido a tu cesta.',
                    confirmButtonText: 'OK',  // Botón OK
                    customClass: {
                        confirmButton: 'btn-rosa'  // Usamos la clase personalizada para el botón
                    }
                });
            } else {
                // Si no está logueado, mostramos alerta de advertencia con botón rosa
                Swal.fire({
                    icon: 'warning',
                    title: '¡Debes iniciar sesión!',
                    text: 'Para agregar productos a la cesta, primero debes iniciar sesión.',
                    confirmButtonText: 'OK',  // Botón OK
                    customClass: {
                        confirmButton: 'btn-rosa'  // Usamos la clase personalizada para el botón
                    }
                });
            }
        },
        complete: function() {
            // Habilitar el botón de nuevo después de la comprobación
            button.prop('disabled', false);
        }
    });
}