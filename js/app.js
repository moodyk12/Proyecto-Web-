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

// eliminar cualquier producto
let eliminaModal =document.getElementById('eliminaModal')
eliminaModal.addEventListener('show.bs.modal', function(event){
let button = event.relatedTarget
let id = button.getAttribute('data-bs-id')
let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
buttonElimina.value = id
})

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


