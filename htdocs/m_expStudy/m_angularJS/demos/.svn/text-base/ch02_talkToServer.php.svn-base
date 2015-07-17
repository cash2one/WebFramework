<html ng-app>
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
</head>

<body ng-controller="ShoppingController">
    <h1>Shop!</h1>
    <table>
        <tr ng-repeat="item in items">
            <td>{{item.title}}</td>
            <td>{{item.description}}</td>
            <td>{{item.price | currency}}</td>
        </tr>
    </table>

    <script>
    function ShoppingController($scope, $http) {
        $http.get('./products').success(function(data, status, headers, config) {
            $scope.items = data;
        });
    }
    </script>
</body>

</html>
