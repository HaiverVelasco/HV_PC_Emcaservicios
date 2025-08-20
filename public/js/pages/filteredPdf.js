/**
 * Funcionalidad para la descarga de PDFs filtrados por tipo de equipo
 */

// Función para inicializar la funcionalidad de PDFs filtrados
function initFilteredPdfDownload() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const downloadFilteredPdfButtons = document.querySelectorAll('.btn-download-filtered-pdf');
    const filteredPdfModal = document.getElementById('filteredPdfModal');
    const closeFilteredPdfModal = document.querySelector('.close-filtered-pdf-modal');
    const filteredPdfStatus = document.getElementById('filteredPdfStatus');
    
    // Mostrar botón de descarga solo cuando hay un filtro activo
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const activeArea = this.closest('.area-section');
            const downloadBtn = activeArea.querySelector('.btn-download-filtered-pdf');
            const filterType = this.getAttribute('data-type');
            
            if (filterType === 'all') {
                downloadBtn.style.display = 'none';
            } else {
                downloadBtn.style.display = 'inline-block';
                downloadBtn.setAttribute('data-filter-type', filterType);
                downloadBtn.setAttribute('data-area-id', activeArea.querySelector('.btn-download-area-qr') ? 
                    activeArea.querySelector('.btn-download-area-qr').getAttribute('onclick').match(/'([^']*)'/) ? 
                    activeArea.querySelector('.btn-download-area-qr').getAttribute('onclick').match(/'([^']*)'/)[1] : '' : '');
            }
        });
    });

    // Cerrar modal de PDFs filtrados
    if (closeFilteredPdfModal) {
        closeFilteredPdfModal.onclick = () => filteredPdfModal.style.display = "none";
    }

    window.addEventListener('click', (event) => {
        if (event.target == filteredPdfModal) {
            filteredPdfModal.style.display = "none";
        }
    });

    // Descargar PDFs filtrados
    downloadFilteredPdfButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filterType = this.getAttribute('data-filter-type');
            const areaId = this.getAttribute('data-area-id');
            
            if (!filterType || !areaId) return;
            
            filteredPdfModal.style.display = 'block';
            filteredPdfStatus.textContent = `Preparando PDFs de ${filterType}...`;
            
            // Petición AJAX para obtener los PDFs
            fetch(`/equipment/download-filtered-pdfs/${areaId}/${filterType}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    // Intentar obtener más detalles del error
                    return response.json()
                        .then(errorData => {
                            throw new Error(errorData.error || 'Error en la descarga de PDFs');
                        })
                        .catch(() => {
                            // Si no se puede parsear como JSON, usar mensaje genérico
                            throw new Error(`Error ${response.status}: ${response.statusText || 'Error en la descarga de PDFs'}`);
                        });
                }
                
                // Verificar el tipo de contenido de la respuesta
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/pdf')) {
                    return response.blob();
                } else if (contentType && contentType.includes('application/json')) {
                    return response.json().then(data => {
                        throw new Error(data.error || 'El servidor no devolvió un archivo PDF válido');
                    });
                } else {
                    throw new Error('Formato de respuesta no válido');
                }
            })
            .then(blob => {
                // Verificar que el blob es un PDF
                if (blob.type !== 'application/pdf') {
                    throw new Error('El archivo descargado no es un PDF válido');
                }
                
                // Crear enlace de descarga
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `PDFs-${filterType}-${new Date().toISOString().slice(0,10)}.pdf`);
                document.body.appendChild(link);
                link.click();
                
                // Liberar recursos
                setTimeout(() => {
                    window.URL.revokeObjectURL(url);
                    link.remove();
                }, 100);
                
                filteredPdfStatus.textContent = 'Descarga completada';
                
                // Cerrar modal después de un tiempo
                setTimeout(() => {
                    filteredPdfModal.style.display = 'none';
                }, 2000);
            })
            .catch(error => {
                console.error('Error en la descarga de PDF:', error);
                filteredPdfStatus.textContent = `Error: ${error.message || 'Error en la descarga. Intente nuevamente.'}`;
                
                // Cerrar modal después de un tiempo
                setTimeout(() => {
                    filteredPdfModal.style.display = 'none';
                }, 5000);
            });
        });
    });
}

// Inicializar cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.btn-download-filtered-pdf')) {
        initFilteredPdfDownload();
    }
});
