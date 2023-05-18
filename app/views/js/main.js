

// Función para realizar cualquier patición a una API

function peticion(url, body, redirectUrl){

    fetch(url, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: body
    })
    .then(response => response.json())
    .then(data => {

        const token = data?.token;
        localStorage.removeItem("token");
        localStorage.setItem("token", token || "");
        
        if (redirectUrl!=="" && token) {
            window.location.href = redirectUrl;
        }
    })
    .catch(error => {
      console.error('Error:', error);
    });

}

// Función para obtener el valor de un elemento html por su "id"
function getValue(id){
    return document.getElementById(id).value;
}

// Login de usuario por username y password

function login(event){
    
    event.preventDefault();

    const username = getValue("username");
    const password = getValue("password");

    let creedenciales  = JSON.stringify({
        username: username,
        password: password
    });


    peticion(
        "http://localhost/clientAPI/app/index.php/user/login", 
        creedenciales,
        "http://localhost/clientAPI/app/views/home.html"
    );
}

