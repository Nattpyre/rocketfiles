$(document).ready(function() {

//Скрипт добавления класса active пунктам меню
  var pathname = this.location.pathname.replace(/\/[0-9]+/g, '');

  $('.menu-list a[href="' + pathname + '"]').parent().addClass('active');

//Аналог htmlspecialchars на JS
  function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };

    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
  }

//Скрипт изменения внешнего вида кнопки загрузки
	$(document).on('change', '.upload-file :file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });

  $('.upload-file :file').on('fileselect', function(event, numFiles, label) {
        
    var input = $(this).parents('#main_upload').find(':text'),
        log = numFiles > 1 ? numFiles + ' files selected' : label;
        
    if( input.length ) {
        input.val(log);
    } else {
        if( log ) alert(log);
    }
        
  });

//Скрипт тоггла блока с добавлением комментария
  $('.comments-toggle').click(function (){
    $('.add-comment').slideToggle();
    $('.comments-toggle').html($('.comments-toggle').text() == 'Добавить комментарий' ? 'Свернуть комментарий' : 'Добавить комментарий');
  });

//Скрипт добавления запроса поиска к ссылкам в пагинаторе
  $.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null){
       return null;
    }
    else{
       return results[1] || 0;
    }
  }

  if ($.urlParam('search') != null) {

    var search = 'search=' + $.urlParam('search');

    $('.pagination a').each(function()
    {
        var href = $(this).attr('href');

        if(href) {
            href = '?' + escapeHtml(search) + '&' + href.substring(1);
            $(this).attr('href', href);
        }
    });
  }

//Скрипт подтверждения удаления файла
  $('.delete-file').click(function (e) {

    e.preventDefault();

    var href = $(this).attr('href');

    $(".dialog").text('Вы уверены, что хотите удалить этот файл?');

    $(".dialog").dialog({
      resizable: false,
      draggable : false,
      modal: true,
      title : 'Удаление файла',
      buttons: {
        'Удалить': function() {
          $(this).dialog('close');
          window.location = href;
        },
        'Отмена': function() {
          $(this).dialog('close');
        }
      }
    });
  });

//Скрипт подтверждения удаления комментария
  $('.delete-comment').click(function (e) {

    e.preventDefault();

    var href = $(this).attr('href');

    $(".dialog").text('Вы уверены, что хотите удалить этот комментарий?');

    $(".dialog").dialog({
      resizable: false,
      draggable : false,
      modal: true,
      title : 'Удаление комментария',
      buttons: {
        'Удалить': function() {
          $(this).dialog('close');
          window.location = href;
        },
        'Отмена': function() {
          $(this).dialog('close');
        }
      }
    });
  });

//Скрипт подтверждения удаления аккаунта пользователя
  $('.delete-user').click(function (e) {

    e.preventDefault();

    var href = 'http://rocketfiles.zz.mu' + $('.delete-user-form').attr('action') + '?useraction=delete';

    $(".dialog").text('Вы уверены, что хотите навсегда удалить свой аккаунт?');

    $(".dialog").dialog({
      resizable: false,
      draggable : false,
      modal: true,
      title : 'Удаление аккаунта',
      buttons: {
        'Удалить': function() {
          $(this).dialog('close');
          window.location = href;
        },
        'Отмена': function() {
          $(this).dialog('close');
        }
      }
    });
  });

//Скрипт AJAX обновления информации пользователя
  $('.refresh').click(function (e) {

    e.preventDefault();

    $.ajax({

        url: $('.refresh-info').attr('action'),
        type: 'POST',
        data: $('.refresh-info').serialize(),
        success: function(response) {
          if(response.length != 0) {

            $(".dialog").text(response);

            $(".dialog").dialog({
                resizable: false,
                draggable : false,
                modal: true,
                title : 'Ошибка',
                buttons: {
                  Ok : function() {
                    $(this).dialog('close');
                  }
                }
              });

          } else {
            
            $(".dialog").text('Данные успешно изменены!');

            $(".dialog").dialog({
                resizable: false,
                draggable : false,
                modal: true,
                title : 'Результат',
                buttons: {
                  Ok : function() {
                    $(this).dialog('close');
                    location.reload();
                  }
                }
              });

          }
        }

      });
    });

