function getUserById(userId) {
    return fetch(`tu_endpoint.php?id=${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Aquí puedes manejar los datos obtenidos
            console.log(data);
            return data; // Devuelve los datos para su uso posterior si es necesario
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            throw error; // Lanza el error para manejarlo en el contexto de la aplicación
        });
}

function getAllUsers() {
    return fetch('tu_endpoint.php?all')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Aquí puedes manejar los datos obtenidos
            console.log(data);
            return data; // Devuelve los datos para su uso posterior si es necesario
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            throw error; // Lanza el error para manejarlo en el contexto de la aplicación
        });
}
