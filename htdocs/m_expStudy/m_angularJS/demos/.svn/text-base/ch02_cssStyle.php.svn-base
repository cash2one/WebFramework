<html ng-app>
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
    <style>
    .error {
        background-color: red;
    }
    .warning {
        background-color: yellow;
    }
    .selected {
        background-color: lightgreen;
    }
    </style>
</head>

<body>
    <div ng-controller='HeaderController'>
        <div ng-class='{error: isError, warning: isWarning}'>{{messageText}}</div>
        <button ng-click='showError()'>Simulate Error</button>
        <button ng-click='showWarning()'>Simulate Warning</button>

        <table border='1'>
            <tr ng-repeat='restaurant in directory' ng-click='selectRestaurant($index)' ng-class='{selected: $index==selectedRow}'>
                <td>{{restaurant.name}}</td>
                <td>{{restaurant.cuisine}}</td>
            </tr>
        </table>
    </div>

    <script>
        function HeaderController($scope) {
            $scope.isError = false;
            $scope.isWarning = false;
            $scope.directory = [{name:'The Handsome Heifer', cuisine:'BBQ'},
                                {name:'Green\'s Green Greens', cuisine:'Salads'},
                                {name:'House of Fine Fish', cuisine:'Seafood'}];
    
            $scope.showError = function() {
                $scope.messageText = "This is an error!";
                $scope.isError = true;
                $scope.isWarning = false;
            }

            $scope.showWarning = function() {
                $scope.messageText = "Just a warning. Please carry on.";
                $scope.isWarning = true;
                $scope.isError = false;
            }

            $scope.selectRestaurant = function(row) {
                $scope.selectedRow = row;
            }
        }
    </script>
</body>

</html>
