'use strict';

angular.module('trackingApp')
.controller('navCtrl', ['$scope', '$location', function ($scope, $location) {

        $scope.isActive = function(path) {
            if (($location.path().substr(0, path.length) === path)&&(path.length===$location.path().length)  ) {
              return true;
            } else {
              return false;
            }
          };

      }]);