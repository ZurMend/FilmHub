const token = localStorage.getItem("token");

if(!token){
    window.location="login.php";
}

function registrarCliente(){

    let formData = new FormData();
    formData.append("nombre",document.getElementById("nombre").value);
    formData.append("apellido_paterno",document.getElementById("apellido_paterno").value);
    formData.append("apellido_materno",document.getElementById("apellido_materno").value);
    formData.append("correo",document.getElementById("correo").value);

    fetch("../api/clientes/crear.php",{
        method:"POST",
        headers:{
            "Authorization":"Bearer "+token
        },
        body:formData
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.status==="success"){
            alert("Cliente registrado y contraseña enviada al correo");
            limpiarFormulario();
            listarClientes();
        }else{
            alert("Error al registrar");
        }
    });
}

function listarClientes(){
    fetch("../api/clientes/listar.php",{
        headers:{
            "Authorization":"Bearer "+token
        }
    })
    .then(res=>res.json())
    .then(data=>{
        let html="";
        data.data.forEach(c=>{
            html+=`
            <tr>
                <td>${c.nombre} ${c.apellido_paterno} ${c.apellido_materno}</td>
                <td>${c.correo}</td>
                <td>${c.fecha_registro}</td>
                <td>${c.estado}</td>
                <td>
                    <button onclick="activarCliente(${c.id})" class="btn btn-success btn-sm">Activar</button>
                    <button onclick="eliminarCliente(${c.id})" class="btn btn-danger btn-sm">Eliminar</button>
                </td>
            </tr>
            `;
        });

        document.getElementById("tablaClientes").innerHTML = html;
    });
}

function activarCliente(id){
    fetch(`../api/clientes/activar.php?id=${id}`,{
        headers:{
            "Authorization":"Bearer "+token
        }
    }).then(()=>listarClientes());
}

function eliminarCliente(id){
    if(confirm("¿Eliminar cliente?")){
        fetch(`../api/clientes/eliminar.php?id=${id}`,{
            headers:{
                "Authorization":"Bearer "+token
            }
        }).then(()=>listarClientes());
    }
}

function limpiarFormulario(){
    document.getElementById("nombre").value="";
    document.getElementById("apellido_paterno").value="";
    document.getElementById("apellido_materno").value="";
    document.getElementById("correo").value="";
}

listarClientes();
