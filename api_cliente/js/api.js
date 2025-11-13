let timeout = null;

async function buscarHoteles() {
    const search = document.getElementById('search').value.trim();
    const token = document.getElementById('token').value;
    const resultadosDiv = document.getElementById('resultados');

    // Limpiar resultados si el campo está vacío
    if (!search) {
        resultadosDiv.innerHTML = '';
        return;
    }

    // Mostrar loading
    resultadosDiv.innerHTML = `
        <div class="loading">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Buscando hoteles...</p>
        </div>
    `;

    // Cancelar la búsqueda anterior si existe
    if (timeout) {
        clearTimeout(timeout);
    }

    // Esperar 500ms después de que el usuario deje de escribir
    timeout = setTimeout(async () => {
        try {
            const formData = new FormData();
            formData.append('token', token);
            formData.append('search', search);

            const response = await fetch('api_handler.php?action=buscarHoteles', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!data.status) {
                Swal.fire({
                    icon: data.type || 'error',
                    title: data.type === 'error' ? 'Error' : 'Advertencia',
                    text: data.msg,
                    confirmButtonColor: '#667eea'
                });
                resultadosDiv.innerHTML = '';
                return;
            }

            if (data.data.length === 0) {
                resultadosDiv.innerHTML = `
                    <div class="no-results">
                        <i class="fas fa-info-circle"></i>
                        <p>No se encontraron hoteles con el criterio: "<strong>${search}</strong>"</p>
                    </div>
                `;
                return;
            }

            let html = '';
            data.data.forEach(hotel => {
                // Resaltar las coincidencias en los resultados
                const highlight = (text) => {
                    if (!text) return '';
                    const regex = new RegExp(search, 'gi');
                    return text.replace(regex, match => `<mark>${match}</mark>`);
                };

                html += `
                    <div class="hotel-card">
                        <div class="hotel-header">
                            <div class="hotel-icon"><i class="fas fa-hotel"></i></div>
                            <div>
                                <h3 class="hotel-nombre">${highlight(hotel.nombre)}</h3>
                                <p class="hotel-direccion">${highlight(hotel.direccion)}</p>
                            </div>
                        </div>
                        <div class="hotel-info">
                            <p><i class="fas fa-phone"></i> ${hotel.telefono}</p>
                            <p><i class="fas fa-bed"></i> ${highlight(hotel.tipos_habitacion)}</p>
                            <p><i class="fas fa-credit-card"></i> ${hotel.metodos_pago}</p>
                        </div>
                    </div>
                `;
            });

            resultadosDiv.innerHTML = html;

        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al buscar los hoteles.',
                confirmButtonColor: '#667eea'
            });
            resultadosDiv.innerHTML = '';
        }
    }, 500); // Esperar 500ms
}
