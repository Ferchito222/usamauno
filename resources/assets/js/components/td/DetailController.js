var detail = function ($rootScope, $scope, $http) {
  /**
   * risk create form
   * @param  {[type]} event [description]
   * @return {[type]}       [description]
   */
  $scope.create = function (event) {
    event.preventDefault();

    $scope.easyLoading('body', 'dark', true);

    var url = event.target.action+'?nro_folio_real='+$('#nro_folio_real').val();

    $http.get(url, {})
      .then(function (response) {
        if (response.status == 200) {
          var data = response.data;

          $rootScope.data.property_types    = data.property_types;
          $rootScope.data.property_uses     = data.property_uses;
          $rootScope.data.property_states   = data.property_states;
          $rootScope.data.cities            = data.cities;
          $rootScope.amount_max             = data.amount_max;
          $rootScope.currency               = data.currency;
          $rootScope.exchange_rate          = data.exchange_rate;

          var matter = ''
            angular.forEach(data.property_types, function(value, key) {
              if(value.id == data.detail.matter_insured)
                matter = value; 
            });
            var use_detail = ''
            angular.forEach(data.property_uses, function(value, key) {
              if(value.id == data.detail.use)
                use_detail = value; 
            });
            var state_detail = ''
            angular.forEach(data.property_states, function(value, key) {
              if(value.id == data.detail.state)
                state_detail = value; 
            });
            city_detail = ''
            angular.forEach(data.cities, function(value, key) {
              if(value.slug == data.detail.city)
                city_detail = value; 
            });

            $scope.formData.matter_insured        = matter;
            $scope.formData.use                   = use_detail;
            $scope.formData.state                 = state_detail;
            $scope.formData.city                  = city_detail;
            $scope.formData.matter_description    = data.detail.matter_description;
            $scope.formData.number                = data.detail.number;
            $scope.formData.insured_value         = data.detail.insured_value;
            $scope.formData.insured_value_od      = data.detail.insured_value;
            $scope.formData.construction_value    = data.detail.construction_value;
            $scope.formData.remodeling_value      = data.detail.remodeling_value;
            $scope.formData.construction_term     = data.detail.construction_term;
            $scope.formData.zone                  = data.detail.zone;
            $scope.formData.locality              = data.detail.locality;
            $scope.formData.address               = data.detail.address;
            $scope.formData.nro_folio_real        = data.detail.nro_folio_real;

            $scope.formData.insolute_balance      = data.header.insolute_balance;
            $scope.formData.edit_form             = 1;
            $scope.formData.currency_value        = data.header.currency;
            $scope.formData.exchange              = $rootScope.exchange_rate.bs_value;
            $scope.formData.currency              = data.detail.currency;
            
            $scope.errors.message_search  = false;
            $scope.errors.message_ws      = false;
            $scope.popup(data.payload);
            messageInsured();
            bloqueaMatterState();
          
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
          //$rootScope.submitted = false;
          if (response.status == 200) {
            $scope.success = { result: true };

            $scope.redirect(response.data.location);
          }
        }, function (response) {
          $rootScope.submitted = false;
          
          if (response.status == 422) {
            $scope.errors = response.data;
          } else if (status == 500) {
            console.log('Unauthorized action.');
          } else if (response.status == 428) {
                        $scope.errors = {};
                        messageAction('error', response.data.reason);
                    }
        }).finally(function () {
          $scope.easyLoading('#popup', '', false);
        });
    }
  };

$scope.$watch('formData.matter_insured', function(value, oldValue, scope) {
    angular.element('#use option[value=""]').prop('selected', true);
    if(value.id != ''){
      if(value.id == 'PR'){
        angular.element('#use option[value="OT"]').prop('disabled', true);
        angular.element('#use option[value="ID"]').prop('disabled', false);
        angular.element('#use option[value="IP"]').prop('disabled', false);
        angular.element('#use option[value="IC"]').prop('disabled', false);
 
        angular.element('.number_field').hide();
        angular.element('#number').val('');
        angular.element('#state').prop('disabled', false);
        angular.element('#state').val('');
      }else{
        angular.element('#use option[value="OT"]').prop('disabled', false);
        angular.element('#use option[value="ID"]').prop('disabled', true);
        angular.element('#use option[value="IP"]').prop('disabled', true);
        angular.element('#use option[value="IC"]').prop('disabled', true);

        angular.element('.number_field').show();
        angular.element('#number').val('');
        angular.element('#state').prop('disabled', true);
        angular.element('#state').val('');

        angular.element('.construct').hide();
        angular.element('.construction_term_block').hide();
        angular.element('.insured').show();
      }
    }
  });
$scope.$watch('formData.state', function(value, oldValue, scope) {
    scope.state_remotelation = false;
    scope.state_construction = false;
    if(value.id == 'P'){//remodelacion
      angular.element('.construct').show();
      angular.element('.construction_term_block').show();
      angular.element('.insured').hide();
      
      $scope.formData.remodeling_value = $scope.formData.insolute_balance;
      scope.state_remotelation = true;
      
    }else if(value.id == 'C'){//construccion
      angular.element('.construct').hide();
      angular.element('.construction_term_block').show();
      angular.element('.insured').show();
      
      $scope.formData.construction_value=0;
      if($scope.formData.edit_form == 0)
      $scope.formData.insured_value = $scope.formData.insolute_balance;  
    else
      $scope.formData.insured_value = $scope.formData.insured_value;  
      
      scope.state_construction = true;
      
    }else{//terminado
      angular.element('.construct').hide();
      angular.element('.construction_term_block').hide();
      angular.element('.insured').show();
      if($scope.formData.edit_form == 0){
        $scope.formData.construction_value=0;  
        $scope.formData.insured_value = 0;
      }else{
        $scope.formData.insured_value = $scope.formData.insured_value_od;
      }
    }
    validaConstructionTerm(scope);
  });

  $scope.delete = function (event) {
    event.preventDefault();
    var url     = event.target.attributes.href.value;
    var message = 'Desea eliminar el riesgo?';
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

            $rootScope.data.property_types    = data.property_types;
            $rootScope.data.property_uses     = data.property_uses;
            $rootScope.data.property_states   = data.property_states;
            $rootScope.data.cities            = data.cities;
            $rootScope.amount_max             = data.amount_max;
            $rootScope.currency               = data.currency;
            $rootScope.exchange_rate          = data.exchange_rate;
            
            var matter = ''
            angular.forEach(data.property_types, function(value, key) {
              if(value.id == data.detail.matter_insured)
                matter = value; 
            });
            var use_detail = ''
            angular.forEach(data.property_uses, function(value, key) {
              if(value.id == data.detail.use)
                use_detail = value; 
            });
            var state_detail = ''
            angular.forEach(data.property_states, function(value, key) {
              if(value.id == data.detail.state)
                state_detail = value; 
            });
            city_detail = ''
            angular.forEach(data.cities, function(value, key) {
              if(value.slug == data.detail.city)
                city_detail = value; 
            });

            $scope.formData.matter_insured        = matter;
            $scope.formData.use                   = use_detail;
            $scope.formData.state                 = state_detail;
            $scope.formData.city                  = city_detail;
            $scope.formData.matter_description    = data.detail.matter_description;
            $scope.formData.number                = data.detail.number;
            $scope.formData.insured_value         = data.detail.insured_value;
            $scope.formData.insured_value_od      = data.detail.insured_value;
            $scope.formData.construction_value    = data.detail.construction_value;
            $scope.formData.remodeling_value      = data.detail.remodeling_value;
            $scope.formData.construction_term     = data.detail.construction_term;
            $scope.formData.zone                  = data.detail.zone;
            $scope.formData.locality              = data.detail.locality;
            $scope.formData.address               = data.detail.address;
            $scope.formData.nro_folio_real        = data.detail.nro_folio_real;

            $scope.formData.insolute_balance      = data.header.insolute_balance;
            $scope.formData.edit_form             = 1;
            $scope.formData.currency_value        = data.header.currency;
            $scope.formData.exchange              = $rootScope.exchange_rate.bs_value;
            $scope.formData.currency              = data.detail.currency;

            $scope.popup(data.payload);
            messageInsured();
            bloqueaMatterState();

          }
        }, function(response){
          console.log(response);
        }).finally(function () {
          $scope.easyLoading('body', '', false);
        });
  };

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
          //$rootScope.submitted = false;
          if (response.status == 200) {
            $scope.success = { result: true };

            $scope.redirect(response.data.location);
          }
        }, function (response) {
          $rootScope.submitted = false;

          if (response.status == 422) {
            $scope.errors = response.data;
          } else if (response.status == 500) {
            console.log('Unauthorized action.');
          } else if (response.status == 428) {
                        $scope.errors = {};
                        messageAction('error', response.data.reason);
                    }
        }).finally(function () {
          $scope.easyLoading('#popup', '', false);
        });
    }
  };
  
  /**
   * Insured Value
   * @param  {[type]} value    [description]
   * @param  {[type]} oldValue [description]
   * @param  {[type]} scope)   {               if (value [description]
   * @return {[type]}          [description]
   */
