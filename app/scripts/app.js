'use strict';

angular
  .module('trackingApp', [
    'ngCookies',
    'ngResource',
    'ngSanitize',
    'ngRoute'
  ])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html',
        controller: 'MainCtrl'
      })
      .when('/testquery', {
        templateUrl: 'views/testquery.html',
        controller: 'testqueryCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