//Скрипт добавления комментария
$('.add-comment-button').click(function (e) {

    e.preventDefault();

    $.ajax({

        url: $('.comment-form').attr('action'),
        type: 'POST',
        data: $('.comment-form').serialize(),
        success: function(response) {
          if(response.length != 0) {

            $(".dialog").text(response);

            $(".dialog").dialog({
                resizable: false,
                draggable : false,
                modal: true,
                title : 'Ошибка',
                buttons: {
                  Ok : function() {
                    $(this).dialog('close');
                  }
                }
              });

          } else {
            
            location.reload();
          }
        } 
      });
    });

//Скрипт AJAX загрузки файлов
$(function() {
    $('.main-file-upload')
        .formValidation({
            message: 'Поле заполнено неверно!',
            err: {
                container: '.main-upload-error'
            },
            fields: {
                uploadFile: {
                    validators: {
                      notEmpty: {
                            message: 'Выберите файл для загрузки!'
                        },
                        file: {
                            maxSize: 50*1024*1024,
                            message: 'Выберите файл размером не более 50 МБ'
                        }
                    }
                },
                terms: {
                  validators: {
                    notEmpty: {
                      message: 'Перед загрузкой файла необходимо принять условия соглашения'
                    }
                  }
                }

            }
        })
        .on('success.form.fv', function(e) {
            e.preventDefault();

            $('.input-group-btn').hide();

            $('.main-upload-progress').show();

            $('.main-upload-progress').progressbar({
              value: 0,
              change: function() {
                $('.upload-label').text($('.main-upload-progress').progressbar('value') + '%');
              },
              complete: function() {
                $('.upload-label').text("Готово!");
              }
            });

            var formData = new FormData($('.main-file-upload')[0]);

             $.ajax({
                xhr: function()
                {
                  var xhr = new window.XMLHttpRequest();
                  xhr.upload.addEventListener("progress", function(e){
                    if (e.lengthComputable) {
                      var percentComplete = Math.round((e.loaded / e.total) * 100);
                      $('.main-upload-progress').progressbar('option', 'value', percentComplete);
                    }
                  }, false);
                  return xhr;
                },
                type: "POST",
                  processData: false,
                  contentType: false,
                  url: '/files', 
                  data: formData,
                success: function(result){
                  if($.isNumeric(result)) {
                    window.location = 'http://rocketfiles.zz.mu/files/' + result;
                  } else {
                    $('.main-upload-error').html(result);
                    $('.main-upload-progress').hide();
                    $('.input-group-btn').show();
                  }
                },
              });
            });
          });

//Скрипт AJAX загрузки удаленных файлов
$(function() {
    $('.remote-file-upload')
        .formValidation({
            message: 'Поле заполнено неверно!',
            err: {
                container: '.remote-upload-error'
            },
            fields: {
                remote_url: {
                    validators: {
                      notEmpty: {
                            message: 'Укажите ссылку на файл.'
                        },
                        uri: {
                          message: 'Неверный формат URL'
                        }
                    }
                },
                terms: {
                  validators: {
                    notEmpty: {
                      message: 'Перед загрузкой файла необходимо принять условия соглашения'
                    }
                  }
                }

            }
        })
        .on('success.form.fv', function(e) {
            e.preventDefault();

            $('.remote-upload-field').hide();

            $('.remote-upload-progress').show();

            $('.remote-upload-progress').progressbar({
              value: false
            });

            $('.upload-label').text("Пожалуйста, подождите...");

            var formData = new FormData($('.remote-file-upload')[0]);

             $.ajax({
                xhr: function()
                {
                  var xhr = new window.XMLHttpRequest();
                  xhr.upload.addEventListener("progress", function(e){
                    if (e.lengthComputable) {
                      var percentComplete = Math.round((e.loaded / e.total) * 100);
                      $('.remote-upload-progress').progressbar('option', 'value', percentComplete);
                    }
                  }, false);
                  return xhr;
                },
                type: "POST",
                  processData: false,
                  contentType: false,
                  url: '/files', 
                  data: formData,
                success: function(result){
                  if($.isNumeric(result)) {
                    window.location = 'http://rocketfiles.zz.mu/files/' + result;
                  } else {
                    $('.remote-upload-error').html(result);
                    $('.remote-upload-progress').hide();
                    $('.remote-upload-field').show();
                  }
                },
              });
            });
          });

