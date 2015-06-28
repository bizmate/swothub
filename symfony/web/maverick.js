var app = angular.module('maverickApp', [], function($locationProvider) {
  $locationProvider.html5Mode({
    enabled: true
  });
});

app.controller('MaverickController', function($scope, $http, $location) {
  var budget = $location.search()['budget']
  var startdates = $scope.start = $location.search()['start']
  var enddates = $scope.end = $location.search()['end']

  $http.get('api/tos/'+'FCO'+'/froms/LHR/startdates/'+startdates+'/enddates/'+enddates+'.json').
    success(function(data, status, headers, config) {
      $scope.experiences = data['local_offers'];
      $scope.accommodations = data['hotels'];
      $scope.destinations = data['itineraries'];
      var humanName;
      $http.get('api/citynames/'+'FCO'+'.json').
        success(function(data, status, headers, config) {
          $scope.destinations.forEach(function(entry) {
            entry.name= data;
          });
        })
        .error(function(data, status, headers, config) {});
      // maverick.destinations[0].price = data['itineraries'][0]['total']
    }).
    error(function(data, status, headers, config) {
      // log error
    });

    $scope.changedDestination = function(code) {
      $http.get('api/tos/'+code+'/froms/LHR/startdates/'+startdates+'/enddates/'+enddates+'.json').
        success(function(data, status, headers, config) {
          $scope.experiences = data['local_offers'];
          $scope.accommodations = data['hotels'];
          var humanName;
          $http.get('api/citynames/'+'FCO'+'.json').
            success(function(data, status, headers, config) {
              $scope.destinations.forEach(function(entry) {
                entry.name= data;
              });
            })
            .error(function(data, status, headers, config) {});
          // maverick.destinations[0].price = data['itineraries'][0]['total']
        }).
        error(function(data, status, headers, config) {
          // log error
        });
    };
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
