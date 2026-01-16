var header = function ($scope, $http, $compile) {
    $scope.plans = null;
    $scope.plan = null;
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

        $scope.plan = $scope.plan[0];
        var plan = angular.fromJson($scope.plan.plan);
        $scope.plan.plan = plan;

        $scope.$apply();
    });

    $scope.searchTomador = function (event) {
        if (event) {
            $('#search_tomador').hide();
            $('#tomador1').checked;
        } else
            $('#search_tomador').show();
    }

    $scope.freshPayment = function (event) {
        $('#payment_method').change();
    }

    $('#payment_method').change(function () {
        var payment_method = this.value;

        $('#msg_pignorar').hide();
        //$('#period option[value=""]').prop('selected', true);
        $('#period option').prop('disabled', false);

        if (payment_method == 'CO') {

            $('#period option[value="Y"]').prop('selected', true);
            $('#period option:not(:selected)').prop('disabled', true);
            $('#period').trigger('change');
            $('#account_number').prop('readonly', true);

        } else if (payment_method == 'DA') {

            $('#period option[value="S"]').prop('disabled', true);
            $('#period option[value="C"]').prop('disabled', true);
            $('#period option[value="B"]').prop('disabled', true);

            $('#period').trigger('change');
            $('#account_number').prop('readonly', false);
            /*$('#period option[value="S"]').prop('disabled', true);
            $('#period option[value=""]').attr("selected",true);
            $('#period').trigger('change');
            $('#account_number').prop('readonly', false);*/
        } else if (payment_method == 'CR') {
            $('#msg_pignorar').show();
            $('#period option[value="Y"]').prop('selected', true);
            $('#period option:not(:selected)').prop('disabled', true);
            $('#period').trigger('change');
            $('#account_number').prop('readonly', false);
        } else if (payment_method == 'TJ') {
            $('#period option[value="Y"]').prop('selected', true);
            $('#period option:not(:selected)').prop('disabled', true);
            $('#period').trigger('change');
            $('#account_number').prop('readonly', false);
            /*$('#period option[value="S"]').prop('disabled', true);
            $('#period option[value="C"]').prop('disabled', true);
            $('#period option[value=""]').attr("selected",true);
            $('#period').trigger('change');
            $('#account_number').prop('readonly', false);*/
        }
        hide_expiration_date();
    });

    $('#period').change(function () {
        var period = this.value;

        if ($('#payment_method').val() == 'DA'){
            $('#term').val(12);
            $('#term').prop('readonly', true);
        }else if ($('#payment_method').val() == 'CO'){
            $('#term').val(12);
            $('#term').prop('readonly', false);
        }else if ($('#payment_method').val() == 'TJ'){
            $('#term').val(12);
            $('#term').prop('readonly', true);
        }  else {

            if ($('#term').val() == 12){
                $('#term').val(12);
            }

            $('#term').prop('readonly', false);
        }
        $('#term').trigger('change');
    });

    //planes visualiza mensaje de ayuda
    $('#ad_plan_id').change(function () {
        $scope.hide_plans();
        $('#plan_id_' + this.value).show();
    });

    $scope.hide_plans = function () {
        var arr_plan = JSON.parse($('#plans').val());
        angular.forEach(arr_plan, function (value, key) {
            if (value.id != '')
                $('#plan_id_' + value.id).hide();
        });
    }
    
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

    function hide_expiration_date() {
        if ($('#payment_method').val() == 'TJ')
            $('#hide_expiration_date').show();
        else
            $('#hide_expiration_date').hide();
    }

};

module.exports.header = header;
