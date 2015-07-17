<html ng-app="myApp">
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
</head>

<body ng-controller="TextController">
    <p>{{greeting}}</p>
    <p ng-bind='greeting'></p>

    <script>
        var myAppModule = angular.module("myApp", []);
        myAppModule.controller("TextController", function($scope) {
            $scope.greeting = "You have started my journey.";
        });
    </script>
</body>

</html>
