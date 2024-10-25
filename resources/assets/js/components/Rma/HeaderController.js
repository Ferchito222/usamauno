var header = function ($rootScope, $scope, $http) {

  /**
   * Request create
   * @param  {[type]} event [description]
   */
  $scope.requestCreate = function (event) {
    event.preventDefault();

    $scope.easyLoading('body', 'dark', true);

    var url = event.target.href;

    $http.get(url, {})
      .then(function (response) {
          if (response.status == 200) {
            var data = response.data;

            $scope.formData.facultative_observation = data.facultative_observation;

            $scope.popup(data.payload);
          }
        }, function(response){
          console.log(response);
        }).finally(function () {
          $scope.easyLoading('body', '', false);
        });
  };

  /**
   * Request store
   * @param  {[type]} event [description]
   */
  $scope.requestStore = function (event) {
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
            $scope.success = { facultative: true };

            $scope.redirect(response.data.location);
          }
        }, function (response) {
          $rootScope.submitted = false;

          if (response.status == 422) {
            $scope.errors = response.data;
          } else if (response.status == 500) {
            console.log('Unauthorized action.');
          }
        }).finally(function () {
          $scope.easyLoading('#popup', '', false);
        });
    }

  };
  $scope.showBoxAttachrma = function (event) {
        angular.element('#box-attach-rma').show();
    };

};

module.exports.header = header;