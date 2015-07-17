<html ng-app>
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
</head>

<body>
    <div ng-controller="CountController">
        <button ng-click='count=3'>Set count to three</button>
        <button ng-click='setCount()'>Set count to five</button>
        <input ng-model='count' />
    </div>

    <script>
        function CountController($scope) {
            $scope.count = 4;
        
            $scope.setCount = function() {
                $scope.count = 5;
            }
        }
    </script>
</body>

</html>
