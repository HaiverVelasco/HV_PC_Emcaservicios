// ===============================
// Gestión de Alertas
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    function closeAlert(element) {
        element.style.animation = 'fadeOut 0.5s ease-out';
        setTimeout(() => {
            element.remove();
        }, 500);
    }

    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            closeAlert(this.parentElement);
        });
    });

    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            if (alert) {
                closeAlert(alert);
            }
        }, 5000);
    });

    // Initialize equipment fields on load
    toggleSpecificFields();
});

// ===============================
// Gestión de Imágenes
// ===============================
window.deleteImage = function(imageId, button) {
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
                alert('Error al eliminar la imagen: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar la imagen');
        });
    }
}

window.previewImages = function(event) {
    const preview = document.getElementById('image-preview');
    
    Array.from(event.target.files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            const div = document.createElement('div');
            div.className = 'image-preview';
            
            reader.onload = function(e) {
                const date = new Date();
                const formattedDate = date.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
                const formattedTime = date.toLocaleTimeString('es-ES', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });

                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <div class="image-info">
                        <span class="image-date">${formattedDate} ${formattedTime}</span>
                    </div>
                    <button type="button" class="remove-image" onclick="removeNewImage(${index}, this)">×</button>
                `;
            };
            
            reader.readAsDataURL(file);
            preview.appendChild(div);
        }
    });
}

window.removeNewImage = function(index, button) {
    const input = document.getElementById('new_images');
    const dt = new DataTransfer();
    const { files } = input;
    
    for(let i = 0; i < files.length; i++) {
        if(i !== index) dt.items.add(files[i]);
    }
    
    input.files = dt.files;
    button.closest('.image-preview').remove();
}

// ===============================
// Gestión de Campos de Equipo
// ===============================
function toggleSpecificFields() {
    const equipmentType = document.getElementById('equipment_type').value;
    const allSpecificFields = document.querySelectorAll('.specific-fields');
    
    // Ocultar todos los campos específicos primero
    allSpecificFields.forEach(field => {
        field.style.display = 'none';
    });
    
    // Mostrar los campos específicos según el tipo de equipo seleccionado
    if (equipmentType) {
        const specificFields = document.querySelector(`.${equipmentType}-fields`);
        if (specificFields) {
            specificFields.style.display = 'contents';
        }
    }
}
