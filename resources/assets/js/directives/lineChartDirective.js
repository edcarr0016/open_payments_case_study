(function(){

    'use strict';

    angular
        .module('openPayments')
        .directive('lineChart', lineChart);

    lineChart.$inject = [];

    function lineChart(){
        return{
            restrict: 'AE',
            scope: {
                datadump:'='
            },
            templateUrl: '/views/directives/_line_chart_template.html',
            link: function(scope, element, attr){
                scope.labels = scope.datadump.labels;
                scope.data = scope.datadump.set;
                scope.series = scope.datadump.series;
            }
        };
    }
})();