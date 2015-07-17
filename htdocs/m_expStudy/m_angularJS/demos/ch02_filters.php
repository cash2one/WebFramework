<html>
<!--
Filters allow you to declare how to transform data for display to the user within an
interpolation in your template. The syntax for using filters is:
{{ expression | filterName : parameter1 : ...parameterN }}
where expression is any Angular expression, filterName is the name of the filter you
want to use, and the parameters to the filter are separated by colons. The parameters
themselves can be any valid Angular expression.
-->
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
</head>

<body ng-app="HomeModule" ng-controller="HomeController">
    <h1>{{pageHeading | titleCase}}</h1>
    <script>

        var homeModule = angular.module("HomeModule", []);
        homeModule.filter("titleCase", function() {
            var titleCaseFilter = function(input) {
                var words = input.split(' ');
                for (var i = 0; i < words.length; i++) {
                    words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
                }
                return words.join(' ');
            }

            return titleCaseFilter;
        });

        function HomeController($scope) {
            $scope.pageHeading = "behold the majesty of your page title";
        }
    </script>
</body>

</html>
