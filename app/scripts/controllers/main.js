/*global $:false */
'use strict';

angular.module('trackingApp')
  .controller('MainCtrl', ['$scope', '$http',   function ($scope, $http ) {

    $scope.testJSON = function (){
      $http({
        method: 'POST',
        url: 'api/resource.php',
        data: '',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
      })
     .success(function(data){
          $('.jumbotron').hide(200);
          $scope.togglelist = 1;
          $scope.mydata = data.resources ;
          //console.log(data);
        });
    };

    $scope.showdesc = function(givenid){
      //console.log(givenid);
      $http({
        method: 'POST',
        url: 'api/resource.php',
        data: 'id='+givenid,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
      })
     .success(function(data){
          $scope.togglelist = 0;
          $scope.toggledesc = 1;
          $scope.mydesc = data ;
          //console.log(data);
        });
        
    };
    
    $scope.backtolist = function(){$scope.togglelist = 1;$scope.toggledesc = 0;};


  }]);
