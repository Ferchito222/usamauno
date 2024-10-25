var header = function ($rootScope, $scope, $http) {

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

  /**
   * Orden de Cobro edit
   * 
   * @param  {[type]}
   * @return {[type]}
   */
  $scope.orderEdit = function (event) {
    event.preventDefault();

    $scope.easyLoading('body', 'dark', true);

    var href = event.target.href;

    $http.get(href, {

    }).then(function (response) {
        if (response.status == 200) {
          var data = response.data;

          $scope.popup(data.payload);
        }
      }, function(response){
        console.log(response);
      }).finally(function () {
        $scope.easyLoading('body', '', false);
      });
  };

  /**
   * Orden de Cobro update
   * @param  {[type]} event [description]
   * @return {[type]}       [description]
   */
  $scope.orderUpdate = function (event) {
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
            $scope.redirect(response.data.location);
          }
        }, function (response) {
          $rootScope.submitted = false;

          if (response.status == 422) {
            $scope.errors = response.data;

            console.log = response.data;
          } else if (response.status == 500) {
            console.log('Unauthorized action.');
          }
        }).finally(function () {
          $scope.easyLoading('#popup', '', false);
        });
    }
  };
};

module.exports.header = header;
