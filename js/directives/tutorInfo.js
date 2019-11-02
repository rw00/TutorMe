app.directive("tutorInfo", function () {
    return {
        restrict: 'E',
        scope: {
            info: '='
        },
        templateUrl: 'views/tutor.html'
    };
});
