$(document).ready(function(){
  'use strict';
  app();
});

function app() {
  // Cambio de login - register
  CambioDeFormulario();
  // Validation forms
  validacionDeFormulario();
}

function CambioDeFormulario() {
  const registerBtn = $('#toRegister');
  const loginBtn = $('#toLogin');
  const container = $('#main');
  const registerFormElements = $('#registerForm > [data-toggle="animated"]');
  const loginFormElements = $('#loginForm > [data-toggle="animated"]');
  const registerDescriptionElements = $('#registerDescription > *');
  const loginDescriptionElements = $('#loginDescription > *');
  let delayCant = 850;

  $(registerBtn).click(function(e) {
    e.preventDefault();
    delayAply(registerFormElements, undefined, delayCant);
    delayAply(registerDescriptionElements, 100, delayCant);
    delayAply(loginFormElements);
    delayAply(loginDescriptionElements, 100);
    $(container).removeClass('loginside').addClass('registerside');
  })
  $(loginBtn).click(function(e) {
    e.preventDefault();
    delayAply(registerFormElements);
    delayAply(registerDescriptionElements, 100);
    delayAply(loginFormElements, undefined, delayCant);
    delayAply(loginDescriptionElements, 100, delayCant);
    $(container).removeClass('registerside').addClass('loginside');
  })

  function delayAply(array, desfase = 40, delay = 0) {
    $(array).each(function (index, element) {
      $(element).css({
        'animation-delay': (delay + (desfase * index)) + 'ms',
      });
    });
  }
}

function validacionDeFormulario() {
  const submitButton = $('form button[type="submit"]');
  
  $(submitButton).each(function (index, button) {
    // element == this
    $(button).on('click', (e) => {
      e.preventDefault();
      const form = $(this).parents('form');
      const inputs = form.find('input, textarea');
      const messageContainer = form.find('.message-container');
      let message;
      let isValid = true;

      // Valida errores en el formulario
      (function validateErrors() {
        $(inputs).each(function (index, input) {
          if ($(input).attr('required')) {
            if ($(input).val() === '') {
              isValid = false;
              $(form).addClass('error');
              $(this).parents('.field').addClass('error');
            } else {
              $(form).removeClass('error');
              $(this).parents('.field').removeClass('error');
            }
          }
        });
      })()

      // Valida si el formulario está ok
      if (isValid) {
        form.submit();
        message = 'Form submitted successfully!';
        setMessage('success', message);
      } else {
        message = 'Please, fill all required fields';
        setMessage('error', message);
      }

      // Muestra mensaje en pantalla
      function setMessage(status, message) {
        $(messageContainer)
          .addClass(status)
          .text(message)
          .fadeIn(500, 'linear', () => {
            setTimeout(() => {
              $(messageContainer)
                .fadeOut(500, 'linear', () => {
                  $(messageContainer)
                    .removeClass(status)
                    .text('');
                })
            }, 3000)
          });
      }
      
    });
  });

  $('input, textarea').each(function (index, input) {
    $(input).on('keyup', (e) => {
      // Remueve estilo de error al completar el campo
      $(this).parents('.field').removeClass('error');
      // Valida el formulario al presionar enter en los inputs
      if (e.keyCode === 13) {
        $(this).parents('form').find('button[type="submit"]').click();
      }
    });
    
    // Valida el campo al hacer focus out
    $(input).focusout(() => {
      const required = $(input).attr('required');
      if (required && $(input).val() === '') {
        $(input).parents('.field').addClass('error');
      }
    });
  });
}