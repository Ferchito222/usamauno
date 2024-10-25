var angular = require('angular');
var ngAnimate = require('angular-animate');
var angucomplete = require('angucomplete-alt');
var angularFileUpload = require('angular-file-upload');

/*var $ = require('jquery');
global.jQuery = $;
var bootstrap = require('bootstrap');*/

var UserController         = require('./components/UserController');
var FacultativeController  = require('./components/FacultativeController');
var HeaderDeController     = require('./components/de/HeaderController');
var DetailDeController     = require('./components/de/DetailController');
var BeneficiaryController  = require('./components/de/BeneficiaryController');
var CancellationController = require('./components/de/CancellationController');
var HeaderViController     = require('./components/vi/HeaderController');
var HeaderAuController     = require('./components/au/HeaderController');
var DetailAuController     = require('./components/au/DetailController');
var HeaderTdController     = require('./components/td/HeaderController');
var DetailTdController     = require('./components/td/DetailController');
var HeaderEmaController    = require('./components/Ema/HeaderController');
var DetailEmaController    = require('./components/Ema/DetailController');
var HeaderRmaController    = require('./components/Rma/HeaderController');
var DetailRmaController    = require('./components/Rma/DetailController');
var ReportController       = require('./components/ReportController');
var ClientController       = require('./components/ClientController');
var HeaderElpController    = require('./components/elp/HeaderController');
var HeaderEbController     = require('./components/eb/HeaderController');

var ExtraController        = require('./components/api/ExtraController');
var SettingController      = require('./components/SettingController');

var app = angular.module('sibas', ['ngAnimate', 'angucomplete-alt', 'angularFileUpload', ]);

app.config(['$httpProvider', function ($httpProvider) {
  $httpProvider.defaults.headers
      .common['X-Requested-With'] = 'XMLHttpRequest';
}]);

app.run(['$rootScope', '$compile', '$window', '$timeout', '$http', 'FileUploader', function($rootScope, $compile, $window, $timeout, $http, FileUploader){
  $rootScope.formData = {
    mother_last_name: '',
    emails: []
  };

  $rootScope.errors = {
  };

  $rootScope.success = {
  };

  $rootScope.mcData = {
  };

  $rootScope.dataOptions   = [];
  $rootScope.currentOption = [];
  $rootScope.data          = {};

  $rootScope.mcEnabled = false;

  $rootScope.submitted = false;

  $rootScope.getActionAttribute = function (event) {
    return event.target.attributes.action.value;
  };

  $rootScope.popup = function (payload) {
    angular.element('#popup').find('.modal-body').html($compile(payload)($rootScope));
    angular.element('#popup').modal();
  };

  $rootScope.popup2 = function (payload) {
    angular.element('#popup2').find('.modal-body').html($compile(payload)($rootScope));
    angular.element('#popup2').modal();
  };

  $rootScope.redirect = function (location) {
    $timeout(function(){
      $window.location.href = location;
    }, 1500);
  };

  $rootScope.csrf_token = function () {
    return angular.element('meta[name="csrf-token"]').attr('content');
  };

  $rootScope.compileData = function (payload) {
    return $compile(payload)($rootScope);
  };

  $rootScope.sendForm = function (event) {
    if ($rootScope.submitted) {
      event.preventDefault();
    } else {
      $rootScope.submitted = true;
    }
  };

  $rootScope.submitForm = function (id_form) {
    $timeout(function(){
      angular.element(id_form).submit();
    }, 0);
  };

  $rootScope.easyLoading = function (element, theme, show) {
    if (show) {
      $(element).loading({
        theme: theme,                  //light
        message: 'Por favor espere...'
      });
    }

    if (! show) {
      $(element).loading('stop');
    }
  };

  $rootScope.warranty = function () {
    $('input[name="warranty"]').click(function (e) {
      if (this.value == 0) {
        angular.element('#term').prop('value', 1).prop('readonly', true);
        angular.element('#type_term option[value="Y"]').prop('selected', true);
        angular.element('#payment_method option[value="PT"]').prop('selected', true);

        angular.element('#type_term option:not(:selected)').prop('disabled', true);
        angular.element('#payment_method option:not(:selected)').prop('disabled', true);

        angular.element('#number-de-container').hide();
      } else {
        // angular.element('#term').prop('value', '').prop('readonly', false);
        angular.element('#term').prop('readonly', false);
        angular.element('#type_term option:first').prop('selected', true);
        angular.element('#payment_method option:first').prop('selected', true);

        angular.element('#type_term option').prop('disabled', false);
        angular.element('#payment_method option').prop('disabled', false);

        angular.element('#number-de-container').show();
      }

      angular.element('#type_term').triggerHandler('change');
      angular.element('#payment_method').triggerHandler('change');
    });

    $('input[name="warranty"]:checked').trigger('click');
  };

  $rootScope.getPolicies = function () {
    var url = angular.element('#form-init').data('url');

    $http.get(url, {})
      .then(function (response) {
        $rootScope.headers = response.data.headers;
          if (response.status == 200) {
            var data = response.data;
          }
        }, function(response){
          console.log(response);
        }).finally(function () {
        });
  };

  $rootScope.policySelected = function (selected) {
    var number_de = angular.element('#number_de');

    if (selected) {
      number_de.prop('value', selected.originalObject.id);
    } else {
      number_de.prop('value', '');
    }
  };

  $rootScope.openCertificate = function (event) {
    event.preventDefault();
    $rootScope.easyLoading('body', 'dark', true);

    var url = event.target.href;

    $http.get(url, {})
      .then(function (response) {
          if (response.status == 200) {
            $('#popup .modal-dialog').css({
              'width': '825px'
            });

            var data = response.data;

            $rootScope.popup(data.payload);
          }
        }, function(response) {
          console.log(response.data);
        }).finally(function () {
          $rootScope.easyLoading('body', '', false);
        });
  };

  $rootScope.uploadFile = function () {
    var url        = angular.element('#url-upload').prop('value');
    var csrf_token = $rootScope.csrf_token();

    var uploader = $rootScope.uploader = new FileUploader({
      headers : {
        'X-CSRF-TOKEN': csrf_token,
      },
      url: url,
      autoUpload: true,
    });

    uploader.onSuccessItem = function(item, response, status, headers) {
      $rootScope.errors = {};
      $rootScope.success.message = response.message;
      
      if ('location' in response) {
        $rootScope.redirect(response.location);
      }
    };

    uploader.onErrorItem = function (item, response, status, headers) {
      $rootScope.errors = response;
      $rootScope.uploader.clearQueue();
    };

    angular.element('.input-file + label').click(function (e) {
      var progress = angular.element(this).data('progress');

      angular.element('.box-progress').hide();
      angular.element('.' + progress).show();
    });
  };

}]);

