function peticiones(url, method, token){

    const headers = {
        'Content-Type': 'application/json'
    };
    
    if (token) {
        //headers['Authorization'] = `Bearer ${token}`;
    }

    fetch(url, {
        method: method,
        headers: headers,
    })
    .then(response => response.json())
    .then(data => {
        generateCards(data);
    })
    .catch(error => {
      console.error('Error:', error);
    });

}

function logged(){
    return localStorage.getItem("token") ;
}

function logout(){
    localStorage.removeItem("token");
    window.location.href = "http://localhost/clientAPI/app/views/";
}

// ---------------------------------------- //

function generateCards(data) {
    if (data && Array.isArray(data)) {
        let cardContainer = document.getElementById("card-container");

        // Limpiar el contenedor antes de generar los nuevos cards
        cardContainer.innerHTML = "";
        const len = data.length;
        // Iterar sobre los datos y generar los cards
        for (let i = 0; i < len; i += 3) {
            // Crear elementos HTML dinámicamente
            let row = document.createElement("div");
            row.classList.add("row");

            // Generar máximo 3 cards por fila
            for (let j = i; j < i + 3 && j < len; j++) {
                let col = document.createElement("div");
                col.classList.add("col-6");

                let card = document.createElement("div");
                card.classList.add("card", "w-100");

                let cardBody = document.createElement("div");
                cardBody.classList.add("card-body");

                let cardTitle = document.createElement("h5");
                cardTitle.classList.add("card-title");
                cardTitle.textContent = data[j].name + " " + data[j].lastName;

                let cardText = document.createElement("p");
                cardText.classList.add("card-text");
                cardText.textContent = "+57 "+data[j].phone || "No cuenta con número de teléfono.";

                // Anidar los elementos HTML
                cardBody.appendChild(cardTitle);
                cardBody.appendChild(cardText);

                card.appendChild(cardBody);

                col.appendChild(card);
                row.appendChild(col);
            }

            cardContainer.appendChild(row);
        }
    } else {
        console.error("Error: Datos inválidos");
    }
}

// ---------------------------------------- //

const token = logged();  


if(token == "" || token == null) { 
    window.location.href = "http://localhost/clientAPI/app/views/";
}else{
    peticiones(
        "http://localhost/clientAPI/app/index.php/user/getAllUsers",
        "GET",
        token
    );
}