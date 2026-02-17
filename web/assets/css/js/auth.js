function loginAdmin(){
    let correo = document.getElementById("correo").value;
    let clave  = document.getElementById("clave").value;

    fetch("../api/login/admin.php",{
        method:"POST",
        headers:{"Content-Type":"application/json"},
        body:JSON.stringify({correo,clave})
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.status=="success"){
            localStorage.setItem("token",data.data.token);
            window.location="dashboard.php";
        }else{
            alert("Credenciales incorrectas");
        }
    });
}
