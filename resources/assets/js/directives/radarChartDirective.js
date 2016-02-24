(function(){

    'use strict';

    angular
        .module('openPayments')
        .directive('radarChart', radarChart);

    radarChart.$inject = [];

    function radarChart(){
        return{
            restrict: 'AE',
            scope: {
                datadump:'='
            },
            templateUrl: '/views/directives/_radar_chart_template.html',
            link: function(scope, element, attr){
                scope.labels = scope.datadump.labels;
                scope.data = scope.datadump.set;
                scope.series = scope.datadump.series;
            }
        };
    }
})();