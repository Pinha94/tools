const elementsSelect = document.querySelectorAll('select');
const hasForm = document.getElementById('configForm');
const historialData = localStorage.getItem('historial');
const isMobile = window.innerWidth <= 640;

document.addEventListener("DOMContentLoaded", function(event) {
    app();
    impHistorial();
    elementsSelect && changeColorSelect();
    isMobile && appMobile();
});

// Arrow functions útiles
var changeColor = (element, newColor) => element.style.color = newColor;

// Funciones ejecutadas por evento
function app() {
    console.info('App is ready');
    const iframes = document.querySelectorAll('.preview');
    const buttons = document.querySelectorAll('.button');
    const reloadBtn = document.getElementById('reloadBtn');
    const uncheckedBtn = document.getElementById('uncheckedBtn');
    const clearHistoryBtn = document.getElementById('clearHistoryBtn');
    const copyHashBtns = document.querySelectorAll('*[data-tocopy]');
    const selectFlow = document.getElementById('flow');
    const formPopup = document.getElementById('popup');
    const bigView = document.getElementById('bigView');

    reloadBtn && reloadViews();
    buttons && animateButtons();
    uncheckedBtn && uncheckAll();
    clearHistoryBtn && clearHistory();
    (copyHashBtns.length > 0) && copyText(copyHashBtns);
    selectFlow && filterByFlow();
    formPopup && addCountry();
    bigView && setExpandedView();

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
            showViewer(['.viewer']);
            setTimeout(() => {
                switch (selectFlow.value) {
                    case 'pin':
                        hideViewer(['.doi', '.confirm']);
                        break;
                    case 'wap':
                        hideViewer(['.request-pin', '.doi', '.no-he']);
                        break;
                    case 'doi':
                        hideViewer(['.request-pin', '.confirm', '.ok']);
                        break;
                
                    default:
                        console.log('muestra todos los elementos');
                        break;
                }
            }, 100);
        });
    }
    // Agrega un nuevo país al json
    function addCountry() {
        const selectCountry = document.getElementById('pais');
        const cancelButton = document.getElementById('cancelButton');

        selectCountry.addEventListener('click', () => ( selectCountry.value == 'new') && show(formPopup) )

        cancelButton.addEventListener('click', () => {
            hide(formPopup);
            selectCountry.value = 0;
            changeColor(selectCountry, 'rgb(255 255 255 / .5)');
        });
    }
    function setExpandedView() {
        const body = document.querySelector('body');
        const buttons = document.querySelectorAll('button[data-action="expand"]');
        const closeButton = document.getElementById('closeBigView');
        const bigViewName = document.getElementById('bigViewName');
        const expandedView = document.getElementById('expandedView');
        
        // Itera todos los botones de expandir para escuchar el click
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                let nameView = button.getAttribute('data-name');
                let srcView = button.getAttribute('data-src');

                bigViewName.innerText = nameView;
                expandedView.setAttribute('src', srcView);
                body.classList.add('no-scroll');
                
                
                show(bigView);
                resizeBigView();
            });
        });

        // Cerrar vista expandida
        closeButton.addEventListener('click', () => {
            hide(bigView);
            body.classList.remove('no-scroll')
        });

        // Ajusta el tamaño
        function resizeBigView() {
            var height = expandedView.offsetHeight;
            expandedView.width = height * (9 / 20);
            // var width = expandedView.offsetWidth;
            console.log('rezized');
        }
    }
}
// Funciones ejecutadas por eventos en mobile
function appMobile() {
    const historial = document.getElementById('historial');
    const buttonExpandHistory = document.querySelector('#historial .expand-menu');
    const iconExpandHistory = document.querySelector('#historial .expand-menu i');

    iconExpandHistory && showHideHistory();
    
    // Muestra brevemente el historial antes de ocultarlo
    (function() {
        setTimeout(() => {
            slideX(historial, `-${historial.offsetWidth}px`, 500, 'right' );
            rotate(iconExpandHistory, '180deg', 500 );
        }, 1000);
    })();

    // Muestra y oculta el historial
    function showHideHistory() {
        buttonExpandHistory.addEventListener('click', () => {
            if (historial.classList.contains('active')) {
                slideX(historial, `-${historial.offsetWidth}px`, 500, 'right');
                rotate(iconExpandHistory, '180deg', 500);
                historial.classList.remove('active');
            } else {
                slideX(historial, 0, 500, 'right');
                rotate(iconExpandHistory, '0deg', 500);
                historial.classList.add('active');
            }
        })
    }
}
// Cambia el color del default de los select
function changeColorSelect() {    
    elementsSelect.forEach(select => {
        changeColor(select, select.value == 0 ? 'rgb(255 255 255 / .5)' : '#FFF');
        select.addEventListener('change', () => changeColor(select, '#FFF'))
    });
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
// Oculta vistas
function hideViewer(params) {
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
// Muestra vistas ocultas
function showViewer(params) {
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
// Oculta un elemento
function hide(element) {
    element.animate({
        opacity: 0
    }, 500).onfinish = () => {
        element.style.opacity = 0;
        element.classList.add('hide');
    }
}
// Muestra un elemento
function show(element) {
    element.classList.remove('hide');
    element.animate({
        opacity: 1
    }, 500).onfinish = () => {
        element.style.opacity = 1;
    }
}
// Deslizamiento a horizontal
function slideX(element, distans, speed, position = 'left') {
    element.style.transition = speed + 'ms';
        
    if (position === 'left') {
        element.animate({
            left: distans
        }).onfinish = () => element.style.left = distans
    } else {
        element.animate({
            right: distans
        }).onfinish = () => element.style.right = distans
    }
}
// Rotación
function rotate(element, deg, speed) {
    element.style.transition = speed + 'ms';
    element.style.transform = `rotate(${deg})`;
}