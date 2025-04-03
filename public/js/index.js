// ====================================
// Event Listeners y Inicialización
// ====================================
document.addEventListener('DOMContentLoaded', function() {
    initializeAlerts();
    initializeEquipmentType();
});

// ====================================
// Funciones de Alertas
// ====================================
function initializeAlerts() {
    // Configurar listeners para cerrar alertas
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            closeAlert(this.parentElement);
        });
    });

    // Configurar auto-cierre de alertas
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            if (alert) {
                closeAlert(alert);
            }
        }, 5000);
    });
}

function closeAlert(element) {
    element.style.animation = 'fadeOut 0.5s ease-out';
    setTimeout(() => {
        element.remove();
    }, 500);
}

// ====================================
// Funciones de Manejo de Imágenes
// ====================================
function previewImages(event) {
    const preview = document.getElementById('image-preview');
    const input = document.getElementById('images');
    const dt = new DataTransfer();
    
    preview.innerHTML = ''; // Limpiar previsualizaciones
    
    Array.from(event.target.files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            createImagePreview(file, index, preview);
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
}

function createImagePreview(file, index, preview) {
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

function removeImage(index, button) {
    const input = document.getElementById('images');
    const dt = new DataTransfer();
    
    Array.from(input.files).forEach((file, i) => {
        if(i !== index) dt.items.add(file);
    });
    
    input.files = dt.files;
    button.parentElement.remove();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const previews = document.querySelectorAll('.image-preview');
    previews.forEach((preview, newIndex) => {
        const removeBtn = preview.querySelector('.remove-image');
        removeBtn.setAttribute('onclick', `removeImage(${newIndex}, this)`);
    });
}

// ====================================
// Funciones de Manejo de Equipos
// ====================================
function initializeEquipmentType() {
    const equipmentType = document.getElementById('equipment_type');
    if(equipmentType && equipmentType.value) {
        toggleSpecificFields();
    }
}

function toggleSpecificFields() {
    const equipmentType = document.getElementById('equipment_type').value;
    const allSpecificFields = document.querySelectorAll('.specific-fields');
    
    // Ocultar todos los campos
    allSpecificFields.forEach(field => field.style.display = 'none');

    // Mostrar campos específicos
    if (equipmentType === 'computador') {
        document.querySelector('.computer-fields').style.display = 'block';
    } else if (equipmentType === 'impresora') {
        document.querySelector('.printer-fields').style.display = 'block';
    }
}