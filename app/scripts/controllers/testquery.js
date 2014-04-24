/*global $:false */
'use strict';

angular.module('trackingApp')
  .controller('testqueryCtrl', ['$scope', '$http',   function ($scope, $http ) {


    $scope.testquery = function (){
      $http({
        method: 'POST',
        url: 'api/resource.php',
        data: 'action=testquery',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
      })
     .success(function(data){
          $scope.mydata = data ;
          $('.jumbotron').hide(200);
          $scope.toggleconnect = 1;
        })
     .error(function(data, status){
          $scope.showerror = 1;
          $scope.errormessage = 'Status response: '+status ;
        });
    };

    $scope.showdesc = function(pageId, languageId){
      console.log(pageId, languageId);
      $http({
        method: 'POST',
        url: 'api/resource.php',
        data: 'action=webtitledesc&PAGE_ID='+pageId+'&LANGUAGE_ID='+languageId ,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
      })
     .success(function(data){
          $scope.toggleconnect = 0;
          $scope.toggledesc = 1;
          console.log(data);
          console.log(data.resultset);
          $scope.mydesc = data.resultset[0];
        });
        
    };
        
    $scope.backtolist = function(){$scope.toggleconnect = 1;$scope.toggledesc = 0;};

  }]);
