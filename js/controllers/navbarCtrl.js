app.controller('NavbarCtrl', function ($scope, $window) {
    $scope.isActive = function (route) {
        return route === $window.location.pathname;
    }
});