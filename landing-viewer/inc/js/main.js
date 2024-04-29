const hasForm = document.getElementById('configForm');

document.addEventListener("DOMContentLoaded", function(event) {
    app();
});

function app() {
    console.log('App is ready');
    const elementsSelect = document.querySelectorAll('select');
    if (elementsSelect) {
        var changeColor = (element, newColor) => element.style.color = newColor;
        
        elementsSelect.forEach(select => {
            changeColor(select, select.value == 0 ? 'rgb(255 255 255 / .5)' : '#FFF');
            select.addEventListener('change', () => changeColor(select, '#FFF'))
        });
    }
}