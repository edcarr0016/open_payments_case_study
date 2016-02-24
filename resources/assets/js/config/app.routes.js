// Routes
(function(){
    'use strict';

    angular
        .module('openPayments')
        .config(config, 'config');

    config.$inject = ['$stateProvider', '$urlRouterProvider'];
    function config($stateProvider, $urlRouterProvider) {

        // Home
        $stateProvider.state('main', {
            url: '/',
            templateUrl: '/views/main/index.html',
            controller: 'MainCtrl',
            controllerAs: 'main',
        });
        $urlRouterProvider.otherwise('/');
    }
})();