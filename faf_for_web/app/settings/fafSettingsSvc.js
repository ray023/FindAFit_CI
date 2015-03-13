/* ===========================================================================
 * FindAFit
 * ===========================================================================
 * Copyright 2015 Ray Nowell
 * Licensed under MIT (https://github.com/ray023/FindAFit/blob/master/LICENSE)
 * =========================================================================== */
'use strict';

fafApp

    .factory('Settings', function() {

        return {
            saveBoxResultCount: function(value) {
                localStorage.setItem("box_result_count",value);
            },
            getBoxResultCount: function() {
                var boxResultCount = localStorage.getItem("box_result_count") === null ? 5 : localStorage.getItem("box_result_count");
                return boxResultCount;
            },
            getUrl: function(){return 'http://findafit.info/index.php/';},
            getGoogleSearchUrl: function(){return 'https://www.google.com/search?site=&source=hp&q=';}
        }
    });
