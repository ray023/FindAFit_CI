/* ===========================================================================
 * FindAFit
 * ===========================================================================
 * Copyright 2015 Ray Nowell
 * Licensed under MIT (https://github.com/ray023/FindAFit/blob/master/LICENSE)
 * =========================================================================== */
'use strict';

fafApp.controller('SettingsCtrl', function($scope, Settings) {

    $scope.data = {
            boxResultCount: Settings.getBoxResultCount(),
            boxCountList: [
                { text: "5", value: "5" },
                { text: "10", value: "10" },
                { text: "20", value: "20" },
                { text: "50", value: "50" }
            ]
        };
    $scope.saveBoxCount = function(value){Settings.saveBoxResultCount(value)};
});

