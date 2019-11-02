app.directive("courseInfo", function () {
    return {
        restrict: 'AEC',
        replace: true,
        scope: {
            info: '='
        },
        templateUrl: 'views/course.html'
    };
});
