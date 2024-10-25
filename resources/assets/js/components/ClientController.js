var client = function ($rootScope, $scope) {
  $scope.verify = false;

  $scope.submitDe = function (event) {
    $scope.sendForm(event);

    if (! $scope.verify) {
      event.preventDefault();

      var form_id = event.target.attributes.id.value;
      var weight  = angular.element('#weight').prop('value');
      var height  = angular.element('#height').prop('value');
      var message = '';
      var confirm = false;

      if (weight < 40 || weight > 140) {
        message += '<strong>Usted ha introducido un peso fuera de los parámetros normales.</strong>';
        confirm = true;
      }

      if (height < 100) {
        if (confirm) {
          message += '<br />';
        }

        message += '<strong>La estatura introducida para la persona es menor a 1 metro (100 cm.)</strong>';
        confirm = true;
      }

      if (confirm) {
        message += '<br /><br /> Desea continuar de todas formas?';
        
        bootbox.confirm(message, function(result) {
          if (result) {
            $scope.verify        = true;
            $rootScope.submitted = false;

            $scope.submitForm('#' + form_id);
          }
        });
      } else {
        $scope.verify        = true;
        $rootScope.submitted = false;

        $scope.submitForm('#' + form_id);
      }
    }
  };

  $("input[name='cesantia']").change(function() {
    
    if(this.value==1){
        if(angular.element('#economic_activity_type_code').val() == 3){
          bootbox.alert('DE ACUERDO A LA INFORMACION CARGADA EN EL SISTEMA NETBANK EL CLIENTE FIGURA CON SITUACION LABORAL DE INDEPENDIENTE, DESEA CONTINUAR ?', 
          function() {
          });
        }
        $('.data_work').show();
        $('.alert_plazo_fijo').hide();
      }else
        $('.data_work').hide();
  });

  $("#type_of_contract").change(function() {
    if(this.value=='PF'){
      angular.element("#type_of_contract").val('');
      angular.element("#type_of_contract").click();
      angular.element("input[name='cesantia']").change(0);
      angular.element("input[name='cesantia']").click();
      
      $('.alert_plazo_fijo').show();
    }else{
      $('.alert_plazo_fijo').hide();
    }
  });

  $scope.workdata = function($state) {
    if($state == 1)
      $('.data_work').show();
    else
      $('.data_work').hide();
  }; 
};

module.exports.client = client;