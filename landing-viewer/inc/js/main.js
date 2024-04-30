const hasForm = document.getElementById('configForm');
const historialData = localStorage.getItem('historial');

document.addEventListener("DOMContentLoaded", function(event) {
    app();
    impHistorial();
});

function app() {
    console.log('App is ready');
    const elementsSelect = document.querySelectorAll('select');
    const iframes = document.querySelectorAll('.preview');
    const buttons = document.querySelectorAll('.button');
    const reloadBtn = document.getElementById('reloadBtn');
    const uncheckedBtn = document.getElementById('uncheckedBtn');

    if (elementsSelect) {
        var changeColor = (element, newColor) => element.style.color = newColor;
        
        elementsSelect.forEach(select => {
            changeColor(select, select.value == 0 ? 'rgb(255 255 255 / .5)' : '#FFF');
            select.addEventListener('change', () => changeColor(select, '#FFF'))
        });
    }

    if (reloadBtn) {
        reloadBtn.addEventListener('click', () => {
            // Recarga el contenido de los iframes
            iframes.forEach(iframe => iframe.src = iframe.src);
        });
    }

    if (buttons) {
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                // Agregar la clase 'clicked' al botón cuando se le hace click
                button.classList.add('clicked');
            
                // Remover la clase 'clicked' después de un breve retraso para que el botón vuelva a su tamaño original
                setTimeout(function() {
                    button.classList.remove('clicked');
                }, 100); // Cambia este valor según la duración deseada del efecto
            });
        });
    }

    if (uncheckedBtn) {
        let checks = document.querySelectorAll('.checkbox');
        uncheckedBtn.addEventListener('click', () => {
            // Desmarca todos los checkbox
            checks.forEach(checkElement => checkElement.checked = false );
        });
    }
}

function impHistorial() {
    const listHistorial = document.getElementById('historialContent');
    
    if (historialData !== null) {
        var historial = JSON.parse(historialData);
        historial.forEach(element => {
            var elementList = document.createElement('li');
            elementList.classList.add('item');
            elementList.innerText = (element['ambiente'] !== '1' ? element['ambiente'] : 'prod') + ` - ${element['hash']}`;
    
    
            listHistorial.append(elementList);
    
            elementList.addEventListener('click', () => {
                setForm(element);
            });
        });
    } else {
        var historial = ['Nada para mostrar'];
        var elementList = document.createElement('li');
        elementList.classList.add('small');
        elementList.innerText = historial;
        listHistorial.append(elementList);
        
    }

    function setForm(data) {
        const fields = document.querySelectorAll('.field');
        const hash = document.getElementById('hash');
        const ambiente = document.getElementById('ambiente');
        const pais = document.getElementById('pais');
        const digitsCant = document.getElementById('digitsCant');
        const prefix = document.getElementById('prefix');
        const custom = document.getElementById('custom');

        ambiente.value = data['ambiente'];
        hash.value = data['hash'];
        pais.value = data['pais'];
        digitsCant.value = data['digitsCant'];
        prefix.value = data['prefix'];
        custom.value = data['custom'];
    }
}
