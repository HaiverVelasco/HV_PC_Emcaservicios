document.addEventListener('DOMContentLoaded', function() {
    // Función para cerrar la alerta
    function closeAlert(element) {
        element.style.animation = 'fadeOut 0.5s ease-out';
        setTimeout(() => {
            element.remove();
        }, 500);
    }

    // Cerrar alerta al hacer clic en el botón
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            closeAlert(this.parentElement);
        });
    });

    // Auto-cerrar alertas después de 5 segundos
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            if (alert) {
                closeAlert(alert);
            }
        }, 5000);
    });

    // Función para eliminar imagen
    function deleteImage(imageId, button) {
        if (confirm('¿Está seguro que desea eliminar esta imagen?')) {
            fetch(`/equipment/image/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.closest('.image-preview').remove();
                } else {
                    alert('Error al eliminar la imagen');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar la imagen');
            });
        }
    }

    // Función para previsualizar imágenes
    function previewImages(event) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        
        Array.from(event.target.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                const div = document.createElement('div');
                div.className = 'image-preview';
                
                reader.onload = function(e) {
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-image" onclick="removeImage(${index}, this)">×</button>
                    `;
                };
                
                reader.readAsDataURL(file);
                preview.appendChild(div);
            }
        });
    }
});