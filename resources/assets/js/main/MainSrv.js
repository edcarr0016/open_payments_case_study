(function(){

    'use strict';

    angular
        .module('openPayments')
        .factory('MainService', MainService);

    MainService.$inject = ['$q', '$http'];

    function MainService($q, $http){

        var service = {
            getChartData: getChartData,
            paymentSearch: paymentSearch,
            downloadExcel: downloadExcel
        };

        return service;

        function getChartData(){
            var deferred = $q.defer();

            var url = '/v1/charts';

            $http.get(url)
                .success(function(response){
                    deferred.resolve(response);
                })
                .error(function(err){
                    deferred.reject(err);
                });

            return deferred.promise;
        }

        function paymentSearch(query){
            var deferred = $q.defer();

            var url = '/v1/search/' + query;

            $http.get(url)
                .success(function(response){
                    deferred.resolve(response);
                })
                .error(function(err){
                    deferred.reject(err);
                });

            return deferred.promise;
        }

        function downloadExcel(query){
            var deferred = $q.defer();

            var url = '/v1/search/' + query + '/download';

            $http.get(url, { responseType: "arraybuffer"})
                .success(function(data, status, headers){

                    var type = headers('Content-Type');

                    var disposition = headers('Content-Disposition');

                    var filename = disposition.substring(disposition.indexOf('filename="') + 10,disposition.lastIndexOf('"'));

                    var blob = new Blob([data], { type: type });

                    saveAs(blob, filename);

                    deferred.resolve(filename);
                })
                .error(function(err){
                    deferred.reject(err);
                });

            return deferred.promise;
        }
    }
})();