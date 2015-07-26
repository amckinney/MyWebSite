(function () {
    'use strict';

   /* Controllers */

   // Global "database"

   var homeControllers = angular.module('homeControllers', []);

   pollsControllers.controller('HomeCtrl', ['$scope', '$http',
      function($scope, $http) {
         $scope.author = 'Alex McKinney';
         $scope.email  = 'amckinney@berkeley.edu';
   }]);
}());
