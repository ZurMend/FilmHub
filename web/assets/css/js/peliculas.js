const token = localStorage.getItem("token");

function registrarPelicula(){
    let formData = new FormData();
    formData.append("nombre",document.getElementById("nombre").value);
    formData.append("genero",document.getElementById("genero").value);
    formData.append("descripcion",document.getElementById("descripcion").value);
    formData.append("link",document.getElementById("link").value);
    formData.append("imagen",document.getElementById("imagen").files[0]);

    fetch("../api/peliculas/crear.php",{
        method:"POST",
        headers:{"Authorization":"Bearer "+token},
        body:formData
    })
    .then(res=>res.json())
    .then(data=>{
        alert("Registrada");
        listarPeliculas();
    });
}

function listarPeliculas(){
    fetch("../api/peliculas/listar.php",{
        headers:{"Authorization":"Bearer "+token}
    })
    .then(res=>res.json())
    .then(data=>{
        let html="";
        data.data.forEach(p=>{
            html+=`
            <tr>
            <td><img src="../api/uploads/${p.imagen}" width="80"></td>
            <td>${p.nombre}</td>
            <td>${p.genero}</td>
            <td>${p.estado}</td>
            <td>
            <button onclick="activar(${p.id})" class="btn btn-success btn-sm">Activar</button>
            <button onclick="inactivar(${p.id})" class="btn btn-danger btn-sm">Inactivar</button>
            </td>
            </tr>
            `;
        });
        document.getElementById("tablaPeliculas").innerHTML=html;
    });
}

function activar(id){
    fetch(`../api/peliculas/activar.php?id=${id}`,{
        headers:{"Authorization":"Bearer "+token}
    }).then(()=>listarPeliculas());
}

function inactivar(id){
    fetch(`../api/peliculas/inactivar.php?id=${id}`,{
        headers:{"Authorization":"Bearer "+token}
    }).then(()=>listarPeliculas());
}

listarPeliculas();
