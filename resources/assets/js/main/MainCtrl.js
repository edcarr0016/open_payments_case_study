(function(){

    'use strict';

    angular
        .module('openPayments')
        .controller('MainCtrl',MainCtrl)
        .config(function($sceProvider){
            $sceProvider.enabled(false);
        });

    MainCtrl.$inject = ['$scope', '$interval', '$sce', '$window', 'MainService'];

    function MainCtrl($scope, $interval, $sce, $window, MainService){

        $scope.chart = {
            typeSummary : {
                id: 'type-summary',
                title: 'Payment Amounts by Type (2014)',
                labels: [],
                set: []
            },
            typeAndMonthSummary : {
                id: 'type-month-summary',
                title: 'Payment Amounts by Type and Month',
                labels: [],
                set: [],
            }
        };

        $scope.hits = [];

        var setChartData = function(){
            MainService.getChartData().then(function(response){
                $scope.chart.typeSummary.labels = response.data.type.labels;
                $scope.chart.typeSummary.set = [response.data.type.set];

                $scope.chart.typeAndMonthSummary.labels = response.data.typeAndMonth.labels;
                $scope.chart.typeAndMonthSummary.set = response.data.typeAndMonth.set;
                $scope.chart.typeAndMonthSummary.series = response.data.type.labels;
            });
        };

        var updateChartData = function(){
            MainService.getChartData().then(function(response){
               if($scope.chart.typeSummary.labels.length == response.data.type.labels.length){
                   for(var i = $scope.chart.typeSummary.set[0].length - 1; i >= 0; i--){
                       //console.log("Type- Old: " + $scope.chart.typeSummary.set[0][i] + " New: " + response.data.type.set[i]);
                       $scope.chart.typeSummary.set[0][i] = response.data.type.set[i];
                   }

                   for(var i = $scope.chart.typeAndMonthSummary.set.length - 1; i >= 0; i--){
                       for(var j = $scope.chart.typeAndMonthSummary.set[i].length - 1; j >= 0; j--){
                           //console.log("TypeAndMonth- Old: " + $scope.chart.typeAndMonthSummary.set[i][j] + " New: " + response.data.typeAndMonth.set[i][j]);
                           $scope.chart.typeAndMonthSummary.set[i][j] = response.data.typeAndMonth.set[i][j];
                       }
                   }
               }else{
                   // Have to find a way to set the data and re-render the directives on the page to show new labels
                   //Shotgun approach: Reload the whole page
                   if($scope.focus){
                       $window.location.reload();
                   }

               }
            });
        };

        setChartData();

        var refresher = $interval(function(){
         //console.log("A Call would occur");
         updateChartData();
         }, 5000);

        $scope.search = function(){
            MainService.paymentSearch($scope.query).then(function(response){
                $scope.hits = [];
                for(var i = 0; i < response.length; i++){
                    $scope.hits.push($sce.trustAsHtml(response[i].display));
                }
                //$scope.hits = $sce.trustAsHtml(response.display);
            }, function(err){
                $scope.hits = [];
            });
        }

        $scope.download = function(){
            MainService.downloadExcel($scope.query);
        }
    };
})();