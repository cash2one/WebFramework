<html ng-app>
<!--
One thing to keep in mind: you have to use the declared form of dependency injection
(specifying the $inject property on the controller) when you want to minify
your code.
-->
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
</head>

<body>
    <div ng-controller='MyController'>
    </div>

    <script>
        function MyController($scope, $resource) {
            // Same stuff here
        }
        MyController.$inject = ['$scope', '$resource'];

        // or use the module, like so:
        myAppModule.controller(‘MyController’, [‘$scope’, ‘$resource’,
            function($scope, $resource) {
                // Same stuff here
            }]);
    </script>
</body>

</html>
