/* global app: false */
/* global angular: false */

app.directive("activeLink", ["$window", function ($window) {
    return {
        restrict: "A", // use as attribute
        replace: false,
        link: function (scope, elem) {
            var p = $window.location.pathname;
            var i = p.indexOf("/", p.indexOf("/") + 1);
            var path = p.substring(i + 1);
            angular.forEach(elem.find("a"), function (a) {
                a = angular.element(a);
                if ((path === a.attr("href")) || (path === "" && a.attr("href") === "index")) {
                    a.removeAttr("data-hover");
                    a.parent().addClass("active");
                }
                // else {
                // a.parent().removeClass("active");
                // }
            });
        }
    };
}]);

app.directive("myNav", function () {
    return {
        restrict: "AEC",
        scope: {},
        templateUrl: "views/nav.html"
    };
});

app.directive("myFooter", function () {
    return {
        restrict: "AEC",
        replace: true,
        templateUrl: "views/footer.html"
    };
});

app.directive("msgInfo", function () {
    return {
        restrict: "AEC",
        scope: {
            info: "="
        },
        templateUrl: "views/msg.html"
    };
});

app.directive("courseInfo", function () {
    return {
        restrict: "AEC",
        replace: true,
        scope: {
            info: "="
        },
        templateUrl: "views/course.html"
    };
});

app.directive("tutorInfo", function () {
    return {
        restrict: "AEC",
        scope: {
            info: "="
        },
        templateUrl: "views/tutor.html"
    };
});
