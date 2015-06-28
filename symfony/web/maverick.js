var app = angular.module('maverickApp', [], function($locationProvider) {
  $locationProvider.html5Mode({
    enabled: true
  });
});

app.controller('MaverickController', function($scope, $http, $location) {
  var budget = $location.search()['budget']
  var startdates = $location.search()['start']
  var enddates = $location.search()['end']

  $http.get('api/tos/'+'FCO'+'/froms/LHR/startdates/'+startdates+'/enddates/'+enddates+'.json').
    success(function(data, status, headers, config) {
      $scope.experiences = data['local_offers'];
      $scope.accommodations = data['hotels'];
      $scope.destinations = data['itineraries'];

      // maverick.destinations[0].price = data['itineraries'][0]['total']
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
