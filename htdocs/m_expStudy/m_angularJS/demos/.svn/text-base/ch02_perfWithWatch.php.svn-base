<html ng-app>

<!--
Notice here that the $watch specified items as a string. This is possible because the
$watch function can take either a function (as we did previously) or a string. If a string
is passed to the $watch function, then it will be evaluated as an expression in the scope
of the $scope it’s called on.

This strategy might work well for your app. However, since we’re watching the items
array, Angular will have to make a copy of it to compare it for us. For a large list of items,
it may perform better if we just recalculate the bill properties every time Angular evaluates
the page.

Watching multiple things
What if you want to watch multiple properties or objects and execute a function whenever
any of them change? You’d have two basic options:
• Put them into an array or object and pass in deepWatch as true.
• Watch a concatenated value of the properties.

$scope.$watch('things.a + things.b', callMe(...));
$scope.$watch('things', callMe(...), true);
-->

<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
</head>

<body>
    <div ng-controller="CartController">
        <div ng-repeat="item in items">
            <span>{{item.title}}</span>
            <input ng-model="item.quantity">
            <span>{{item.price | currency}}</span>
            <span>{{item.price * item.quantity | currency}}</span>
        </div>
        <div>Total: {{bill.totalCart | currency}}</div>
        <div>Discount: {{bill.discount | currency}}</div>
        <div>Subtotal: {{bill.subtotal | currency}}</div>
    
    </div>


    <script>
    function CartController($scope) {
        $scope.bill = {};

        $scope.items = [
            {title: 'Paint pots', quantity: 8, price: 3.95},
            {title: 'Polka dots', quantity: 17, price: 12.95},
            {title: 'Pebbles', quantity: 5, price: 6.95}
        ];

        var calculateTotals = function() {
            var total = 0;
            for (var i = 0, len = $scope.items.length; i < len; i++) {
                total = total + $scope.items[i].price * $scope.items[i].quantity;
            }
            $scope.bill.totalCart = total;
            $scope.bill.discount = total > 100 ? 10 : 0;
            $scope.bill.subtotal = total - $scope.bill.discount;
        }

        // $scope.$watch('items', calculateTotals, true);

        $scope.$watch(function() {
            var total = 0;
            for (var i = 0; i < $scope.items.length; i++) {
                total = total + $scope.items[i].price * $scope.items[i].quantity;
            }

            $scope.bill.totalCart = total;
            $scope.bill.discount = total > 100 ? 10 : 0;
            $scope.bill.subtotal = total - $scope.bill.discount;
        });
    }
    </script>
</body>

</html>
