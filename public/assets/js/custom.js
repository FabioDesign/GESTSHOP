//Nombre entier
function verif_int(champ) {
  var chiffres = new RegExp('[0-9]');
  for(x = 0; x < champ.value.length; x++) {
    verif = chiffres.test(champ.value.charAt(x));
    if (verif == false) {
      champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;
    }
  }
}
//Cocher afficher
$(document).on('ifChecked', '.check', function(event) {
  $(this).parents('.boxcheck').siblings().find('.show').each(function() {
    $(this).parent().addClass('checked');
    $(this).attr('checked', 'checked');
  });
});
$(document).on('ifUnchecked', '.show', function(event) {
  $(this).parents('.boxcheck').siblings().find('input:checkbox').each(function() {
    $(this).parent().removeClass('checked');
    $(this).removeAttr('checked');
  });
});
//Checkbox
$('.iCheck').iCheck({
  checkboxClass: 'icheckbox_square-blue',
  radioClass: 'iradio_square-blue',
  increaseArea: '20%'
});
//View Password
$('.viewPass, .backPass').on('click', function() {
  var password = $(this).siblings('input');
  if (password.attr('type') == 'password') {
    password.attr('type', 'text');
    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
  } else {
    password.attr('type', 'password');
    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
  }
});
$('#specimen').on('change', function() {
  let file = this.files[0];
  if (file) {
    // Vérifier que c’est bien une image
    if (!file.type.startsWith('image/')) {
      $('.msgError').html("Veuillez sélectionner une image valide !");
      $(this).val('');
      $('#previewImage').hide();
      return;
    }
    let reader = new FileReader();
    reader.onload = function(e) {
      $('#previewImage').attr('src', e.target.result).fadeIn(); // effet sympa
    }
    reader.readAsDataURL(file);
  }
});
// Signature
$('#signature').on('change', function() {
  let file = this.files[0];
  if (file) {
    // Vérifier que c’est bien une image
    if (!file.type.startsWith('image/')) {
      $('.msgError').html("Veuillez sélectionner une image valide !");
      $(this).val('');
      $('#previewSignature').hide();
      return;
    }
    let reader = new FileReader();
    reader.onload = function(e) {
      $('#remove_sig').css('display', 'flex').data('status', 0);
      $('#previewSignature').attr('src', e.target.result).fadeIn(); // effet sympa
    }
    reader.readAsDataURL(file);
  }
});
$(document).on('click', '#remove_sig', function () {
    $('#signature').val('');
    $(this).hide().data('status', 0);
    $('#previewSignature').attr('src', '');
});

