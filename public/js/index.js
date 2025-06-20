// ====================================
// Event Listeners y Inicialización
// ====================================
document.addEventListener('DOMContentLoaded', () => {
    initializeAlerts();
    initializeEquipmentType();
});

// ====================================
// Funciones de Alertas
// ====================================
function initializeAlerts() {
    // Configurar listeners para cerrar alertas
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', () => closeAlert(button.parentElement));
    });

    // Configurar auto-cierre de alertas
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => alert && closeAlert(alert), 5000);
    });
}

function closeAlert(element) {
    element.style.animation = 'fadeOut 0.5s ease-out';
    setTimeout(() => element.remove(), 500);
}

// ====================================
// Funciones de Manejo de Imágenes
// ====================================
function previewImages(event) {
    const preview = document.getElementById('image-preview');
    const input = document.getElementById('images');
    const dt = new DataTransfer();
    
    preview.innerHTML = '';
    
    Array.from(event.target.files)
        .filter(file => file.type.startsWith('image/'))
        .forEach((file, index) => {
            createImagePreview(file, index, preview);
            dt.items.add(file);
        });
    
    input.files = dt.files;
}

function createImagePreview(file, index, preview) {
    const reader = new FileReader();
    const div = document.createElement('div');
    div.className = 'image-preview';
    
    reader.onload = (e) => {
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
    
    Array.from(input.files)
        .forEach((file, i) => {
            if (i !== index) dt.items.add(file);
        });
    
    input.files = dt.files;
    button.parentElement.remove();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    document.querySelectorAll('.image-preview').forEach((preview, newIndex) => {
        const removeBtn = preview.querySelector('.remove-image');
        removeBtn.setAttribute('onclick', `removeImage(${newIndex}, this)`);
    });
}

// ====================================
// Funciones de Manejo de Equipos
// ====================================
function initializeEquipmentType() {
    const equipmentType = document.getElementById('equipment_type');
    if (equipmentType?.value) {
        toggleSpecificFields();
    }
}

function toggleSpecificFields() {
    const equipmentType = document.getElementById('equipment_type').value;
    const allSpecificFields = document.querySelectorAll('.specific-fields');
    
    allSpecificFields.forEach(field => field.style.display = 'none');

    const fieldsMap = {
        'computador': '.computer-fields',
        'impresora': '.printer-fields'
    };

    const selector = fieldsMap[equipmentType];
    if (selector) {
        document.querySelector(selector).style.display = 'block';
    }
}
