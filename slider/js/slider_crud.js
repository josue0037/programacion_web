function crear() {

    let nombre = document.getElementById("nombre").value;
    let imagen = document.getElementById("imagen").files[0];

    let formData = new FormData();
    formData.append("nombre", nombre);
    formData.append("imagen", imagen);

    fetch("slider.php?action=create", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(() => {
        cargar();
    });
}

function cargar() {

    fetch("slider.php?action=read")
    .then(res => res.json())
    .then(data => {

        let html = "";

        data.forEach(item => {
            html += `
                <div style="margin-bottom:20px;">
                    <img src="${item.ruta}" width="200">
                    <p>${item.nombre}</p>

                    <button onclick="editar(${item.id}, '${item.nombre}')">
                        Editar nombre
                    </button>

                    <button onclick="eliminar(${item.id})">
                        Eliminar
                    </button>
                </div>
            `;
        });

        document.getElementById("lista").innerHTML = html;
    });
}

function eliminar(id) {

    let formData = new FormData();
    formData.append("id", id);

    fetch("slider.php?action=delete", {
        method: "POST",
        body: formData
    })
    .then(() => cargar());
}

// cargar al inicio
cargar();

function editar(id, nombreActual) {

    let nuevoNombre = prompt("Nuevo nombre:", nombreActual);

    if (!nuevoNombre || nuevoNombre.trim() === "") return;

    let formData = new FormData();
    formData.append("id", id);
    formData.append("nombre", nuevoNombre);

    fetch("slider.php?action=update", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(() => {
        cargar(); // recargar lista
    });
}