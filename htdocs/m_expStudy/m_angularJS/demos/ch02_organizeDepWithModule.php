<!-- To get this to work with our template, we need to tell the ng-app directive the name of our module, like the following: -->
<html ng-app='ShoppingModule'>
<!--
Services are singleton (single-instance) objects that carry out the tasks necessary to
support your application’s functionality. Angular comes with many services like $loca
tion, for interacting with the browser’s location, $route, for switching views based on
location (URL) changes, and $http, for communicating with servers.

You can, and should, create your own services to do all of the tasks unique to your
application. Services can be shared across any controllers that need them. As such,
they’re a good mechanism to use when you need to communicate across controllers and
share state.

You define services with the module object’s API. There are three functions for creating
generic services, with different levels of complexity and ability:

provider(name, Object OR constructor() )
A configurable service with complex creation logic. If you pass an Object, it should have a
function named $get that returns an instance of the service. Otherwise, Angular assumes you’ve
passed a constructor that, when called, creates the instance.

factory(name, $getFunction() )
A non-configurable service with complex creation logic. You specify a function that, when
called, returns the service instance. You could think of this as provider(name, { $get:
$getFunction() } ).

service(name, constructor() )
A non-configurable service with simple creation logic. Like the constructor option with provider,
Angular calls it to create the service instance.
-->

<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
</head>

<body>
    <div ng-controller="ShoppingController">
        <ul>
            <li ng-repeat='item in items'>
                {{item.title}}
            </li>
        </ul>
    </div>

    <script>
    // Create a module to support our shopping views
    var shoppingModule = angular.module('ShoppingModule', []);

    // Set up the service factory to create our Items interface to the
    // server-side database
    shoppingModule.factory('Items', function() {
        var items = {};

        items.query = function() {
            // In real apps, we\'d pull this data from the server...
            return [
                {title: 'Paint pots', description: 'Pots full of paint', price: 3.95},
                {title: 'Polka dots', description: 'Dots with polka', price: 2.95},
                {title: 'Pebbles', description: 'Just little rocks', price: 6.95}
            ];
        };

        return items;
    });

    /*
    shoppingModule.controller("ShoppingController", function($scope, Items) {
        $scope.items = Items.query();
    });
    */

    function ShoppingController($scope, Items) {
        $scope.items = Items.query();
    }
    </script>
</body>

</html>
