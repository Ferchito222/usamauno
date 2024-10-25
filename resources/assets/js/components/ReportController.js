var Highcharts = require('highcharts');

// Load module after Highcharts is loaded
require('highcharts/modules/exporting')(Highcharts);
var themes = require('./../css/highchart-theme-dark');

var report = function ($rootScope, $scope, $http) {
  $scope.e_policy      = false;
  $scope.e_client      = false;
  $scope.e_beneficiary = false;
  $scope.e_vehicle     = false;

  $scope.$watch('e_policy', function(value, oldValue, scope) {
    if (value) {
      angular.element('.disabled-policy').prop('disabled', false);
    } else {
      angular.element('.disabled-policy').prop('disabled', true);
    }
  });

  $scope.$watch('e_client', function(value, oldValue, scope) {
    if (value) {
      angular.element('.disabled-client').prop('disabled', false);
    } else {
      angular.element('.disabled-client').prop('disabled', true);
    }
  });

  $scope.$watch('e_beneficiary', function(value, oldValue, scope) {
    if (value) {
      angular.element('.disabled-beneficiary').prop('disabled', false);
    } else {
      angular.element('.disabled-beneficiary').prop('disabled', true);
    }
  });

  $scope.$watch('e_vehicle', function(value, oldValue, scope) {
    if (value) {
      angular.element('.disabled-vehicle').prop('disabled', false);
    } else {
      angular.element('.disabled-vehicle').prop('disabled', true);
    }
  });

  $('#report-product').change(function () {
    var code = $(this).children('option:selected').data('code');

    if (typeof code === "undefined") {
      code = '';
    }

    $('#product-code').html(code.toUpperCase());
  });

  /**
   * Report highlights
   * @param  {[type]}    [description]
   * @return {[type]}    [description]
   */
  $('.gridview tr').click(function (e) {
    if (this.className.indexOf('selected') >= 0) {
      this.className = this.className.replace('selected', '');
    } else {
      this.className  += 'selected';
    }
  });

  /**
   * Report Graphic Search
   * @param  {[type]} event [description]
   * @return {[type]}       [description]
   */
  $scope.reportGraphicSearch = function (event) {
    event.preventDefault();

    $scope.easyLoading('body', 'dark', true);

    var action = $scope.getActionAttribute(event);
    var data   = $scope.formData;

    $http.get(action, { params : data })
      .then(function (response) {
          var res = response.data.res;

          $scope.myChart.xAxis[0].setCategories(res.categories, true, true);
          $scope.myChart.setTitle({ text: res.type.title });
          $scope.myChart.yAxis[0].setTitle({ text: res.type.yAxis });

          var seriesLength = $scope.myChart.series.length;

          for(var i = seriesLength - 1; i > -1; i--)
          {
            if ($scope.myChart.series.length) {
              $scope.myChart.series[i].remove();
            }
          }

          angular.forEach(res.series, function(item, key) {
            $scope.myChart.addSeries(item, false);
          });

          $scope.modal_title = res.type.title;
          $scope.results     = res.results;

          $scope.myChart.redraw();
        }, function(response){
          // console.log(response.data);
        }).finally(function () {
          $scope.easyLoading('body', '', false);
        });
  };

  /**
   * Report Graphic
   * 
   * @return {[type]} [description]
   */
  $scope.reportGraphic = function () {
    $scope.easyLoading('body', 'dark', true);
    
    $scope.results = {
    };

    themes.dark(Highcharts);
    delete $scope.formData.mother_last_name;
    delete $scope.formData.emails;
    
    var url  = angular.element('#container').data('url');
    var data = $scope.formData;

    $http.get(url, { params: data })
      .then(function (response) {
          var res = response.data.res;

          $scope.myChart = Highcharts.chart('container', {
            chart: {
                type: 'column',
            },
            title: {
                text: res.type.title
            },
            xAxis: {
                categories: res.categories
            },
            yAxis: {
                title: {
                    text: res.type.yAxis
                }
            },
            series: res.series,
            /*series: [{
                name: 'Jane',
                data: [1, 0, 4]
            }, {
                name: 'John',
                data: [5, 7, 3]
            }]*/
            plotOptions: {
              series: {
                cursor: 'pointer',
                point: {
                  events: {
                    click: function (event) {
                      $scope.category = this.category;

                      if (! angular.equals({}, $scope.results) && ! angular.equals([], $scope.results)) {
                        $scope.user_results = [];

                        angular.forEach($scope.results, function(item, key){
                          if (item.category == $scope.category) {
                            $scope.user_results.push(item);
                          }
                        });

                        $scope.$apply();

                        $('#report-modal').modal('show');
                      }
                    }
                  }
                }
              }
            },

          });
        }, function(response){
          // console.log(response.data);
        }).finally(function () {
          $scope.easyLoading('body', '', false);
        });
  };


};

module.exports.report = report;
