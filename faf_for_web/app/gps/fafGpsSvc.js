/* ===========================================================================
 * FindAFit
 * ===========================================================================
 * Copyright 2015 Ray Nowell
 * Licensed under MIT (https://github.com/ray023/FindAFit/blob/master/LICENSE)
 * =========================================================================== */
'use strict';

fafApp

    .factory('Boxes', function($http, $q, Settings, $ionicLoading) {
        return {
            getByLocation: function(latitude, longitude) {
                var deferred  = $q.defer();
                $ionicLoading.show({template: 'Loading...'});
                $http.get(Settings.getUrl() +
                            'gps/get_json/' +
                            latitude + '/' +
                            longitude + '/' +
                            Settings.getBoxResultCount()).
                    success(function(data,status,headers,config){
                        localStorage.setItem('boxesByGps', JSON.stringify(data)); //TODO:  Attach to scope
                        $ionicLoading.hide();
                        deferred.resolve(data);
                    }).
                    error(function(data,status,headers,config){
                        $ionicLoading.hide();
                        deferred.reject(status);
                    });
                return deferred.promise;
            },
            get: function(boxId) {
                var retrievedObject = JSON.parse(localStorage.getItem('boxesByGps'));
                for (var i = 0; i < retrievedObject.length; i++) {
                    if (retrievedObject[i].af_id === boxId)
                    {
                        if (!retrievedObject[i].url)
                            retrievedObject[i].url = Settings.getGoogleSearchUrl() + retrievedObject[i].affil_name.replace('/ /g','+');
                        return retrievedObject[i];
                    }
                };
                return null;
            }
        }
    })

    .factory('Location', function($q, $ionicLoading) {
        return {
            getCurrentPosition: function() {
                var deferred  = $q.defer();
                $ionicLoading.show({template: 'Getting Location...'});
                navigator.geolocation
                    .getCurrentPosition(
                    function (data) {
                        $ionicLoading.hide();
                        deferred.resolve(data);
                    },
                    function (error) {
                        $ionicLoading.hide();
                        var errorMessage = 'Error getting current position.';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = "User denied the request for Geolocation."
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = "Location information is unavailable."
                                break;
                            case error.TIMEOUT:
                                errorMessage = "The request to get user location timed out.<br>Please turn on (or restart) Location services on your device."
                                break;
                            case error.UNKNOWN_ERROR:
                                errorMessage = "An unknown error occurred."
                                break;
                        }

                        var data = {errorCode: error.code,
                                        errorMessage: errorMessage};
                        deferred.reject(data);
                    },
                    {timeout:5000, enableHighAccuracy:false});

                return deferred.promise;
            }
        }
    }
);

