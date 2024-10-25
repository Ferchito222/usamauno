var extra = function ($rootScope, $scope, $http) {
  $scope.$watch('type', function(value, oldValue, scope) {
    scope.daily    = false;
    scope.monthly  = false;
    scope.campaign = false;
    scope.type_sl  = false;

    if (value == 'daily') {
      scope.daily    = true;
    } else if (value == 'monthly') {
      scope.monthly  = true;
    } else if (value == 'campaign') {
      scope.campaign = true;
    } 
    
    if (value == '' || (typeof value == 'undefined')) {
      scope.type_sl  = true;
    }
  });

  $scope.exportXls = function (event) {
    event.preventDefault();

    $('#export').trigger('submit');
  };
  
  /**
   * imprime html por bloque id
   * @param {type} event
   * @param {type} route ruta controlador
   * @param {type} html_id id html
   * @returns {undefined}
   */
  $scope.printHtmlGet = function (event, route, html_id) {
    event.preventDefault();
    var href = route;

    $http.get(href, {

    }).then(function (response) {
        if (response.status == 200)
            angular.element('#'+html_id).html(response.data.payload);
      }, function (response) {
        console.log(response.data);
      }).finally(function () {
        //$scope.easyLoading('body', '', false);
      });
  };
  
  $scope.popupHtmlGet = function (event, route) {
    event.preventDefault();
    $scope.easyLoading('body', 'dark', true);
    var href = route;

    $http.get(href, {

    }).then(function (response) {
        if (response.status == 200)
            $scope.popup2(response.data.payload);
      }, function (response) {
        console.log(response.data);
      }).finally(function () {
        $scope.easyLoading('body', '', false);
      });
  };
  
  $scope.angularForm = function (event) {
    event.preventDefault();

    $scope.easyLoading('body', 'dark', true);

    var href = event.target.href;

    $http.get(href, {

    }).then(function (response) {
        if (response.status == 200) {
        
          if (typeof response.data.objects !== 'undefined')
              $rootScope.data = response.data.objects;
          
          
          angular.forEach(response.data.fields, function(value, key){
            $scope.formData[key] = value;
         });
          
          $scope.popup(response.data.payload);          
        }
      }, function (response) {
        console.log(response.data);
      }).finally(function () {
        $scope.easyLoading('body', '', false);
      });
  };
  
  $scope.store = function (event) {
    event.preventDefault();

    if (! $rootScope.submitted) {
      $rootScope.submitted = true;

      $scope.easyLoading('#popup', 'dark', true);

      var action = $scope.getActionAttribute(event);

      CSRF_TOKEN = $scope.csrf_token();

      $http({
        method: 'POST',
        url: action,
        data: $.param($scope.formData),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': CSRF_TOKEN
        }
      }).then(function (response) {
          $scope.errors = {};

          if (response.status == 200) {
            $scope.success = { beneficiary: true };
            $scope.redirect(response.data.location);
          }
        }, function (response) {
          $rootScope.submitted = false;

          if (response.status == 422) {
            $scope.errors = response.data;
          } else if (response.status == 500) {
            console.log('Unauthorized action.');
          }

          // console.log(err);
        }).finally(function () {
          $scope.easyLoading('#popup', '', false);
        });
    }
  };
  
  $scope.update = function (event) {
    event.preventDefault();

    if (! $rootScope.submitted) {
      $rootScope.submitted = true;
      
      $scope.easyLoading('#popup', 'dark', true);

      var action = $scope.getActionAttribute(event);

      CSRF_TOKEN = $scope.csrf_token();

      $http({
        method: 'PUT',
        url: action,
        data: $.param($scope.formData),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': CSRF_TOKEN
        }
      }).then(function (response) {
          $scope.errors = {};

          if (response.status == 200) {
            $scope.success = { beneficiary: true };
            $scope.redirect(response.data.location);
          }
        }, function (response) {
          $rootScope.submitted = false;

          if (response.status == 422) {
            $scope.errors = response.data;
          } else if (response.status == 500) {
            console.log('Unauthorized action.');
          }

          // console.log(err);
        }).finally(function () {
          $scope.easyLoading('#popup', '', false);
        });
    }
  };
  
  $scope.delete = function (event) {
    event.preventDefault();

    var url     = event.target.attributes.href.value;
    var message = 'Desea eliminar el registro?';
    CSRF_TOKEN  = $scope.csrf_token();
    
    bootbox.confirm(message, function(result) {
      if (result) {
        $http.delete(url, {
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': CSRF_TOKEN
          }
        })
         .then(function (response) {
            if (response.status == 200) {
              $scope.redirect(response.data.location);
            }
          }, function (response) {
            console.log('Unauthorized action.');
          });
      }
    });
  };
  
};

module.exports.extra = extra;
