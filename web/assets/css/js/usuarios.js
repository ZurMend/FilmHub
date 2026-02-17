const token = localStorage.getItem("token");

if(!token){
    window.location="login.php";
}

function registrarUsuario(){

    let formData = new FormData();
    formData.append("nombre",document.getElementById("nombre").value);
    formData.append("apellido_paterno",document.getElementById("apellido_paterno").value);
    formData.append("apellido_materno",document.getElementById("apellido_materno").value);
    formData.append("correo",document.getElementById("correo").value);

    fetch("../api/usuarios/crear.php",{
        method:"POST",
        headers:{
            "Authorization":"Bearer "+token
        },
        body:formData
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.status==="success"){
            alert("Usuario registrado y contraseña enviada al correo");
            limpiarFormulario();
            listarUsuarios();
        }else{
            alert("Error al registrar");
        }
    });
}

function listarUsuarios(){
    fetch("../api/usuarios/listar.php",{
        headers:{
            "Authorization":"Bearer "+token
        }
    })
    .then(res=>res.json())
    .then(data=>{
        let html="";
        data.data.forEach(u=>{
            html+=`
            <tr>
                <td>${u.nombre} ${u.apellido_paterno} ${u.apellido_materno}</td>
                <td>${u.correo}</td>
                <td>${u.fecha_registro}</td>
                <td>${u.estado}</td>
                <td>
                    <button onclick="activarUsuario(${u.id})" class="btn btn-success btn-sm">Activar</button>
                    <button onclick="eliminarUsuario(${u.id})" class="btn btn-danger btn-sm">Eliminar</button>
                </td>
            </tr>
            `;
        });

        document.getElementById("tablaUsuarios").innerHTML = html;
    });
}

function activarUsuario(id){
    fetch(`../api/usuarios/activar.php?id=${id}`,{
        headers:{
            "Authorization":"Bearer "+token
        }
    }).then(()=>listarUsuarios());
}

function eliminarUsuario(id){
    if(confirm("¿Eliminar usuario?")){
        fetch(`../api/usuarios/eliminar.php?id=${id}`,{
            headers:{
                "Authorization":"Bearer "+token
            }
        }).then(()=>listarUsuarios());
    }
}

function limpiarFormulario(){
    document.getElementById("nombre").value="";
    document.getElementById("apellido_paterno").value="";
    document.getElementById("apellido_materno").value="";
    document.getElementById("correo").value="";
}

listarUsuarios();
