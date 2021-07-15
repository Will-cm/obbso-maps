$(document).ready(() => {

  $('.upload_file').on('submit', upload);
  function upload(e) {
    e.preventDefault();

    let form = $(this),
      wrapper = $('.wrapper'),
      wrapper_f = $('.wrapper_files'),
      progress_bar = $('.progress_bar'),
      data = new FormData(form.get(0));

    //inicialixzando la barra de progreso
    progress_bar.removeClass('bg-succes bg-danger').addClass('bg-info');
    progress_bar.css('width', '0%');
    progress_bar.html('Preparando...');
    wrapper.fadeIn();

    $.ajax({
      xhr: function () {
        let xhr = new window.XMLHttpRequest();

        xhr.upload.addEventListener("progress", function (e) {
          if (e.lengthComputable) {
            let percentComplete = Math.floor((e.loaded / e.total) * 100);

            //Mostramos el progreso
            progress_bar.css('width', percentComplete + '%');
            progress_bar.html(percentComplete + '%');
          }
        }, false);

        return xhr;
      },
      type: 'POST',
      url: "upload_excel.php",
      dataType: 'json',
      contentType: false,
      processData: false,
      cache: false,
      data: data,
      beforeSend: () => {
        $('button', form).attr('disabled', true);
      }
    }).done(res => {
      if (res.status === 200) {
        progress_bar.removeClass('bg-info').addClass('bg-success');
        progress_bar.html('¡Listo!');
        form.trigger('reset');  //reinicia el formulario

        //Agregamos un boton de desca
        //
        setTimeout(() => {
          wrapper.fadeOut();
          progress_bar.removeClass('bbg-success bg-danger').addClass('bg-info');
          progress_bar.css('width', '0%');
        }, 1500);

      } else {
        alert(res.msg);
        progress_bar.css('width', '100%');
        progress_bar.html(res.msg);
      }
    }).fail(err => {
      progress_bar.removeClass('bg-success bg-info').addClass('bg-danger');
      progress_bar.html('Hubo un error!');
    }).always(() => {
      $('button', form).attr('disabled', false);
    });
  }
})