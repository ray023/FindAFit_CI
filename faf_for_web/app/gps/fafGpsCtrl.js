/* ===========================================================================
 * FindAFit
 * ===========================================================================
 * Copyright 2015 Ray Nowell
 * Licensed under MIT (https://github.com/ray023/FindAFit/blob/master/LICENSE)
 * =========================================================================== */
'use strict';

fafApp

    .controller('GpsCtrl',
    function($scope, $ionicPopup, Boxes, Location) {
        $scope.BoxModel = Boxes;
        Location.getCurrentPosition().then(
            function(position){
                Boxes.getByLocation(position.coords.latitude, position.coords.longitude)
                    .then(function (boxes) {
                        $scope.data = boxes;
                    },
                    function (statusCode) {
                        var statusMessage = 'Server Error:  ' + statusCode;
                        if (statusCode == '404')
                            statusMessage = 'Could not connect to server.  Please make sure you have network connectivity.';

                        $ionicPopup.alert({
                            title: 'Server Error: ' + statusCode,
                            okType: 'button-assertive',
                            template: statusMessage
                        });
                    });
            },
            function(error){
                $ionicPopup.alert({
                    title: 'GPS Error',
                    okType: 'button-assertive',
                    template: error.errorCode + ': ' + error.errorMessage
                })
            }
        );


        $scope.refreshBoxList = function() {
            Location.getCurrentPosition().then(
                function(position){
                    Boxes.getByLocation(position.coords.latitude, position.coords.longitude)
                        .then(function (boxes) {
                            $scope.data = boxes;
                        },
                        function (statusCode) {
                            var statusMessage = 'Server Error:  ' + statusCode;
                            if (statusCode == '404')
                                statusMessage = 'Could not connect to server.  Please make sure you have network connectivity.';

                            $ionicPopup.alert({
                                title: 'Server Error: ' + statusCode,
                                okType: 'button-assertive',
                                template: statusMessage
                            });
                        });
                },
                function(error){
                    $ionicPopup.alert({
                        title: 'GPS Error',
                        okType: 'button-assertive',
                        template: error.errorCode + ': ' + error.errorMessage
                    })
                }
            );
            $scope.$broadcast('scroll.refreshComplete');
        };

    })

    .controller('GpsDetailCtrl', function($scope, $stateParams, Boxes) {
        $scope.data = Boxes.get($stateParams.boxId);

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

    });