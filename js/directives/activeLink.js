app.directive('activeLink', ['$window', function ($window) {
    return {
        restrict: 'A', //use as attribute 
        replace: false,
        link: function (scope, elem) {
            //after the route has changed
            var p = $window.location.pathname;
            var i = p.indexOf("/", p.indexOf("/") + 1);
            var path = p.substring(i + 1);
            angular.forEach(elem.find('a'), function (a) {
                a = angular.element(a);
                if (path === a.attr('href')) {
                    a.removeAttr('data-hover');
                    a.parent().addClass('active');
                } else {
                    a.parent().removeClass('active');
                }
            });
        }
    }
}]);