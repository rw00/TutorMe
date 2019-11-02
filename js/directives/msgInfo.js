app.directive("msgInfo", function () {
    return {
        restrict: 'E',
        scope: {
            info: '='
        },
        templateUrl: 'views/msg.html'
    };
});
