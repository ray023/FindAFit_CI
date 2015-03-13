/* ===========================================================================
 * FindAFit
 * ===========================================================================
 * Copyright 2015 Ray Nowell
 * Licensed under MIT (https://github.com/ray023/FindAFit/blob/master/LICENSE)
 * =========================================================================== */
'use strict';

fafApp

    .factory('Address', function($http, $q, Settings, $ionicLoading) {
        return {
            getByAddress: function(search_term) {
                var encodedAddress = encodeURIComponent(search_term)
                var deferred  = $q.defer();

                $ionicLoading.show({template: 'Loading...'});
                $http.get(Settings.getUrl() +
                            'address/get_json/' +
                            encodedAddress + '/' +
                            Settings.getBoxResultCount()).
                    success(function(data,status,headers,config){
                        localStorage.setItem('boxesByAddress', JSON.stringify(data)); //Attach to scope
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
                var retrievedObject = JSON.parse(localStorage.getItem('boxesByAddress'));
                for (var i = 0; i < retrievedObject.length; i++) {
                    if (retrievedObject[i].af_id === boxId)
                    {
                        if (!retrievedObject[i].url)
                            retrievedObject[i].url = Settings.getGoogleSearchUrl() +
                                                        retrievedObject[i].affil_name.replace('/ /g','+');
                        return retrievedObject[i];
                    }
                };
                return null;
            }
        }
    });
