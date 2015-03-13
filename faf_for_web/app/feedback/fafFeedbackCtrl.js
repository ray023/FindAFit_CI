/* ===========================================================================
 * FindAFit
 * ===========================================================================
 * Copyright 2015 Ray Nowell
 * Licensed under MIT (https://github.com/ray023/FindAFit/blob/master/LICENSE)
 * =========================================================================== */
'use strict';

fafApp

    .controller('FeedbackCtrl', function($scope, $ionicPopup, Feedback) {

        $scope.send = function(feedback, contact){
            if (!feedback) {
                $ionicPopup.alert({
                    title: 'Feedback required',
                    okType: 'button-energized',
                    template: 'Please enter your feedback'
                });
                return;
            }

            Feedback.send(feedback, contact).then(
                function(response){
                    $ionicPopup.alert({
                        title: 'Response',
                        okType: 'button-balanced',
                        template: response
                    });
                },
                function(statusCode){
                    $ionicPopup.alert({
                        title: 'Server Error',
                        okType: 'button-assertive',
                        template: 'Server error:  ' + statusCode
                    });
                });
        };
    });