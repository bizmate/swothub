var app = angular.module('flightApp', [], function($locationProvider) {
  $locationProvider.html5Mode({
    enabled: true
  });
});

app.controller('flightController', function($scope, $http, $location) {
  var from = $location.search()['from']
  var to = $location.search()['to']
  var startdates = $scope.start = $location.search()['start']
  var enddates = $scope.end = $location.search()['end']
  $http.get('api/tos/'+to+'/froms/'+from+'/startdates/'+startdates+'/enddates/'+enddates+'.json').
    success(function(data, status, headers, config) {
      $scope.flights = data;
    }).
    error(function(data, status, headers, config) {
      // log error
    });
    $http.get('api/citynames/'+from+'.json').
      success(function(data, status, headers, config) {
        $scope.from = data;
      }).
      error(function(data, status, headers, config) {
        // log error
      });
      $http.get('api/citynames/'+to+'.json').
        success(function(data, status, headers, config) {
          $scope.to = data;
            $scope.toImage = './images/destination/'+data+'.jpg';
        }).
        error(function(data, status, headers, config) {
          // log error
        });

});

app.directive('backImg', function(){
  return function(scope, element, attrs){
    attrs.$observe('backImg', function(value) {
      element.css({
        'background-image': 'url(' + value +')',
        'background-size' : 'cover'
      });
    });
  };
});
