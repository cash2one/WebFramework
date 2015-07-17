<html ng-app="app">
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
</head>

<body ng-controller="SomeController">
    <button ng-click="clickUnfocused()">Not focused</button>
    <button ngbk-focus ng-click="clickFocused()">I'm very focused!</button>
    <div>{{message.text}}</div>
</body>

<script>
    var appModule = angular.module("app", []);
    appModule.directive("ngbkFocus", function() {
        return {
            link: function(scope, element, attrs, controller) {
                element[0].focus();
            }
        }
    });

    function SomeController($scope) {
        $scope.message = {text: 'nothing clicked yet'};

        $scope.clickUnfocused = function() {
            $scope.message.text = 'unfocused button clicked';
        };

        $scope.clickFocused = function() {
            $scope.message.text = 'focus button clicked';
        };
    }
</script>

</html>
