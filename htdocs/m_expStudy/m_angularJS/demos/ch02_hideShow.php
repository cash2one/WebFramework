<html ng-app>
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
    <style>
        .menu-disabled-true {
            color: gray;
        }
    </style>
</head>

<body>
    <div ng-controller='DeathrayMenuController'>
        <button ng-click='toggleMenu()'>Toggle Menu</button>
        <ul ng-show='menuState.show'>
            <!-- This technique works equally well when combining inline styles with interpolation, such as with style="{{some expression}}". -->
            <!-- Angular provides the ng-class and ng-style directives. -->
            <li class='menu-disabled-{{isDisabled}}' ng-click='stun()'>Stun</li>
            <li ng-click='disintegrate()'>Disintegrate</li>
            <li ng-click='erase()'>Erase from history</li>
        </ul>
    </div>

    <script>
        function DeathrayMenuController($scope) {
            // $scope.menuState.show = false; //不能直接这样，需要先初始化
            $scope.menuState = {show: true};
            $scope.isDisabled = false;

            $scope.toggleMenu = function() {
                $scope.menuState.show = !$scope.menuState.show;
            }
            $scope.stun = function() {
                $scope.isDisabled = true;
            }
        }
    </script>
</body>

</html>
