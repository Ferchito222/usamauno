var header = function ($scope, $http, $compile) {
  $scope.plans    = null;
  $scope.plan     = null;
  $scope.numberBN = 0;

  $scope.getPlans = function () {
    var rp_plans = angular.element('#rp-plans').prop('value');

    if (rp_plans) {
      $http.get(rp_plans)
        .then(function (response) {
          if (response.status == 200) {
            if (response.data.plans != null) {
              $scope.plans = response.data.plans;
            }
          }
        }, function (response) {
          if (response.status == 422) {
            $scope.errors = response.data;
          } else if (response.status == 500) {
            console.log('Unauthorized action.');
          }
        });
    }
  };

  $('#plans').change(function () {
    var id = this.value;

    $scope.plan = $.grep($scope.plans, function (plan, key) {
      return plan.id == id;
    });

    $scope.plan      = $scope.plan[0];
    var plan         = angular.fromJson($scope.plan.plan);
    $scope.plan.plan = plan;

    $scope.$apply();
  });

  $('#payment_method').change(function () {
    var payment_method = this.value;

    if (payment_method == 'CO') {
      $('#period option[value="Y"]').prop('selected', true);
      $('#period option:not(:selected)').prop('disabled', true);
      $('#account_number').prop('value', 0).prop('readonly', true);
      $('#period').trigger('change');
    } else {
      $('#period option[value=""]').prop('selected', true);
      $('#period option').prop('disabled', false);
      $('#account_number').prop('value', '').prop('readonly', false);
      $('#period').trigger('change');
    }
  });

  $scope.beneficiary = function (event, action) {
    event.preventDefault();
    var number = 0;

    if (action) {
      $scope.numberBN += 1;

      angular.element('#beneficiaries').append($compile('<div class="beneficiary">' +
                            '<span class="label label-info">' + $scope.numberBN + '</span>' +
                            '<div class="form-group" style="margin-left: 10px;">' +
                              '<span class="label_required"></span> ' +
                              '<input type="text" name="beneficiaries[' + $scope.numberBN + '][first_name]" class="form-control" autocomplete="off" placeholder="Nombre">' +
                            '</div>' +
                            '<div class="form-group">' +
                              '<span class="label_required"></span> ' +
                              '<input type="text" name="beneficiaries[' + $scope.numberBN + '][last_name]" class="form-control" autocomplete="off" placeholder="Apellido Paterno">' +
                            '</div>' +
                            '<div class="form-group">' +
                              '<input type="text" name="beneficiaries[' + $scope.numberBN + '][mother_last_name]" class="form-control" autocomplete="off" placeholder="Apellido Materno">' +
                            '</div>' +
                            '<div class="form-group">' +
                              '<span class="label_required"></span> ' +
                              '<input type="text" name="beneficiaries[' + $scope.numberBN + '][relationship]" class="form-control" autocomplete="off" placeholder="Parentesco">' +
                            '</div>' +
                            '<div class="form-group">' +
                              '<input type="text" name="beneficiaries[' + $scope.numberBN + '][dni]" class="form-control" autocomplete="off" placeholder="Documento de Identidad">' +
                            '</div>' +
                            '<div class="form-group" style="margin-right: 5px;">' +
                              '<span class="label_required"></span> ' +
                              '<input type="text" name="beneficiaries[' + $scope.numberBN + '][participation]" class="form-control" autocomplete="off" placeholder="Participación %">' +
                            '</div>' +
                            // '<a href="#" class="beneficiary glyphicon glyphicon-minus-sign" style="color: #f44336;" ng-click="beneficiary($event, false)"></a>' +
                          '</div>')($scope));
    } else {
      $scope.numberBN -= 1;

      if ($scope.numberBN < 0) {
        $scope.numberBN = 0;
      }
      
      angular.element('#beneficiaries div.beneficiary').last().remove();
    }

  };

};

module.exports.header = header;
