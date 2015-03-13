/* ===========================================================================
 * FindAFit
 * ===========================================================================
 * Copyright 2015 Ray Nowell
 * Licensed under MIT (https://github.com/ray023/FindAFit/blob/master/LICENSE)
 * =========================================================================== */
'use strict';

fafApp

    .controller('AddressCtrl', function($scope, $ionicPopup, Address) {

        $scope.searchByTerm = function(search_term) {
            $scope.data         = null;
            if (search_term) {
                Address.getByAddress(search_term).then(
                    function(boxes){
                        if (boxes.length > 0)
                            $scope.data = boxes;
                        else {
                            $ionicPopup.alert({
                                title: 'No results',
                                okType: 'button-assertive',
                                template: 'No boxes found.'
                            });
                        }
                    },
                    function(statusCode) {
                        var statusMessage = 'Server Error:  ' + statusCode;
                        if (statusCode == '404')
                            statusMessage = 'Could not connect to server.  Please make sure you have network connectivity.';

                        $ionicPopup.alert({
                            title: 'Server Error: ' + statusCode,
                            okType: 'button-assertive',
                            template: statusMessage
                        });
                    });
            }
            else {
                $ionicPopup.alert({
                    title: 'Field Required',
                    okType: 'button-energized',
                    template: 'Search term required.'
                });
            }
        };
    })

    .controller('AddressDetailCtrl', function($scope, $stateParams, Address) {

        $scope.data = Address.get($stateParams.boxId);

        $scope.siteClick = function(){
            window.open($scope.data.url, '_blank', 'location=yes');
        };

        $scope.navClick = function(){
            window.open($scope.data.nav_link, '_system', 'location=yes');
        };

        $scope.facebookClick = function(){
            window.open($scope.data.facebook, '_blank', 'location=yes');
        };

        $scope.twitterClick = function(){
            window.open($scope.data.twitter, '_blank', 'location=yes');
        };

        $scope.instagramClick = function(){
            window.open($scope.data.instagram, '_blank', 'location=yes');
        };

        $scope.googlePlusClick = function(){
            window.open($scope.data.google_plus, '_blank', 'location=yes');
        };

        $scope.softwareLinkClick = function(){
            window.open($scope.data.software_hyperlink, '_system', 'location=yes');
        };
    })
;