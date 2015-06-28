var app = angular.module('flightApp', [], function($locationProvider) {
  $locationProvider.html5Mode({
    enabled: true,
    requireBase: false
  });
});

app.controller('flightController', function($scope, $http) {
  $http.get('http://destination.bizmate.space/api/tos/VIE/froms/LHR/startdates/2015-07-10/enddates/2015-07-11.json').
    success(function(data, status, headers, config) {
      $scope.flights = data;
    }).
    error(function(data, status, headers, config) {
      // log error
    });
});
