/* ===========================================================================
 * FindAFit
 * ===========================================================================
 * Copyright 2015 Ray Nowell
 * Licensed under MIT (https://github.com/ray023/FindAFit/blob/master/LICENSE)
 * =========================================================================== */
'use strict';

fafApp
    .factory('News', function($http, $q, $ionicLoading, Settings) {
        return {
            getAll: function() {
                var deferred  = $q.defer();

                $ionicLoading.show({template: 'Loading...'});
                $http({method: 'GET', url: Settings.getUrl() + 'news/get_json'}).
                    success(function(data,status,headers,config){
                        $ionicLoading.hide();
                        deferred.resolve(data);
                    }).
                    error(function(data,status,headers,config){
                        $ionicLoading.hide();
                        deferred.reject(status);
                    });

                return deferred.promise;
            }
        }
    });