app.controller('HeaderDeController', ['$rootScope', '$scope', '$http', HeaderDeController.header]);

app.controller('DetailDeController', ['$rootScope', '$scope', '$http', DetailDeController.detailEdit]);

app.controller('BeneficiaryController', ['$rootScope', '$scope', '$http', BeneficiaryController.beneficiary]);

app.controller('FacultativeController', ['$rootScope', '$scope', '$http', '$compile', '$filter', FacultativeController.facultative]);

app.controller('CancellationController', ['$rootScope', '$scope', '$http', CancellationController.cancellation]);

app.controller('HeaderViController', ['$scope', '$http', '$compile', HeaderViController.header]);

app.controller('ClientController', ['$rootScope', '$scope', ClientController.client]);

app.controller('HeaderAuController', ['$rootScope', '$scope', '$http', HeaderAuController.header]);

app.controller('DetailAuController', ['$rootScope', '$scope', '$http', DetailAuController.detail]);

app.controller('HeaderTdController', ['$rootScope', '$scope', '$http', HeaderTdController.header]);

app.controller('DetailTdController', ['$rootScope', '$scope', '$http', DetailTdController.detail]);

app.controller('HeaderEmaController', ['$rootScope', '$scope', '$http', HeaderEmaController.header]);

app.controller('DetailEmaController', ['$rootScope', '$scope', '$http', DetailEmaController.detail]);

app.controller('HeaderRmaController', ['$rootScope', '$scope', '$http', HeaderRmaController.header]);

app.controller('DetailRmaController', ['$rootScope', '$scope', '$http', DetailRmaController.detail]);

app.controller('ReportController', ['$rootScope', '$scope', '$http', ReportController.report]);

app.controller('HeaderElpController', ['$rootScope', '$scope', '$http', HeaderElpController.header]);

app.controller('HeaderEbController', ['$rootScope', '$scope', '$http', HeaderEbController.header]);

app.controller('UserController', ['$rootScope', '$scope', '$http', UserController.user]);

app.controller('ExtraController', ['$rootScope', '$scope', '$http', ExtraController.extra]);

app.controller('SettingController', ['$rootScope', '$scope', '$http', SettingController.setting]);