//Скрипт AJAX ответов от сервера для формы восстановления пароля
$(function() {
    $('.forgot-email')
        .formValidation({
            message: 'Поле заполнено неверно!',
            framework: 'bootstrap',
            err: {
                container: '.response'
            },
            fields: {
                email: {
                    validators: {
                      notEmpty: {
                            message: 'Введите email.'
                        },
                        emailAddress: {
                          message: 'Неверный email.'
                        }
                    }
                }

            }
        })
        .on('success.form.fv', function(e) {

            e.preventDefault();

             $.ajax({
                type: "POST",
                url: '/login/forgot', 
                data: $('.forgot-email').serialize(),
                  success: function(result){
                    if(result == 'Сообщение успешно отправлено!') {
                      $('.response, .help-block').css('color', 'green');
                    } else {
                      $('.response, .help-block').css('color', 'red');
                    }

                    $('.response').text(result);

                  }
                });
            });
          });

//Скрипт AJAX ответов от сервера для формы входа
$(function() {
    $('.login-form')
        .formValidation({
            message: 'Поле заполнено неверно!',
            framework: 'bootstrap',
            err: {
                container: '.response'
            },
            fields: {
                userlogin: {
                    validators: {
                      notEmpty: {
                            message: 'Введите логин.'
                        }
                    }
                },
                userpassword: {
                    validators: {
                      notEmpty: {
                            message: 'Введите пароль.'
                        }
                    }
                }
            }
        })
        .on('success.form.fv', function(e) {

            e.preventDefault();

             $.ajax({
                type: "POST",
                url: '/login', 
                data: $('.login-form').serialize(),
                  success: function(result){
                    if(result.length == 0) {
                      location.reload();
                    } else {
                      $('.response, .help-block').css('color', 'red');
                    }

                    $('.response').text(result);

                  }
                });
            });
          });

//Скрипт AJAX ответов для формы регистрации
$(function() {
    $('.register-form')
        .formValidation({
            message: 'Поле заполнено неверно!',
            framework: 'bootstrap',
            err: {
                container: '.response'
            },
            fields: {
                username: {
                    validators: {
                      notEmpty: {
                            message: 'Введите логин.'
                        }
                    }
                },
                email: {
                    validators: {
                      notEmpty: {
                            message: 'Введите email.'
                        },
                      emailAddress: {
                        message: 'Email неверен.'
                      }
                    }
                },
                password: {
                    validators: {
                      notEmpty: {
                            message: 'Введите пароль.'
                        }
                    }
                },
                passconfirm: {
                    validators: {
                      notEmpty: {
                            message: 'Введите подтверждение пароля.'
                        }
                    }
                }
            }
        })
        .on('success.form.fv', function(e) {

            e.preventDefault();

             $.ajax({
                type: "POST",
                url: '/register', 
                data: $('.register-form').serialize(),
                  success: function(result){
                    if(result == 'Пользователь успешно зарегистрирован! Вы можете войти используя ваш логин и пароль.') {
                      $('.response, .help-block').css('color', 'green');
                    } else {
                      $('.response, .help-block').css('color', 'red');
                    }

                    $('.response').text(result);

                  }
                });
            });
          });

//Скрипт для формы входа в хедере
$('.header-login-button').click(function (e) {

    e.preventDefault();

    $.ajax({

        url: '/login',
        type: 'POST',
        data: $('.menu-login').serialize(),
        success: function(response) {
          if(response.length != 0) {

            $(".dialog").text(response);

            $(".dialog").dialog({
                resizable: false,
                draggable : false,
                modal: true,
                title : 'Ошибка',
                buttons: {
                  Ok : function() {
                    $(this).dialog('close');
                  }
                }
              });

          } else {
            
            location.reload();
          }
        } 
      });
    });

//Скрипт для AJAX отправки жалобы
$('.make-appeal').click(function (e) {

    e.preventDefault();

    $(".appeal").dialog({
        resizable: false,
        width: 500,
        draggable : false,
        modal: true,
        title : 'Жалоба',
        buttons: {
          'Отправить' : function() {

            $(this).dialog('close');

            $.ajax({

              url: '/files/appeal',
              type: 'POST',
              data: $('.appeal-form').serialize(),
              success: function(response) {
                if(response.length != 0) {

                  $(".dialog").text(response);

                  $(".dialog").dialog({
                      resizable: false,
                      draggable : false,
                      modal: true,
                      title : 'Результат',
                      buttons: {
                        Ok : function() {
                          $(this).dialog('close');
                          $('.appeal-form').trigger('reset');
                        }
                      }
                    });
                } 
              } 
            });
          },
          'Отмена' : function() {
            $(this).dialog('close');
          }
        }
      });
    });

});