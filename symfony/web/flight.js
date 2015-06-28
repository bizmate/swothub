var app = angular.module('flightApp', [], function($locationProvider) {
  $locationProvider.html5Mode({
    enabled: true,
    requireBase: false
  });
});

app.controller('flightController', function($scope, $http, $location) {
  var from = $scope.from = $location.search()['from']
  var to = $scope.to = $location.search()['to']
  $http.get('http://localhost:8889/api/tos/'+to+'/froms/'+from+'/startdates/2015-07-10/enddates/2015-07-11.json').
    success(function(data, status, headers, config) {
      $scope.flights = data;
    }).
    error(function(data, status, headers, config) {
      // log error
    });
});
