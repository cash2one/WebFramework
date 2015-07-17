<html ng-app="myApp">
<!--
    In the previous example, we’ve created TextController in the global scope. W
    While this is fine for examples, the right way to define a controller is as part of something called a module,
    which provides a namespace for related parts of your application
-->
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
</head>

<body ng-controller="TextController">
    <p>{{someText.message}}</p>

    <script>
        var myAppModule = angular.module("myApp", []);
        myAppModule.controller("TextController", function($scope) {
            var someText = {};
            someText.message = "You have started my journey.";
            $scope.someText = someText;
        });
    </script>
</body>

</html>
