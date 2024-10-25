var user = function ($rootScope, $scope, $http) {
  /**
   * Password Edit
   * @param  {[type]} event [description]
   * @return {[type]}       [description]
   */
  $scope.optionsUpdate = function (event) {
    event.preventDefault();

    if (! $rootScope.submitted) {
      $scope.easyLoading('#popup', 'dark', true);

      var action = $scope.getActionAttribute(event);

      CSRF_TOKEN = $scope.csrf_token();

      var data = $.param($scope.formData);

      $http.put(action, data, {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': CSRF_TOKEN
        }
      })
        .then(function (response) {
          $scope.errors = {};

          if (response.status === 200) {
            $rootScope.submitted = false;

            delete $scope.formData.old_password;
            delete $scope.formData.password_new;
            delete $scope.formData.password_new_confirmation;
          }

          $scope.showAlert(response.data.option, response.status, response.data.message);
        }, function (response) {
          $rootScope.submitted = false;

          if (response.status == 422) {
            $scope.errors = response.data;
          } else if (response.status == 500) {
            console.log('Unauthorized action.');
          } else {
            $scope.showAlert(response.data.option, response.status, response.data.message);
          }
        }).finally(function () {
          $scope.easyLoading('#popup', '', false);
        });
    }
  };

  $scope.showAlert = function (option, status, message) {
    var alert = status === 200 ? 'alert-success' : 'alert-danger';
    
    angular.element('#alert-' + option).removeClass('alert-success alert-danger').addClass(alert).text(message);
  };

  $scope.delete = function (event) {
    event.preventDefault();

    CSRF_TOKEN    = $scope.csrf_token();
    var url       = event.target.attributes.href.value;
    var full_name = angular.element(event.target).data('full-name');
    var active    = angular.element(event.target).data('active');

    bootbox.confirm({
        message: 'Esta seguro en ' + ( active ? 'habilitar' : 'deshabilitar' ) + ' al usuario ' + full_name + '?',
        buttons: {
            confirm: {
                label: 'Si',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
              $http.delete(url, {
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded',
                  'X-CSRF-TOKEN': CSRF_TOKEN
                }
              })
               .then(function (response) {
                  if (response.status == 200) {
                    if ('location' in response.data) {
                      $scope.redirect(response.data.location);
                    }
                  }
                }, function (response) {
                  console.log('Unauthorized action.');
                });
            }
        }
    });
  };

  $scope.createUser = function () {
    $('#user_type').change(function (e) {
      var user_type_codename = angular.element('option:selected', this).data('codename');
      var ctn_profiles       = angular.element('#ctn_profiles');
      var permissions        = angular.element('#permissions');
      
      // permissions.children('option:selected').removeAttr('selected');
      
      if (user_type_codename === 'UST') {
        ctn_profiles.show();

        permissions.children('option[data-codename="admin"]').hide();
        permissions.children('option[data-codename!="admin"]').show();
      } else {
        ctn_profiles.hide();

        permissions.children('option[data-codename!="admin"]').hide();
        permissions.children('option[data-codename="admin"]').show();
      }
    });

    $('#profile').change(function (e) {
      var profile       = angular.element('option:selected', this).data('codename');
      var ctn_companies = angular.element('#ctn_companies');
      
      if (profile === 'COP') {
        ctn_companies.show();
      } else {
        ctn_companies.hide();
      }
    });

    $('#city').change(function (e) {
      var city_codename = angular.element('option:selected', this).data('codename');
      var agencies      = angular.element('#agency');

      agencies.children('option[data-codename!="' + city_codename + '"]').hide();
      agencies.children('option[data-codename="' + city_codename + '"], option[data-codename=""]').show();
    });

    angular.element('#user_type').triggerHandler('change');
    angular.element('#profile').triggerHandler('change');
    angular.element('#city').triggerHandler('change');
  };

  /**
   * Password strenght
   * 
   * @return {[type]} [description]
   */
  $scope.passwordSM = function () {
    $scope.minChar = 5;
    $scope.maxChar = 10;

    var url = $('#url-setting-get').prop('value');

    $http.get(url, {})
      .then(function (response) {
          if (response.status == 200) {
            var data     = response.data;
            var settings = data.settings;

            angular.forEach(settings, function(item, key){
              if (item.codename === 'min_char') {
                $scope.minChar = item.logs[0].value;
              }

              if (item.codename === 'max_char') {
                $scope.maxChar = item.logs[0].value;
              }
            });

            $scope.initPasswordSM();
          }
        }, function(response) {
          console.log(response.data);
        }).finally(function () {
          // $scope.easyLoading('body', '', false);
        });
  };


  $scope.initPasswordSM = function () {
    i18next.init({
      lng: 'es',
      resources: {
        es: {
          translation: {
            "wordMinLength": "Tu contraseña es demasiado corta",
            "wordMaxLength": "Tu contraseña es muy larga",
            "wordInvalidChar": "Tu contraseña contiene un carácter no válido",
            "wordNotEmail": "No uses tu email como tu contraseña",
            "wordSimilarToUsername": "Tu contraseña no puede contener tu nombre de usuario",
            "wordTwoCharacterClasses": "Mezcla diferentes clases de caracteres",
            "wordRepetitions": "Demasiadas repeticiones",
            "wordSequences": "Tu contraseña contiene secuencias",
            "errorList": "Errores:",
            "veryWeak": "Muy Débil",
            "weak": "Débil",
            "normal": "Normal",
            "medium": "Media",
            "strong": "Fuerte",
            "veryStrong": "Muy Fuerte"
          }
        }
      }
    }, function () {
      var options = {};

      options.ui = {
        container: "#pwd-container",
        showVerdictsInsideProgressBar: true,
        viewports: {
          progress: ".pwstrength_viewport_progress"
        },
        showErrors: true,
        showPopover: true,
        popoverPlacement: 'top',
        // showStatus: true,
        // showScore: true,
      };

      options.common = {
        minChar: $scope.minChar,
        maxChar: $scope.maxChar,
        debug: false,
        onLoad: function () {
          // $('#messages').text('Start typing password');
        },
        onKeyUp: function (evt, data) {
          // console.log("Current length: " + $(evt.target).val().length + " and score: " + data.score);
          // console.log(data);
          var form = $(evt.target).closest('form');
          
          if (data.verdictLevel > 3) {
            $(form).find(':submit').prop('disabled', false);
          } else {
            $(form).find(':submit').prop('disabled', true);
          }
        }
      };

      options.rules = {
        activated: {
          wordSequences: false,
          wordMaxLength: true,
          wordRepetitions: true,
        }
      };

      $('#password').pwstrength(options);
    });
  };


  $scope.passwordUpdate = function (event) {
    event.preventDefault();

    if (! $rootScope.submitted) {
      delete $scope.formData.mother_last_name;
      // $scope.easyLoading('#popup', 'dark', true);

      var action = $scope.getActionAttribute(event);
      CSRF_TOKEN = $scope.csrf_token();
      var data   = $.param($scope.formData);

      $http.put(action, data, {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': CSRF_TOKEN
        }
      })
        .then(function (response) {
          $scope.errors = {};

          if (response.status === 200) {
            if ('location' in response.data) {
              $rootScope.submitted = false;

              delete $scope.formData.old_password;
              delete $scope.formData.password;
              delete $scope.formData.password_confirmation;

              $scope.redirect(response.data.location);
            } else {
              $rootScope.submitted = false;
            }
          }
        }, function (response) {
          $rootScope.submitted = false;

          if (response.status == 422) {
            $scope.errors = response.data;
          } else if (response.status == 500) {
            console.log('Unauthorized action.');
          }
        }).finally(function () {
          // $scope.easyLoading('#popup', '', false);
        });
    }
  };

};

module.exports.user = user;