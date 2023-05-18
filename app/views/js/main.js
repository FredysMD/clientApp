

// Función para realizar cualquier patición a una API

function peticiones(url, body, method, redirectUrl, token){

    const headers = {
        'Content-Type': 'application/json'
    };
    
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    fetch(url, {
        method: method,
        headers: headers,
        body: body
    })
    .then(response => response.json())
    .then(data => {
      
        if(method == "POST"){
            const token = data?.token;
            localStorage.setItem("token", token || "");
            
            if (redirectUrl!=="" && token) {
              window.location.href = redirectUrl;
            }
        }

        if(method == "GET"){
            generateCards(data);
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

    peticiones(
        "http://localhost/clientAPI/app/index.php/user/login", 
        creedenciales,
        "GET",
        "http://localhost/clientAPI/app/views/home.html"
    );
}

