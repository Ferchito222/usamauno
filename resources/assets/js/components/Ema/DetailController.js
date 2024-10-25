var detail = function ($rootScope, $scope, $http) {

    /**
     * form create detail
     * @param {type} event
     * @returns {undefined}
     */
    $scope.create = function (event) {
        event.preventDefault();
        $scope.easyLoading('body', 'dark', true);
        var url = event.target.action + '?serial_number=' + $('#serial_number').val();

        $http.get(url, {})
                .then(function (response) {
                    if (response.status == 200) {
                        var data = response.data;
                        $rootScope.amount_max      = data.amount_max;
                        $rootScope.currency        = data.currency;
                        $rootScope.exchange_rate   = data.exchange_rate;
                        
                        $scope.formData.property_use    = data.detail.property_use;
                        $scope.formData.description     = data.detail.description;
                        $scope.formData.serial_number   = data.detail.serial_number;
                        $scope.formData.city            = data.detail.city;
                        $scope.formData.insured_value   = data.detail.insured_value;
                        $scope.formData.currency        = data.detail.currency;
                        $scope.formData.guarantee_id    = data.detail.guarantee_id;
                        
                        $scope.errors.message_search    = false;
                        $scope.errors.message_ws        = false;
                        $scope.popup(data.payload);

                    }
                }, function (response) {
                    $rootScope.submitted = false;

                    if (response.status == 500)
                        console.log('Unauthorized action.');

                    if (response.status == 401)
                        $scope.errors.message_search = true;
                    else
                        $scope.errors.message_search = false;

                    if (response.status == 402)
                        $scope.errors.message_ws = true;
                    else
                        $scope.errors.message_ws = false;

                }).finally(function () {
            $scope.easyLoading('body', '', false);
        });
    };

    /**
     * store detail
     * @param {type} event
     * @returns {undefined}
     */
    $scope.store = function (event) {
        event.preventDefault();

        if (!$rootScope.submitted) {
            $rootScope.submitted = true;

            $scope.easyLoading('#popup', 'dark', true);

            var action = $scope.getActionAttribute(event);

            CSRF_TOKEN = $scope.csrf_token();

            var data = $.param($scope.formData);

            $http.post(action, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            })
                    .then(function (response) {
                        $scope.errors = {};

                        if (response.status == 200) {
                            $scope.success = {detail: true};

                            $scope.redirect(response.data.location);
                        }
                    }, function (response) {
                        $rootScope.submitted = false;

                        if (response.status == 422) {
                            $scope.errors = response.data;
                        } else if (response.status == 500) {
                            console.log('Unauthorized action.');
                        } else if (response.status == 428) {
                            $scope.errors = {};

                            messageAction('error', response.data.reason);
                        }
                    }).finally(function () {
                $scope.easyLoading('#popup', '', false);
            });
        }
    };

    /**
     * detail delete
     * @param {type} event
     * @returns {undefined}
     */
    $scope.delete = function (event) {
        event.preventDefault();

        var url = event.target.attributes.href.value;
        var message = 'Desea eliminar el registro?';
        CSRF_TOKEN = $scope.csrf_token();

        bootbox.confirm(message, function (result) {
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

    /**
     * detail form edit
     * @param {type} event
     * @returns {undefined}
     */
    $scope.edit = function (event) {
        event.preventDefault();

        $scope.easyLoading('body', 'dark', true);

        var url = event.target.attributes.href.value;

        $http.get(url, {})
                .then(function (response) {
                    if (response.status == 200) {
                        var data = response.data;
                        $rootScope.amount_max      = data.amount_max;
                        $rootScope.currency        = data.currency;
                        $rootScope.exchange_rate   = data.exchange_rate;
                        
                        $scope.formData.property_use    = data.detail.property_use;
                        $scope.formData.description     = data.detail.description;
                        $scope.formData.serial_number   = data.detail.serial_number;
                        $scope.formData.city            = data.detail.city;
                        $scope.formData.insured_value   = data.detail.insured_value;
                        $scope.formData.currency        = data.detail.currency;
                        
                        $scope.popup(data.payload);
                    }
                }, function (response) {
                    console.log(response);
                }).finally(function () {
            $scope.easyLoading('body', '', false);
        });
    };

    $scope.$watch('formData.insured_value', function (value, oldValue, scope) {
        if (scope.currency == 'BS') {
            value = value / scope.exchange_rate.bs_value;
        }
        
        if (value > (scope.amount_max/1)) {
            scope.insured_value = true;
            scope.btn_save = false;
        } else {
            scope.insured_value = false;
            scope.btn_save = true;
        }
    });
    
    /**
     * update detail
     * @param {type} event
     * @returns {undefined}
     */
    $scope.update = function (event) {
        event.preventDefault();

        if (!$rootScope.submitted) {
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
                            $scope.success = {detail: true};

                            $scope.redirect(response.data.location);
                        }
                    }, function (response) {
                        $rootScope.submitted = false;

                        if (response.status == 422) {
                            $scope.errors = response.data;
                        } else if (response.status == 500) {
                            console.log('Unauthorized action.');
                        } else if (response.status == 428) {
                            $scope.errors = {};

                            messageAction('error', response.data.reason);
                        }
                    }).finally(function () {
                $scope.easyLoading('#popup', '', false);
            });
        }
    };

    /**
     * form issuance detail
     * @param {type} event
     * @returns {undefined}
     */
    $scope.editIssuance = function (event) {
        event.preventDefault();

        $scope.easyLoading('body', 'dark', true);

        var url = event.target.attributes.href.value;

        $http.get(url, {})
                .then(function (response) {
                    if (response.status == 200) {
                        var data = response.data;
                        $rootScope.amount_max      = data.amount_max;
                        $rootScope.currency        = data.currency;
                        $rootScope.exchange_rate   = data.exchange_rate;
                        
                        $scope.formData.property_use    = data.detail.property_use;
                        $scope.formData.description     = data.detail.description;
                        $scope.formData.serial_number   = data.detail.serial_number;
                        $scope.formData.city            = data.detail.city;
                        $scope.formData.insured_value   = data.detail.insured_value;
                        $scope.popup(data.payload);
                    }
                }, function (response) {
                    console.log(response);
                }).finally(function () {
            $scope.easyLoading('body', '', false);
        });
    };

    /**
     * update issuance detail
     * @param {type} event
     * @returns {undefined}
     */
    $scope.updateIssuance = function (event) {
        event.preventDefault();

        if (!$rootScope.submitted) {
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
                            $scope.success = {detail: true};

                            $scope.redirect(response.data.location);
                        }
                    }, function (response) {
                        $rootScope.submitted = false;

                        if (response.status == 422) {
                            $scope.errors = response.data;
                        } else if (response.status == 500) {
                            console.log('Unauthorized action.');
                        } else if (response.status == 428) {
                            $scope.errors = {};

                            messageAction('error', response.data.reason);
                        }
                    }).finally(function () {
                $scope.easyLoading('#popup', '', false);
            });
        }
    };

};

module.exports.detail = detail;