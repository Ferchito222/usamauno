var header = function ($rootScope, $scope, $http) {

  $('[data-toggle="tooltip"]').tooltip();

  $('input[name=is_client]').change(function (e) {
    var form_search = angular.element('#form-search');
    var code        = angular.element('#code');

    if (this.value == 1) {
      form_search.slideDown();
      code.show();
    } else {
      form_search.slideUp();
      code.prop('value', '');
      angular.element('#client_eco').prop('value', '');
      code.hide();
    }
  });

  angular.element('input[name=is_client]').filter(':checked').triggerHandler('change');

  $('#reference').change(function () {
    var reference_other = angular.element('#reference_other');

    if (this.value === 'other') {
      reference_other.show();
    } else {
      reference_other.hide();
      angular.element('input[name=reference_other]').prop('value', '');
    }
  });

  angular.element('#reference').triggerHandler('change');

  /**
   * Beneficiary create
   * @param  {[type]} event [description]
   */
  $scope.createBeneficiary = function (event) {
    event.preventDefault();

    $scope.easyLoading('body', 'dark', true);

    var href = event.target.href;

    $http.get(href, {

    }).then(function (response) {
        if (response.status == 200) {
          var data = response.data;
          $scope.formData.dni               = '';
          $scope.formData.percentage_credit = 100;

          $scope.popup(data.payload);
        }
      }, function(response){
        console.log(response);
      }).finally(function () {
        $scope.easyLoading('body', '', false);
      });
  };

  /**
   * Beneficiary edit
   * @param  {[type]} event [description]
   */
  $scope.editBeneficiary = function (event) {
    event.preventDefault();

    $scope.easyLoading('body', 'dark', true);

    var href = event.target.href;

    $http.get(href, {

    }).then(function (response) {
        if (response.status == 200) {
          var data = response.data;

          $scope.formData.beneficiary_id    = data.beneficiary[0].id;
          $scope.formData.first_name        = data.beneficiary[0].first_name;
          $scope.formData.last_name         = data.beneficiary[0].last_name;
          $scope.formData.mother_last_name  = data.beneficiary[0].mother_last_name;
          $scope.formData.dni               = data.beneficiary[0].dni;
          $scope.formData.relationship      = data.beneficiary[0].relationship;
          $scope.formData.percentage_credit = data.beneficiary[0].percentage_credit;

          $scope.popup(data.payload);
        }
      }, function (response) {
        // console.log(response.data);
      }).finally(function () {
        $scope.easyLoading('body', '', false);
      });
  };

  $('input[name=charge]').change(function () {
    var charge          = angular.element('.charge');
    var charge_response = angular.element('#charge_response');
    var charge_period   = angular.element('#charge_period');

    if (this.value == 1) {
      charge.show();
    } else {
      charge.hide();
      charge_response.prop('value', '');
      charge_period.prop('value', '');
    }
  });

  angular.element('input[name=charge]').filter(':checked').triggerHandler('change');

  /**
   * Set payment method
   */
  $scope.setPaymentMethod = function () {
    var client_eco = angular.element('#ice').prop('value');

    if (client_eco == 0) {
      angular.element('#payment_method option[value=CO]').prop('selected', true);
      angular.element('#payment_method option:not(:selected)').prop('disabled', true);
      angular.element('#payment_method').triggerHandler('change');
    }
  };
};

module.exports.header = header;
