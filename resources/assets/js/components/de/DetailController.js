var detailEdit = function ($rootScope, $scope, $http) {

  /**
   * Balance edit
   * @param  {[type]} event [description]
   */
  this.editBalance = function (event) {
    event.preventDefault();

    $scope.easyLoading('body', 'dark', true);

    var href = event.target.href;

    $http.get(href, {})
      .then(function (response) {
        if (response.status == 200) {
          var data = response.data;

          $scope.formData.amount_requested    = data.amount_requested;
          $scope.formData.amount_requested_bs = data.amount_requested_bs;
          $scope.formData.credit_product      = data.credit_product
          $scope.formData.balance             = data.balance;
          $scope.formData.movement_type       = data.movement_type;
          $scope.formData.refinancing         = data.refinancing;
          $scope.formData.parallel_credit     = data.parallel_credit;
          $scope.formData.read_field          = false;

          if (Number(data.cumulus || 0) == 0) {
            $scope.formData.cumulus = $scope.formData.amount_requested_bs;
          } else {
            $scope.formData.cumulus = data.cumulus;
          }

          if (data.cumulus <= 0) {
            $scope.cumulus();
          }
          
          $scope.popup(data.payload);
        }
      }, function (response) {
        console.log(response.data);
      }).finally(function () {
        $scope.easyLoading('body', '', false);
      });
  };
  
  $scope.$watch('formData.read_field', function(value, oldValue, scope) {
    if(value == true){
        $('#refinancing').attr('readonly', false);
        $('#parallel_credit').attr('readonly', false);
        scope.small_help=true;
    }else{
        $('#refinancing').attr('readonly', true);
        $('#parallel_credit').attr('readonly', true);
        scope.small_help=false;
    }
  });

  /**
   * Balance update
   * @param  {[type]} event [description]
   */
  this.updateBalance = function (event) {
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
              // esta parte de cambia para que se pueda mostrar el mensaje de exito en la vista de detalle
              // $scope.success = { beneficiary: false };
              // $scope.redirect(response.data.location);
              $scope.success = { beneficiary: true };
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


  this.getBalance = function (event) {
    event.preventDefault();
    $scope.easyLoading('#popup', 'dark', true);

    var href = '';

    if (event.target.tagName === 'SPAN') {
      event.stopPropagation();
      href = event.target.parentNode.href;
    } else {
      href = event.target.href;
    }

    $http.get(href, {

    }).then(function (response) {
        if (response.status === 200) {
          $scope.formData.balance = response.data.balance;

          $scope.refinancing();
        }
      }, function (response) {
        console.log(response.data);
      }).finally(function () {
        $scope.easyLoading('#popup', '', false);
      });
  }

  /**
   * Beneficiary create
   * @param  {[type]} event [description]
   */
  this.createBeneficiary = function (event) {
    event.preventDefault();

    //$scope.easyLoading('body', 'dark', true);

    var href = event.target.href;

    $http.get(href, {

    }).then(function (response) {
        if (response.status == 200) {
          $scope.formData.last_name        = '';
          $scope.formData.mother_last_name = '';
          $scope.formData.complement       = '';
          $scope.formData.phone_number_mobile = '';

          $scope.popup(response.data.payload);
        }
      }, function (response) {
        console.log(response.data);
      }).finally(function () {
        //$scope.easyLoading('body', '', false);
      });
  };

  /**
   * Beneficiary edit
   * @param  {[type]} event [description]
   */
  this.editBeneficiary = function (event) {
    event.preventDefault();

    //$scope.easyLoading('body', 'dark', true);

    var href = event.target.href;

    $http.get(href, {})
      .then(function (response) {
        if (response.status == 200) {
          var data = response.data;
          var dni  = data.beneficiary[0].dni.split('-');

          $scope.formData.beneficiary_id   = data.beneficiary[0].id;
          $scope.formData.first_name       = data.beneficiary[0].first_name;
          $scope.formData.last_name        = data.beneficiary[0].last_name;
          $scope.formData.mother_last_name = data.beneficiary[0].mother_last_name;
          $scope.formData.relationship     = data.beneficiary[0].relationship;
          $scope.formData.phone_number_mobile = data.beneficiary[0].phone_number_mobile;
          $scope.formData.dni              = dni[0];
          $scope.formData.complement       = dni[1];
          $scope.formData.extension        = data.beneficiary[0].extension;

          $scope.popup(data.payload);
        }
      }, function (response) {
        console.log(response.data);
      }).finally(function () {
        //$scope.easyLoading('body', '', false);
      });
  };

  /*
  * Cumulus calculate
  */
  $scope.cumulus = function () {
    if ($scope.formData.movement_type == 'AD' || $scope.formData.movement_type == 'FS') {
      var amount_requested = Number($scope.formData.amount_requested_bs || 0);
      var balance          = Number($scope.formData.balance || 0);
      /**/
      if($scope.formData.credit_product == 'PLT' || $scope.formData.credit_product == 'PLNT')
        $scope.formData.cumulus = balance + amount_requested;
      else
        $scope.formData.cumulus = amount_requested + balance;/**/
    }
  };

  /*
  * Cumulus calculate with refinancing
  */
  $scope.refinancing = function () {
    var amount_requested = Number($scope.formData.amount_requested_bs || 0);
    var balance          = Number($scope.formData.balance || 0);
    var refinancing      = Number($scope.formData.refinancing || 0);
    var parallel_credit  = Number($scope.formData.parallel_credit || 0);
    
    if($scope.formData.credit_product == 'PLT' || $scope.formData.credit_product == 'PLNT')
      $scope.formData.cumulus = (amount_requested + balance + parallel_credit) - refinancing;
    else
      $scope.formData.cumulus = (amount_requested + balance + parallel_credit) - refinancing;

    if((refinancing <= balance) && ($scope.formData.cumulus >= 0)){
      $('#btn_balance').show();
      $scope.message_buton=false;
    }else{
      $('#btn_balance').hide();
      $scope.message_buton=true;
    }
  };

  $scope.$watch('formData.cumulus', function(value, oldValue, scope) {
    if (scope.formData.movement_type == 'LC') {
      var balance = Number(scope.formData.balance || 0);

      if (value >= scope.formData.amount_requested_bs && value >= balance) {
        scope.data.cumulus = false;
      } else {
        scope.data.cumulus = true;
      }
    }
  });

  /**
   * Client Remove
   * @param  {[type]} event [description]
   * @return {[type]}       [description]
   */
  $scope.delete = function (event) {
    event.preventDefault();

    var url     = event.target.attributes.href.value;
    var message = 'Desea eliminar el registro de este titular?';
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

module.exports.detailEdit = detailEdit;
