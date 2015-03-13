/* ===========================================================================
 * FindAFit
 * ===========================================================================
 * Copyright 2015 Ray Nowell
 * Licensed under MIT (https://github.com/ray023/FindAFit/blob/master/LICENSE)
 * =========================================================================== */
'use strict';

fafApp.directive('fafAddressBlock', function() {
    return {
        templateUrl: "app/shared/partials/fafAddressBlock.html",
        restrict: "E"
    }
});

fafApp.directive('fafDropInRateBlock', function() {
    return {
        templateUrl: "app/shared/partials/fafDropInRateBlock.html",
        restrict: "E"
    }
});


fafApp.directive('fafMediaList', function() {
    return {
        templateUrl: "app/shared/partials/fafMediaList.html",
        restrict: "E"
    }
});

fafApp.directive('fafPhoneBlock', function() {
    return {
        templateUrl: "app/shared/partials/fafPhoneBlock.html",
        restrict: "E"
    }
});

fafApp.directive('fafSoftwareBlock', function() {
    return {
        templateUrl: "app/shared/partials/fafSoftwareBlock.html",
        restrict: "E"
    }
});