<html ng-app> <!-- The ng-app attribute tells Angular which parts of the page it should manage. -->
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
    <title>Your Shopping Cart</title>
</head>

<body ng-controller="CartController"> <!-- you manage areas of the page with JavaScript classes called controllers -->
    <h1>Your Order</h1>
    <div ng-repeat='item in items'>
        <span>{{item.title}}</span> <!-- The {{ }} in the <span> sets up a one-way relationship that says “insert a value here.” -->
        <input ng-model='item.quantity' />
        <span>{{item.price|currency}}</span>
        <span>{{item.price * item.quantity | currency}}</span>
        <button ng-click='remove($index)'>Remove</button>
    </div>

    <script>
        function CartController($scope) {
            $scope.items = [
                {title: 'Paint pots', quantity: 8, price: 3.95},
                {title: 'Polka dots', quantity: 17, price: 12.95},
                {title: 'Pebbles', quantity: 5, price: 6.95}
            ];

            $scope.remove = function(idx) {
                $scope.items.splice(idx, 1);
            }
        }
    </script>
</body>

</html>
