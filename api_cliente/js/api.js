let timeout = null;

async function buscarHoteles() {
    const searchInput = document.getElementById('search');
    const resultadosDiv = document.getElementById('resultados');

    const search = searchInput.value.trim();

    if (!search) {
        resultadosDiv.innerHTML = '';
        return;
    }

    resultadosDiv.innerHTML = `
        <div class="loading">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Buscando hoteles...</p>
        </div>
    `;

    if (timeout) clearTimeout(timeout);

    timeout = setTimeout(async () => {
        try {
            const formData = new FormData();
            formData.append('search', search);

            const response = await fetch('api_handler.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!data.status) {
                Swal.fire({
                    icon: data.type,
                    title: 'Error',
                    text: data.msg
                });
                resultadosDiv.innerHTML = '';
                return;
            }

            if (data.data.length === 0) {
                resultadosDiv.innerHTML = `
                    <div class="no-results">
                        <i class="fas fa-info-circle"></i>
                        <p>No se encontraron resultados para "<b>${search}</b>"</p>
                    </div>
                `;
                return;
            }

            let html = '';

            data.data.forEach(hotel => {
                html += `
                    <div class="hotel-card">
                        <h3>${hotel.nombre}</h3>
                        <p>${hotel.direccion}</p>
                        <p>${hotel.telefono}</p>
                        <p>${hotel.tipos_habitacion}</p>
                        <p>${hotel.metodos_pago}</p>
                    </div>
                `;
            });

            resultadosDiv.innerHTML = html;

        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al procesar la búsqueda.'
            });
        }
    }, 500);
}
