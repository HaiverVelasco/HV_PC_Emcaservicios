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
});

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

function removeImage(index, button) {
    const input = document.getElementById('images');
    const dt = new DataTransfer();
    const { files } = input;
    
    for(let i = 0; i < files.length; i++) {
        if(i !== index) dt.items.add(files[i]);
    }
    
    input.files = dt.files;
    button.parentElement.remove();
}