// Cachet
$('#stamp').on('change', function() {
  let file = this.files[0];
  if (file) {
    // Vérifier que c’est bien une image
    if (!file.type.startsWith('image/')) {
      $('.msgError').html("Veuillez sélectionner une image valide !");
      $(this).val('');
      $('#previewStamp').hide();
      return;
    }
    let reader = new FileReader();
    reader.onload = function(e) {
      $('#remove_sta').css('display', 'flex').data('status', 0);
      $('#previewStamp').attr('src', e.target.result).fadeIn(); // effet sympa
    }
    reader.readAsDataURL(file);
  }
});
$(document).on('click', '#remove_sta', function () {
    $('#stamp').val('');
    $(this).hide().data('status', 0);
    $('#previewStamp').attr('src', '');
});
//X-CSRF-TOKEN
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
$('#pays_id').on('change', function() {
  let dataString = { country_id: $(this).val() };
  $.ajax({
    type: 'POST',
    data: dataString,
    url: '/towns/list',
    success: function(response) {
      if (response === 'x') {
        window.location.href = '/';
        return;
      }
      if (response.status == 1) {
        // Vider avant de recharger
        $("#town_id").empty().append('<option value="" disabled selected>Sélectionner</option>');

        $.each(response.data, function(i, d) {
          $("#town_id").append("<option value='" + d.id + "'>" + d.libelle + "</option>");
        });
      } else {
        $('.msgError').html(response.message);
      }
    }
  });
});
// Gestionnaire pour le changement de statut
$(document).on('click', '.status', function(e) {
  e.preventDefault();
  var urlStatus = $(this).data('url');
  var typeStatus = $(this).data('type');
  var actionTitle = $(this).attr('data-bs-original-title') || $(this).attr('title');
  Swal.fire({
    title: actionTitle,
    text: 'Veuillez confirmer votre action.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Confirmer',
    cancelButtonText: 'Annuler'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: urlStatus,
        type: 'POST',
        data: {
          _token: $('meta[name="csrf-token"]').attr('content'),
          _method: typeStatus
        },
        beforeSend: function() {
          Swal.fire({
            title: 'Chargement en cours...',
            text: 'Veuillez patienter...',
            timer: 50000,
            showConfirmButton: false,
          }).then(function(result) {
            if (result.dismiss === "timer") {
              console.log("I was closed by the timer")
            }
          })
        },
        success: function(response) {
          if (response === 'x') {
            window.location.href = '/';
            return;
          }
          if (response.status == true) {
            Swal.fire({
              title: "Félicitation !",
              text: response.message,
              icon: 'success',
              confirmButtonText: "Fermer",
              customClass:{
                confirmButton: "btn btn-square font-weight-bold btn-light-success"
              }
            }).then(function() {
              location.reload();
            });
          } else {
            Swal.fire({
              title: 'Erreur !',
              text: response.message,
              icon: 'error',
              confirmButtonText: 'Fermer',
              customClass: {
                confirmButton: "btn btn-square font-weight-bold btn-light-success"
              },
            });
          }
        },
        error: function(xhr) {
          Swal.fire({
            title: 'Erreur!',
            text: 'Une erreur est survenue.',
            icon: 'error',
          });
        }
      });
    }
  });
});
//Form Add/Mod
$(document).on('click', '.submitForm', function(e) {
  e.preventDefault();
  let iCheck = false;
  let hasError = false;
  $('.msgError').html('');
  let submitForm = $(this).html();
  $('.is-invalid').removeClass('is-invalid');
  let rootForm = $('#rootForm').val();
  let datasT = new FormData();
  $('.formField').find('input, select, textarea').each(function() {
    if ($(this).is(':input:file')) {
      if ($(this).val() !== '') datasT.append(this.name, $(this)[0].files[0]);
    } else if ($(this).is(':checkbox')) {
      if ($(this).is(':checked')) {
        datasT.append(this.name, $(this).val());
        iCheck = true;
      }
    } else if ($(this).is(':radio')) {
      if ($(this).is(':checked')) {
        datasT.append(this.name, $(this).val());
        iCheck = true;
      }
    } else datasT.append(this.name, $(this).val());
  });
  if (rootForm.slice(0, 5) === 'users') {
    datasT.append('code', $('.iti__selected-dial-code').html());
    datasT.append('img_sta', $('#remove_sta').data('status'));
    datasT.append('img_sig', $('#remove_sig').data('status'));
  }
  $('.formField .requiredField').each(function() {
    if (jQuery.trim($(this).val()) === '') {
      $('.msgError').html("Veuillez renseigner les champs obligatoires !");
      $(this).addClass('is-invalid');
      hasError = true;
    }
  });
  $('.formField .checked').each(function() {
    if (!hasError) {
      if (!iCheck) {
        $('.msgError').html('Veuillez cocher au moins une case.');
        $(this).addClass('is-invalid');
        hasError = true;
      }
    }
  });
  $('.formField .number').each(function() {
    if (!hasError) {
      let value = jQuery.trim($(this).val());
      let regex = /^[0-9\s]*$/;
      if ((value != '')&&(!regex.test(value))) {
        $('.msgError').html("Téléphone non valide.");
        $(this).addClass('is-invalid');
        hasError = true;
      }
    }
  });
  $('.formField .email').each(function() {
    if (!hasError) {
      let value = jQuery.trim($(this).val());
      let regex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      if ((value != '')&&(!regex.test(value))) {
        $(this).addClass('is-invalid');
        $('.msgError').html("Adresse e-mail non valide.");
        hasError = true;
      }
    }
  });
  $('.formField .password').each(function() {
    if (!hasError) {
      if ($(this).val().length < 5) {
        $('.msgError').html("Les mots de passe doivent être supérieur à 5 caractères");
        $(this).addClass('is-invalid');
        hasError = true;
      } else if ($('#newpass').val() !== $('#confirmpass').val()) {
        $('.msgError').html("Les mots de passe ne sont pas identiques");
        $('#newpass, #confirmpass').addClass('is-invalid');
        hasError = true;
      }
    }
  });
  if (!hasError) {
    $.ajax({
      type: 'POST',
      data: datasT,
      contentType: false, 
      processData: false,
      url: '/'+ rootForm,
      beforeSend: function() {
        $('.submitForm').addClass('not-active').html('<i class="fa fa-spinner fa-pulse"></i> Patienter...');
      },
      success:function(response) {
        if (response === 'x') {
          window.location.href = '/';
          return;
        }
        if (response.status != 0) {
          swal.fire({
            title: "Félicitation !",
            text: response.message,
            icon: 'success',
            confirmButtonText: "Fermer",
            customClass:{
              confirmButton: "btn btn-square font-weight-bold btn-light-success"
            }
          }).then(function() {
            if (response.status == 1)
              location.reload();
            else
              location.href = '/';
          });
        } else {
          $('.msgError').html(response.message);
          $('.submitForm').removeClass('not-active').addClass('btn-success').html(submitForm);
        }
      }
    });
  }
});