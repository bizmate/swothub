var app = angular.module('maverickApp', [], function($locationProvider) {
  $locationProvider.html5Mode({
    enabled: true
  });
});

app.controller('MaverickController', function($scope, $http, $location) {
  var maverick = this;
  maverick.destinations = [{name:'Berlin', code: 'TXL', bgURL: './images/destination/berlin.jpg', price: 500},
  {name:'Vienna', code: 'VIE', bgURL:'./images/destination/vienna.jpg', price: 600}];
  maverick.experiences = [{name:'Skydiving', bgURL: './images/experience/skydiving.png', price: 300},
  {name:'Fishing', bgURL:'./images/experience/fishing.jpg', price: 30}];
  maverick.accommodations = [{name:'Hotel', bgURL: './images/accommodation/premium.jpg', price: 70},
  {name:'Hostel', bgURL:'./images/accommodation/hostel.jpg', price: 20}];
  var budget = $location.search()['budget']
  var startdates = $location.search()['start']
  var enddates = $location.search()['end']
  $http.get('http://localhost:8889/app_dev.php/api/tos/'+maverick.destinations[0].code+'/froms/LHR/startdates/'+startdates+'/enddates/'+enddates+'.json').
  success(function(data, status, headers, config) {
    $scope.flights = data;
    maverick.destinations[0].price = data['itineraries'][0]['total']
  }).
  error(function(data, status, headers, config) {
    // log error
  });

  maverick.loadDestinations = function() {
    maverick.destinations = [
      {name:'Berlin', url: './images/destination/berlin.png', price: 500},
      {name:'Rio de Janiero', url:'./images/destination/rio.png', price: 600}];
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
