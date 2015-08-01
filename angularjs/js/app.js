(function () {
    'use strict';

    /* App Module */

    var pollsApp = angular.module('pollsApp', [
      'ngRoute',
      'homeControllers'//,
      //'pollsControllers'
    ]);

    pollsApp.config(['$routeProvider',
      function($routeProvider) {
        $routeProvider.
          when('/home', {
            templateUrl: 'angularjs/partials/home.html',
            controller: 'HomeCtrl'
          }).
          when('/polls', {
            templateUrl: 'angularjs/partials/poll-list.html',
            controller: 'PollListCtrl'
          }).
          when('/polls/create', {
            templateUrl: 'angularjs/partials/poll-create.html',
            controller: 'PollCreateCtrl'
          }).
          when('/polls/:pollId', {
            templateUrl: 'angularjs/partials/poll-detail.html',
            controller: 'PollDetailCtrl'
          }).
          when('/votes/:pollId', {
            templateUrl: 'angularjs/partials/vote-detail.html',
            controller: 'VoteDetailCtrl'
          }).
          when('/about', {
            templateUrl: 'angularjs/partials/about.html',
            controller: 'AboutCtrl'
          }).
          otherwise({
            redirectTo: '/home'
          });
      }]);

}())
