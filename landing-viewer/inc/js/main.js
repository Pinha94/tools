const hasForm = document.getElementById('configForm');
const historialData = localStorage.getItem('historial');

document.addEventListener("DOMContentLoaded", function(event) {
    app();
    impHistorial();
});

function app() {
    console.info('App is ready');
    const elementsSelect = document.querySelectorAll('select');
    const iframes = document.querySelectorAll('.preview');
    const buttons = document.querySelectorAll('.button');
    const reloadBtn = document.getElementById('reloadBtn');
    const uncheckedBtn = document.getElementById('uncheckedBtn');
    const clearHistoryBtn = document.getElementById('clearHistoryBtn');
    const copyHashBtns = document.querySelectorAll('*[data-tocopy]');
    const selectFlow = document.getElementById('flow');

    elementsSelect && changeColorSelect();
    reloadBtn && reloadViews();
    buttons && animateButtons();
    uncheckedBtn && uncheckAll();
    clearHistoryBtn && clearHistory();
    (copyHashBtns.length > 0) && copyText(copyHashBtns);
    selectFlow && filterByFlow();

    // Cambia el color del default de los select
    function changeColorSelect() {
        var changeColor = (element, newColor) => element.style.color = newColor;
        
        elementsSelect.forEach(select => {
            changeColor(select, select.value == 0 ? 'rgb(255 255 255 / .5)' : '#FFF');
            select.addEventListener('change', () => changeColor(select, '#FFF'))
        });
    }
    // Recarga los iframes
    function reloadViews() {
        reloadBtn.addEventListener('click', () => {
            // Recarga el contenido de los iframes
            iframes.forEach(iframe => iframe.src = iframe.src);
        });
    }
    // Aplica animación a los botones
    function animateButtons() {
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
    // Deseslecciona todos los checkboxs
    function uncheckAll() {
        let checks = document.querySelectorAll('.checkbox');
        uncheckedBtn.addEventListener('click', () => {
            // Desmarca todos los checkbox
            checks.forEach(checkElement => checkElement.checked = false );
        });
    }
    // Limpia el historial
    function clearHistory() {
        clearHistoryBtn.addEventListener('click', () => {
            localStorage.removeItem('historial');
            document.getElementById('historialContent').innerHTML = '<li class="small">Nada para mostrar</li>';
        });
    }
    // Copia el hash en uso
    function copyText(button) {
        button.forEach(btn => {
            btn.addEventListener('click', event => {
                let result = saveToClipboard(event.currentTarget.getAttribute('data-tocopy'));
                // PENDIENTE
                // Agregar mensaje "Copied"
            });
        });
    }
    // Filtar por flujo
    function filterByFlow() {
        selectFlow.addEventListener('change', () => {
            showElements(['.viewer']);
            setTimeout(() => {
                switch (selectFlow.value) {
                    case 'pin':
                        hideElements(['.doi', '.confirm']);
                        break;
                    case 'wap':
                        hideElements(['.request-pin', '.doi', '.no-he']);
                        break;
                    case 'doi':
                        hideElements(['.request-pin', '.confirm', '.ok']);
                        break;
                
                    default:
                        console.log('muestra todos los elementos');
                        break;
                }
            }, 100);
        });
    }
}

// Imprime historial de navegación
function impHistorial() {
    const listHistorial = document.getElementById('historialContent');
    
    if (historialData !== null) {
        var historial = JSON.parse(historialData);
        historial.forEach(element => {
            var elementList = document.createElement('li');
            elementList.classList.add('item');
            elementList.innerText = (element['ambiente'] !== '1' ? element['ambiente'] : 'prod') + ` - ${element['hash']}`;
    
    
            listHistorial.prepend(elementList);
    
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

// Guarda texto del elemento en la papelera
function saveToClipboard(elementId) {
    const target = document.getElementById(elementId);
    const textToCopy = target.textContent;

    // Crear un elemento de área de texto temporal
    const textArea = document.createElement("textarea");

    // Establecer el contenido de texto en el área de texto
    textArea.value = textToCopy;

    // Añadir el área de texto al DOM
    document.body.appendChild(textArea);

    // Seleccionar todo el texto en el área de texto
    textArea.select();

    // Intentar copiar el texto seleccionado al portapapeles
    try {
        const successful = document.execCommand('copy');
        const message = successful ? 'Texto copiado al portapapeles' : 'Error al copiar el texto al portapapeles';
        console.info(message);
        // Eliminar el área de texto del DOM
        document.body.removeChild(textArea);
        return true;
    } catch (err) {
        console.error('Error al intentar copiar el texto:', err);
        // Eliminar el área de texto del DOM
        document.body.removeChild(textArea);
        return false;
    }


}

// Oculta elementos
function hideElements(params) {
    params.forEach(key => {
        let element = document.querySelector(key);
        return new Promise(resolve => {
            // Primer paso: Opacidad a 0
            element.animate({
                opacity: 0
            }, {
                duration: 500,
                easing: 'ease'
            }).onfinish = () => {
                // Segundo paso: Ancho a 0
                element.style.opacity = 0;
                element.animate({
                    width: 0
                }, {
                    duration: 1000,
                    easing: 'linear'
                }).onfinish = () => {
                    element.style.width = 0;
                    // Tercer paso: Display none
                    element.classList.add('hide');
                    resolve();
                };
            };
        });
    });
}
// Muestra elementos ocultos
function showElements(params) {
    let elements = document.querySelectorAll(params);
    elements.forEach(element => {
        if (element.classList.contains('hide')) {
            console.log('paso la validación');
            return new Promise(resolve => {
                // Primer paso: Establecer display como block
                element.classList.remove('hide');
        
                // Segundo paso: Ancho a 35rem
                element.animate({
                    width: '175px'
                }, {
                    duration: 1000,
                    easing: 'linear'
                }).onfinish = () => {
                    element.style.width = '175px';
                    // Tercer paso: Opacidad a 1
                    element.animate({
                        opacity: 1
                    }, {
                        duration: 500,
                        easing: 'ease'
                    }).onfinish = () => {
                        element.style.opacity = 1;
                        resolve;
                    };
                };
            });    
        } else {
            return false;
        }
    });
}