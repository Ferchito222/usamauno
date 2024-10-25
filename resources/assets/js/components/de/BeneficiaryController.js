var beneficiary = function($rootScope, $scope, $http){
  
  /**
   * Beneficiary store
   * @param  {[type]} event [description]
   */
  $scope.store = function (event) {
    event.preventDefault();

    if (! $rootScope.submitted) {
      $rootScope.submitted = true;

      //$scope.easyLoading('#popup', 'dark', true);

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
          //$scope.easyLoading('#popup', '', false);
        });
    }
  };

  /**
   * Beneficiary edit
   * @param  {[type]} event [description]
   */
  $scope.update = function (event) {
    event.preventDefault();

    if (! $rootScope.submitted) {
      $rootScope.submitted = true;
      
      //$scope.easyLoading('#popup', 'dark', true);

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
          //$scope.easyLoading('#popup', '', false);
        });
    }
  };

};

module.exports.beneficiary = beneficiary;