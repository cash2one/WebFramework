<html ng-app>
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
    <style>
        .highlight {color: lightblue;}
    </style>
</head>

<body>
    <div ng-controller='SomeController'>
        <div>{{recompute() / 10}}</div>
        <ul ng-repeat='thing in things'>
            <li ng-class='{highlight: $index % 4 >= threshold($index)}'>{{otherFunction($index)}}</li>
        </ul>
    </div>

    <script>
        function SomeController($scope) {
            $scope.things = [1, 7, 2, 6, 3, 5, 4, 8, 0];

            $scope.recompute = function() {
                return 100;
            };

            $scope.threshold = function(index) {
                return $scope.things[index] % 4;
            };

            $scope.otherFunction = function(index) {
                return $scope.things[index] * 2;
            };
        }
    </script>
</body>

</html>
