var facultative = function ($rootScope, $scope, $http, $compile, $filter) {
  $scope.approved             = false;
  $scope.preference_field     = false;
  $scope.surcharge            = false;
  $scope.state                = false;
  $scope.observation          = true;

  $scope.formData.approved   = 0;
  $scope.formData.surcharge  = 0;
  $scope.formData.percentage = 0;
  $scope.formData.state      = null;
  $scope.formData.mc_id      = null;

  $scope.record = [];

  /**
   * Process create form
   * @param  {[type]} event [description]
   */
  $scope.process = function (event) {
    event.preventDefault();

    $scope.easyLoading('body', 'dark', true);

    var url = event.target.href;

    $http.get(url, {})
      .then(function (response) {
          if (response.status == 200) {
            var data = response.data;

            $rootScope.dataOptions       = data.states;
            $rootScope.currentOption     = $rootScope.dataOptions[0];
            $rootScope.age               = data.age;
            $scope.formData.current_rate = data.current_rate;
            $scope.formData.current_rate_old = data.current_rate;
            $scope.formData.final_rate   = data.current_rate;
            $scope.formData.emails       = data.user_email;
            $scope.formData.preference_rate = data.preference_rate;
            $scope.formData.current_rate_accidental = 0;
            $scope.formData.percentage_bank = 0;
            $scope.formData.percentage_cia = 0;
            $scope.formData.monetary_pricing = '';

            $scope.popup(data.payload);
          }
        }, function(response){
          console.log(response.data);
        }).finally(function () {
          $scope.easyLoading('body', '', false);
        });
  };

  /**
   * Process store
   * @param  {[type]} event [description]
   */
  $scope.store = function (event) {
    event.preventDefault();

    if (! $rootScope.submitted) {
      $rootScope.submitted = true;

      $scope.easyLoading('#popup', 'dark', true);

      var action = $scope.getActionAttribute(event);

      CSRF_TOKEN = $scope.csrf_token();

      $scope.formData.emails = $scope.formData.emails.split(',');

      var data = $.param($scope.formData);

      $http.put(action, data, {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': CSRF_TOKEN
        }
      })
        .then(function (response) {
          $scope.errors = {};

          $scope.formData.emails = $scope.formData.emails.join(',');
          
          if (response.status == 200) {
            if ('location' in response.data) {
              $scope.success = { facultative: true };
              $scope.redirect(response.data.location);
            } else {
              $rootScope.submitted = false;
            }
          }
        }, function (response) {
          $rootScope.submitted = false;

          if (response.status == 422) {
            angular.forEach($scope.formData.emails, function(value, key){
              if ('emails.' + key in response.data) {
                response.data = [
                  'El email no es válido'
                ];
              }
            });

            $scope.formData.emails = $scope.formData.emails.join(',');

            $scope.errors = response.data;
          } else if (response.status == 500) {
            console.log('Unauthorized action.');
          }
        }).finally(function () {
          $scope.easyLoading('#popup', '', false);
        });
    }
  };

  /**
   * Get Observation process
   * @param  {[type]} event [description]
   */
  $scope.observation = function (event) {
    event.preventDefault();

    $scope.easyLoading('body', 'dark', true);

    var href = event.target.href;

    $http.get(href, {

    }).then(function (response) {
        if (response.status == 200) {
          $scope.popup(response.data.payload);
        }
      }, function (response) {
        // console.log(err);
      }).finally(function () {
        $scope.easyLoading('body', '', false);
      });
  };

  /**
   * Answer update process
   * @param  {[type]} event [description]
   */
  $scope.storeAnswer = function (event) {
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
            if ('location' in response.data) {
              $scope.success = { facultative: true };
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

          // console.log(err);
        }).finally(function () {
          $scope.easyLoading('#popup', '', false);
        });
    }
  };

  /**
   * State observation action
   */
  $scope.stateChange = function () {
    $scope.formData.state = $scope.currentOption;

    if ($scope.currentOption.data_slug == 'me') {
      $scope.observation = false;
      $scope.formData.observation = '--';

      var _mc = angular.element('input#_mc').prop('value');
      var mcForm  = angular.element('#mc-form');

      $scope.mcEnabled = true;

      $scope.easyLoading('#popup', 'dark', true);

      $http.get(_mc, {

      }).then(function (response) {
          if (response.status == 200) {
            $scope.data.care_centers  = response.data.care_centers;
            $scope.mcData.mcid        = response.data.mc_id;
            $scope.mcData.care_center = null;
            $scope.mcData.date_attention = '';

            mcForm.html($compile(response.data.payload)($scope));
          }
        }, function (response) {
          // console.log(err);
        }).finally(function () {
          $scope.easyLoading('#popup', '', false);
        });

    } else {
      $scope.mcEnabled   = false;
      $scope.observation = true;
      $scope.formData.observation = '';
    }
  };

  /**
   * Care Center
   * @param  {[type]} value    [description]
   * @param  {[type]} oldValue [description]
   * @param  {[type]} scope)   {                 } [description]
   * @return {[type]}          [description]
   */
  $scope.$watch('mcData.care_center', function(value, oldValue, scope) {
    if (typeof value !== 'undefined' && value !== null) {
      $scope.mcData.contact_person = value.responsible+', '+value.address;
    }
  });

  /**
   * Approved actions
   * @param  {[type]} value    [description]
   * @param  {[type]} oldValue [description]
   * @param  {[type]} scope)   {               if (value ! [description]
   */
  $scope.$watch('formData.approved', function(value, oldValue, scope) {
    if(value==3){
      $scope.formData.current_rate_accidental=angular.element("#rate_accidental_death").val();
      $scope.formData.observation=angular.element("#accidental_death_obs").val();
    }else{
      $scope.extras=false;

      if (value != 2) {
        $scope.formData.observation = '';
        
        if(value==1 && $scope.formData.preference_rate > 0)
          $scope.preference_field = true;
        else
          $scope.preference_field = true;
  
      } else if ($scope.currentOption.data_slug == 'me') {
        $scope.observation = false;
        $scope.mcEnabled   = false;
        $scope.formData.observation = '--';
      }
    }
    
  });

  /**
   * Surcharge actions
   * @param  {[type]} value    [description]
   * @param  {[type]} oldValue [description]
   * @param  {[type]} scope)   {               if (value [description]
   */
  $scope.$watch('formData.surcharge', function(value, oldValue, scope) {
    if (value == 0) {
      $scope.formData.percentage = 0;
      //$scope.formData.final_rate = $scope.formData.current_rate;
      $scope.formData.final_rate = 0;
      
      $scope.percetage_extras = true;
      $scope.formData.percentage_bank = 0;
      $scope.formData.percentage_cia = 0;
    }else{
      
      if($.inArray(angular.element('#credit_product_slug').val(),['PMO', 'PLT']) == -1){
        $scope.percetage_extras = true;
        $scope.formData.percentage_bank = 0;
        $scope.formData.percentage_cia = 0;
      }
    }
  });

  /**
   * tasa preferencial
   */
  $scope.$watch('formData.preference_field', function(value, oldValue, scope) {
    
    if($scope.formData.preference_rate > 0){
        if (value == 1) {
            $scope.formData.current_rate = $scope.formData.preference_rate;
        }else
            $scope.formData.current_rate = $scope.formData.current_rate_old;

        $scope.formData.percentage = 0;
        $scope.formData.final_rate = 0;
        
        $scope.formData.percentage_bank = 0;
        $scope.formData.percentage_cia = 0;
    }
  });

  /**
   * Medical Certificate store
   * @param  {[type]} event [description]
   */
  $scope.mcStore = function (event) {
    event.preventDefault();

    if (! $rootScope.submitted) {
      $rootScope.submitted = true;

      $scope.easyLoading('#popup', 'dark', true);

      var action = $scope.getActionAttribute(event);

      CSRF_TOKEN = $scope.csrf_token();

      $http({
        method: 'POST',
        url: action,
        data: $.param($scope.mcData),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': CSRF_TOKEN
        }
      }).then(function (response) {
          $scope.errors = {};

          if (response.status == 200) {
            $scope.formData.mc_id = response.data.mc_id;
            $scope.success        = { medical_certificate: true };
            $scope.mcEnabled      = false;
            $rootScope.submitted  = false;

            $scope.submitForm('#form-fa');
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

  $scope.finalRate = function () {
    var percentage   = parseInt($scope.formData.percentage) || 0;
    var current_rate = parseFloat($scope.formData.current_rate);
    /**var total        = (current_rate * percentage / 100) + current_rate;
    var final_rate   = parseFloat(total).toFixed(4);
    /**/
    if($.inArray(angular.element('#credit_product_slug').val(),['PMO', 'PLT']) == -1){
      $scope.formData.percentage_bank = (percentage<=50)?percentage:50;
      $scope.formData.percentage_cia = (percentage>50)?percentage-50:0;
      var total        = (current_rate * $scope.formData.percentage_bank) / 100;
      var final_rate   = parseFloat(total).toFixed(4);
    }else{
      $scope.formData.percentage_bank = (percentage<=50)?percentage:50;
      $scope.formData.percentage_cia = (percentage>50)?percentage-50:0;
      var total        = (current_rate * $scope.formData.percentage_bank) / 100;
      var final_rate   = parseFloat(total).toFixed(4);
    }
    
    $scope.formData.final_rate = final_rate;
  };

  $scope.readEdit = function (event, value) {
    var slug  = event.target.attributes['data-slug'].value;
    var rp_id = event.target.attributes['data-rp-id'].value;
    var id    = event.target.attributes['data-record'].value;
    var url   = angular.element('#read-edit').prop('value');
    url       = url.replace('fde', slug);
    url       = url.replace(':rp_id', rp_id);
    url       = url.replace(':id', id);

    CSRF_TOKEN = $scope.csrf_token();

    var data = $.param({read: value});

    $http.put(url, data, {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': CSRF_TOKEN
      }
    })
      .then(function (response) {
        if (response.status == 200) {
          // console.log(response.data);
        }
      }, function (response) {

      });
  };

  $scope.readToBoolean = function (value) {
    return Boolean(value);
  };

};

module.exports.facultative = facultative;
