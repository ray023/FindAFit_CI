/* ===========================================================================
 * FindAFit
 * ===========================================================================
 * Copyright 2015 Ray Nowell
 * Licensed under MIT (https://github.com/ray023/FindAFit/blob/master/LICENSE)
 * =========================================================================== */
'use strict';

fafApp

    .controller('NewsCtrl', function($scope, $ionicPopup, News) {
        $scope.NewsModel = News;
        News.getAll().then(
            function(news){$scope.news = news;},
            function(statusCode){
                var statusMessage = 'Server Error:  ' + statusCode;
                if (statusCode == '404')
                    statusMessage = 'Could not connect to server.  Please make sure you have network connectivity.';

                $ionicPopup.alert({
                    title: 'Server Error: ' + statusCode,
                    okType: 'button-assertive',
                    template: statusMessage
                });
            });

        $scope.doRefresh = function() {
            News.getAll().then(
                function(news){
                    $scope.news = news;
                    $scope.$broadcast('scroll.refreshComplete');
                },
                function(statusCode){
                    $scope.$broadcast('scroll.refreshComplete');
                    var statusMessage = 'Server Error:  ' + statusCode;
                    if (statusCode == '404')
                        statusMessage = 'Could not connect to server.  Please make sure you have network connectivity.';

                    $ionicPopup.alert({
                        title: 'Server Error: ' + statusCode,
                        okType: 'button-assertive',
                        template: statusMessage
                    });
                });
        };
    });