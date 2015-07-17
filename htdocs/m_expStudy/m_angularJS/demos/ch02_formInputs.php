<html ng-app="myApp">
<!--
1. When the user checks the box, a property called youCheckedIt on the SomeCon
troller’s $scope will become true. Unchecking the box makes youCheckedIt false.
2. If you set $scope.youCheckedIt to true in SomeController, the box becomes
checked in the UI. Setting it to false unchecks the box.
-->
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
</head>

<body>
    <form ng-submit="requestFunding()" ng-controller="FormController">
        <input type="checkbox" ng-model="youCheckedIt" /> {{youCheckedIt}}
        <br>

        Starting: <input ng-change="computeNeeded()" ng-model="funding.startingEstimate" />
        Recommendation: {{funding.needed}}
        <button>Fund my startup!</button>
        <button ng-click="reset()">Reset</button>
    </form>

    <script>
        var myAppModule = angular.module("myApp", []);
        myAppModule.controller("FormController", function($scope) {
            $scope.youCheckedIt = true;

            $scope.funding = {startingEstimate: 0, needed: 0};
            /*
            $scope.computeNeeded = function() {
                $scope.funding.needed = $scope.funding.startingEstimate * 10;
            };
            */

            computeNeeded = function() {
                $scope.funding.needed = $scope.funding.startingEstimate * 10;
            };
            $scope.$watch('funding.startingEstimate', computeNeeded);

            $scope.requestFunding = function() {
                window.alert("Sorry, please get more customers first.");
            }

            $scope.reset = function() {
                $scope.funding.startingEstimate = 0;
            }
        });
    </script>
</body>

</html>
