var detail = function ($rootScope, $scope, $http) {
  /**
   * Vehicle create form
   * @param  {[type]} event [description]
   * @return {[type]}       [description]
   */
  $scope.create = function (event) {
    event.preventDefault();

    $scope.easyLoading('body', 'dark', true);
    
    
    var url = event.target.action+'?nro_ruat='+$('#nro_ruat').val()+'&type_data='+$('#type_data').val();
    
    $http.get(url, {})
      .then(function (response) {
        if (response.status == 200) {
          var data = response.data;

          $rootScope.data.types      = data.types;
          //$rootScope.data.makes      = data.makes;
          $rootScope.data.categories = data.categories;
          $rootScope.amount_max      = data.amount_max;
          $rootScope.amount_max_ca      = data.amount_max_ca;
          $rootScope.amount_max_cb      = data.amount_max_cb;
          $rootScope.currency        = data.currency;
          $rootScope.exchange_rate   = data.exchange_rate;

          $scope.formData.vehicle_type  = data.detail.vehicle_type;
          //$scope.formData.vehicle_make  = data.detail.vehicle_make;
          //$scope.formData.vehicle_model = data.detail.vehicle_model;
          $scope.formData.year          = data.detail.year;
          $scope.formData.year_old      = data.detail.year;
          $scope.formData.license_plate = data.detail.license_plate;
          $scope.formData.use           = data.detail.use;
          $scope.formData.mileage       = data.detail.mileage ? '1' : '0';
          $scope.formData.insured_value = data.detail.insured_value;
          $scope.formData.own_damage_insurance = data.detail.own_damage_insurance;
          
          $scope.formData.engine            = data.detail.engine;
          $scope.formData.chassis           = data.detail.chassis;
          $scope.formData.color             = data.detail.color;
          $scope.formData.tonnage_capacity  = data.detail.tonnage_capacity;
          $scope.formData.seat_number       = data.detail.seat_number;
          $scope.formData.circulation_plaza = data.detail.circulation_plaza;
          $scope.formData.displacement      = data.detail.displacement;
          $scope.formData.origin            = data.detail.origin;
          $scope.formData.brand             = data.detail.brand;
          $scope.formData.brand_id          = data.detail.brand_id;
          $scope.formData.model             = data.detail.model;
          $scope.formData.currency          = data.detail.currency;
          $scope.formData.guarantee_id      = data.detail.guarantee_id;

          if ($scope.formData.year < data.year_max) {
            $scope.formData.year = 'old';
          }
          $scope.errors.message_search  = false;
          $scope.errors.message_ws      = false;
          $scope.popup(data.payload);

          angular.element('#popup').on('shown.bs.modal', function (e) {
          angular.element('#category option:not(:selected)').prop('disabled', true);
          //angular.element('#vehicle-make').triggerHandler('change');
          //angular.element('#vehicle-model').triggerHandler('change');
          angular.element('#year option[value=' + $scope.formData.year + ']').prop('selected', true);
          angular.element('#year').triggerHandler('change');
          });
        }
      }, function(response){
          console.log(response.data);
          $rootScope.submitted = false;
          
          if (response.status == 500) 
            console.log('Unauthorized action.');
          
          if(response.status == 401)
            $scope.errors.message_search = true;
        else
            $scope.errors.message_search = false;

          if(response.status == 402)
            $scope.errors.message_ws = true;
        else
            $scope.errors.message_ws = false;

      }).finally(function () {
        $scope.easyLoading('body', '', false);
      });
  };

  /**
   * Vehicle store
   * @param  {[type]} event [description]
   * @return {[type]}       [description]
   */
  $scope.store = function (event) {
    event.preventDefault();

    if (! $rootScope.submitted) {
      $rootScope.submitted = true;

      $scope.easyLoading('#popup', 'dark', true);

      var action = $scope.getActionAttribute(event);

      CSRF_TOKEN = $scope.csrf_token();

      var data = $.param($scope.formData);

      $http.post(action, data, {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': CSRF_TOKEN
        }
      })
        .then(function (response) {
          $scope.errors = {};

          if (response.status == 200) {
            $scope.success = { vehicle: true };

            $scope.redirect(response.data.location);
          }
        }, function (response) {
          $rootScope.submitted = false;
          
          if (response.status == 422) {
            $scope.errors = response.data;
          } else if (status == 500) {
            console.log('Unauthorized action.');
          } else if (response.status == 428){
            $scope.errors = {};
            
            messageAction('error', response.data.reason);
          }
        }).finally(function () {
          $scope.easyLoading('#popup', '', false);
        });
    }
  };

  /**
   * Year old
   * @param  {[type]} value    [description]
   * @param  {[type]} oldValue [description]
   * @param  {[type]} scope)   {               if (value ! [description]
   * @return {[type]}          [description]
   */
  $scope.$watch('formData.year', function(value, oldValue, scope) {
    if (value == 'old') {
      $scope.year_old = true;
    } else {
      $scope.year_old = false;
    }
  });


  /**
   * Category by Vehicle Type
   * @param  {[type]} value    [description]
   * @param  {[type]} oldValue [description]
   * @param  {[type]} scope)   {               if (value ! [description]
   * @return {[type]}          [description]
   */
  $scope.$watch('formData.vehicle_type', function(value, oldValue, scope) {
    if (value != null) {
      $scope.formData.category = value.category;
      angular.element('#category option:not(:selected)').prop('disabled', true);
    }
  });

  $scope.delete = function (event) {
    event.preventDefault();

    var url     = event.target.attributes.href.value;
    var message = 'Desea eliminar el registro de este vehículo?';
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

  /**
   * Vehicle edit form
   * @param  {[type]} event [description]
   * @return {[type]}       [description]
   */
  $scope.edit = function (event) {
    event.preventDefault();

    $scope.easyLoading('body', 'dark', true);

    var url = event.target.attributes.href.value;

    $http.get(url, {})
      .then(function (response) {
          if (response.status == 200) {
            var data = response.data;

            $rootScope.data.types      = data.types;
            //$rootScope.data.makes      = data.makes;
            $rootScope.data.categories = data.categories;
            $rootScope.amount_max      = data.amount_max;
            $rootScope.amount_max_ca      = data.amount_max_ca;
            $rootScope.amount_max_cb      = data.amount_max_cb;
            $rootScope.currency        = data.currency;
            $rootScope.exchange_rate   = data.exchange_rate;

            $scope.formData.vehicle_type  = data.detail.vehicle_type;
            //$scope.formData.vehicle_make  = data.detail.vehicle_make;
            //$scope.formData.vehicle_model = data.detail.vehicle_model;
            $scope.formData.year          = data.detail.year;
            $scope.formData.year_old      = data.detail.year;
            $scope.formData.license_plate = data.detail.license_plate;
            $scope.formData.use           = data.detail.use;
            $scope.formData.mileage       = data.detail.mileage ? '1' : '0';
            $scope.formData.insured_value = data.detail.insured_value;
            $scope.formData.own_damage_insurance = data.detail.own_damage_insurance;
            
            $scope.formData.engine            = data.detail.engine;
            $scope.formData.chassis           = data.detail.chassis;
            $scope.formData.color             = data.detail.color;
            $scope.formData.tonnage_capacity  = data.detail.tonnage_capacity;
            $scope.formData.seat_number       = data.detail.seat_number;
            $scope.formData.circulation_plaza = data.detail.circulation_plaza;
            $scope.formData.displacement      = data.detail.displacement;
            $scope.formData.origin            = data.detail.origin;
            $scope.formData.brand             = data.detail.brand;
            $scope.formData.brand_id          = data.detail.brand_id;
            $scope.formData.model             = data.detail.model;
            $scope.formData.currency          = data.detail.currency;
            
            if ($scope.formData.year < data.year_max) {
              $scope.formData.year = 'old';
            }

            $scope.popup(data.payload);

            angular.element('#popup').on('shown.bs.modal', function (e) {
              angular.element('#category option:not(:selected)').prop('disabled', true);
              //angular.element('#vehicle-make').triggerHandler('change');
              //angular.element('#vehicle-model').triggerHandler('change');
              angular.element('#year option[value=' + $scope.formData.year + ']').prop('selected', true);
              angular.element('#year').triggerHandler('change');
            });
          }
        }, function(response){
          console.log(response);
        }).finally(function () {
          $scope.easyLoading('body', '', false);
        });
  };

  /**
   * Insured Value
   * @param  {[type]} value    [description]
   * @param  {[type]} oldValue [description]
   * @param  {[type]} scope)   {               if (value [description]
   * @return {[type]}          [description]
   */
  $scope.$watch('formData.insured_value', function(value, oldValue, scope) {
    limitAmountValitador(value, oldValue, scope);
  });

  $scope.$watch('formData.category', function(value, oldValue, scope) {
    insured = $scope.formData.insured_value;
    limitAmountValitador(insured, oldValue, scope);
  });

  function limitAmountValitador(value, oldValue, scope){
    if (scope.currency == 'BS') {
        value = value / scope.exchange_rate.bs_value;
      }
      
      scope.insured_value_ca = false;
      scope.insured_value_cb = false;

      if($scope.formData.category){
        if($scope.formData.category.category == 'CA'){
          if(value > (scope.amount_max_ca/1)){
            scope.insured_value_ca = true;
            scope.btn_save = false;
          }else{
            scope.insured_value_ca = false;
            scope.btn_save = true;
          } 
        }else{
          if(value > (scope.amount_max_cb/1)){
            scope.insured_value_cb = true;
            scope.btn_save = false;
          }else{
            scope.insured_value_cb = false;
            scope.btn_save = true;
          }
        }
      }
  }

  /**
   * Vehicle update
   * @param  {[type]} event [description]
   * @return {[type]}       [description]
   */
  $scope.update = function (event) {
    event.preventDefault();

    if (! $rootScope.submitted) {
      $rootScope.submitted = true;

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
          
          if (response.status == 200) {
            $scope.success = { vehicle: true };

            $scope.redirect(response.data.location);
          }
        }, function (response) {
          $rootScope.submitted = false;

          if (response.status == 422) {
            $scope.errors = response.data;
          } else if (response.status == 500) {
            console.log('Unauthorized action.');
          } else if (response.status == 428){
            $scope.errors = {};
            
            messageAction('error', response.data.reason);
          }
        }).finally(function () {
          $scope.easyLoading('#popup', '', false);
        });
    }
  };

  /**
   * Vehicle edit issuance form
   * @param  {[type]} event [description]
   * @return {[type]}       [description]
   */
  $scope.editIssuance = function (event) {
    event.preventDefault();

    $scope.easyLoading('body', 'dark', true);

    var url = event.target.attributes.href.value;

    $http.get(url, {})
      .then(function (response) {
          if (response.status == 200) {
            var data = response.data;

            $rootScope.data.types         = data.types;
            //$rootScope.data.makes         = data.makes;
            $rootScope.data.categories    = data.categories;
            $rootScope.data.insured_value = data.detail.insured_value;
            $rootScope.data.premium       = data.detail.premium;
            $rootScope.currency           = data.currency;
            $rootScope.amount_max         = data.amount_max;
            $rootScope.exchange_rate      = data.exchange_rate;
            
            $scope.formData.vehicle_type     = data.detail.vehicle_type;
            //$scope.formData.vehicle_make     = data.detail.vehicle_make;
            //$scope.formData.vehicle_model    = data.detail.vehicle_model;
            $scope.formData.year             = data.detail.year;
            $scope.formData.year_old         = data.detail.year;
            $scope.formData.license_plate    = data.detail.license_plate;
            $scope.formData.use              = data.detail.use;
            $scope.formData.mileage          = data.detail.mileage ? '1' : '0';
            $scope.formData.color            = data.detail.color;
            $scope.formData.engine           = data.detail.engine;
            $scope.formData.chassis          = data.detail.chassis;
            $scope.formData.tonnage_capacity = data.detail.tonnage_capacity;
            $scope.formData.seat_number      = data.detail.seat_number;
            $scope.formData.insured_value    = data.detail.insured_value;
            $scope.formData.brand             = data.detail.brand;
            $scope.formData.brand_id          = data.detail.brand_id;
            $scope.formData.model             = data.detail.model;

            if ($scope.formData.year < data.year_max) {
              $scope.formData.year = 'old';
            }
            
            angular.element('#popup').on('shown.bs.modal', function (e) {
              angular.element('#category option:not(:selected)').prop('disabled', true);
              //angular.element('#vehicle-make').triggerHandler('change');
              //angular.element('#vehicle-model').triggerHandler('change');
              angular.element('#year option[value=' + $scope.formData.year + ']').prop('selected', true).triggerHandler('change');
              angular.element('#year').triggerHandler('change');
            });

            $scope.popup(data.payload);
          }
        }, function(response){
          console.log(response);
        }).finally(function () {
          $scope.easyLoading('body', '', false);
        });
  };

  /**
   * Vehicle update issuance
   * @param  {[type]} event [description]
   * @return {[type]}       [description]
   */
  $scope.updateIssuance = function (event) {
    event.preventDefault();
    
    if (! $rootScope.submitted) {
      $rootScope.submitted = true;

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
          
          if (response.status == 200) {
            $scope.success = { vehicle: true };

            $scope.redirect(response.data.location);
          }
        }, function (response) {
          $rootScope.submitted = false;

          if (response.status == 422) {
            $scope.errors = response.data;
          } else if (response.status == 500) {
            console.log('Unauthorized action.');
          } else if (response.status == 428){
            $scope.errors = {};
            
            messageAction('error', response.data.reason);
          }
        }).finally(function () {
          $scope.easyLoading('#popup', '', false);
        });
    }
  };

};

module.exports.detail = detail;