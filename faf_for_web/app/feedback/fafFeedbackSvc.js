/* ===========================================================================
 * FindAFit
 * ===========================================================================
 * Copyright 2015 Ray Nowell
 * Licensed under MIT (https://github.com/ray023/FindAFit/blob/master/LICENSE)
 * =========================================================================== */
'use strict';

fafApp

    .factory('Feedback', function($http, $q, $ionicLoading, Settings) {
        return {
            send: function(message, contact) {

                var encodeValue = function(value) {
                    if (!angular.isString(value))
                        return '';

                    value = value.replace(/\(/g, "_OPEN_PARENTHESIS_")
                                    .replace(/\)/g, "_CLOSE_PARENTHESIS_")
                                    .replace(/\-/g, "_HYPHEN_")
                                    .replace(/\./g, "_PERIOD_")
                                    .replace(/!/g, "_EXCLAMATION_MARK_")
                                    .replace(/~/g, "_TILDE_")
                                    .replace(/\*/g, "_ASTERISK_")
                                    .replace(/'/g, "_APOSTROPHE_")
                                    .replace(/:/g, "_COLON_")
                                    .replace(/;/g, "_SEMICOLON_")
                                    .replace(/@/g, "_AT_SIGN_")
                                    .replace(/&/g, "_AMPERSAND_")
                                    .replace(/"/g, "_DOUBLE_QUOTE_")
                                    .replace(/%/g, "_PERCENT_")
                                    .replace(/\?/g, "_QUESTION_")
                                    .replace(/,/g, "_COMMA_")
                                    .replace(/\\/g, "_BACKSLASH_")
                                    .replace(/\//g, "_SLASH_")
                                    .replace(/\$/g, "_DOLLAR_SIGN_");

                    value = encodeURIComponent(value);

                    return value;
                };
                var encodedMessage = encodeValue(message);
                var encodedContact = encodeValue(contact);
                var encodedCordova = encodeValue('browser');
                var encodedDeviceModel = encodeValue('browser');
                var encodedDevicePlatform = encodeValue('browser');
                var encodedDeviceUuid = encodeValue('browser');
                var encodedDeviceVersion = encodeValue('browser');

                var deferred  = $q.defer();
                $ionicLoading.show({template: 'Submitting Feedback...'});
                $http.get(Settings.getUrl() + 'feedback/submit_feedback/' +
                encodedMessage + '/' +
                encodedCordova + '/' +
                encodedDeviceModel + '/' +
                encodedDevicePlatform + '/' +
                encodedDeviceUuid + '/' +
                encodedDeviceVersion  + '/' +
                encodedContact).
                    success(function(data, status, headers, config) {
                        $ionicLoading.hide();
                        deferred.resolve(data);
                    }).
                    error(function(data, status, headers, config) {
                        $ionicLoading.hide();
                        deferred.reject(status);
                    });

                return deferred.promise;
            }
        }
    })