$scope.$watch('formData.construction_value', function(value, oldValue, scope) {
  messageInsured();
  validaConstructionTerm(scope)
});

$scope.$watch('formData.remodeling_value', function(value, oldValue, scope) {
  messageInsured(scope);
  validaConstructionTerm(scope);
});

$scope.$watch('formData.insured_value', function(value, oldValue, scope) {
  
  messageInsured(scope);
  validaConstructionTerm(scope);
});

$scope.$watch('formData.matter_insured', function(value, oldValue, scope) {
  messageInsured(scope);
  validaConstructionTerm(scope)
});

$scope.$watch('formData.use', function(value, oldValue, scope) {
  
  if(value.id == 'IP'){
    scope.btn_save = false;
  }else{
    scope.btn_save = true;
  }
  messageInsured(scope);
  validaConstructionTerm(scope)
});
  
  function messageInsured(scope){
      $scope.$watch('formData.insured_value', function(value, oldValue, scope) {
       /**if(angular.element('#state').val() == 'P'){
          var construction = Number(angular.element('#construction_value').val());
          var remodeling = Number(angular.element('#remodeling_value').val());
          value= construction + remodeling;
       }else{/**/
            angular.element('.construct').hide();
            angular.element('.insured').show();
            value= angular.element('#insured_value').val();
       //}

      if (scope.currency == 'BS') {
        value = value / scope.exchange_rate.bs_value;
      }
      /**
      if(angular.element('#use').val() == 'IP' && angular.element('#matter_insured').val()=='PR'){
        scope.insured_riesgo = false;
        if (value > 200000)
          scope.insured_industrial = true;
        else
          scope.insured_industrial = false;
      }else{/**/
      if(angular.element('#use').val() == 'IP'){
        scope.insured_industrial = true;
        if (value > (scope.amount_max/1))
          scope.insured_riesgo = true;
        else
          scope.insured_riesgo = false;
      }else{
        scope.insured_industrial = false;
        if (value > (scope.amount_max/1)) {
          scope.insured_riesgo = true;
          scope.btn_save = false;
        } else {
          scope.insured_riesgo = false;
          scope.btn_save = true;
        }  
      }

      
      //}

      });
      

  }

  // valida termino remodelacion en caso de construccion
  function validaConstructionTerm(scope){
    $scope.$watch('formData.insured_value', function(value, oldValue, scope) {
    scope.construction_term = false;

    value_insured     = angular.element('#insured_value').val();
    value_remodeling  = angular.element('#remodeling_value').val();

    if (scope.currency == 'BS'){
      value_insured     = value_insured / scope.exchange_rate.bs_value;
      value_remodeling  = value_remodeling / scope.exchange_rate.bs_value;
    } 
          

    if(value_insured > 50000 && angular.element('#state').val() == 'C')
          scope.construction_term = true;

    if(value_remodeling > 50000 && angular.element('#state').val() == 'P')
          scope.construction_term = true;
        });
  }

  function bloqueaMatter(){
    
    angular.element('#matter_insured').prop('disabled', true);

  }

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

            $scope.formData.zone                  = data.detail.zone;
            $scope.formData.locality              = data.detail.locality;
            $scope.formData.address               = data.detail.address;
            $scope.formData.make                  = data.detail.make;
            $scope.formData.model                 = data.detail.model;
            $scope.formData.chassis_number        = data.detail.chassis_number;
            $scope.formData.matter_insured        = data.detail.matter_insured;
            
            $scope.popup(data.payload);
          }
        }, function(response){
          console.log(response);
        }).finally(function () {
          $scope.easyLoading('body', '', false);
        });
  };

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
              //$rootScope.submitted = false;
              $scope.success = { result: true };

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