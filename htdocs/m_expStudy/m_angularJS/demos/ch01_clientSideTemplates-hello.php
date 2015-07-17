<html ng-app>
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
</head>

<body>
    <div ng-controller='HelloController'>
        <p>{{greeting.text}}, World</p>
    </div>

    <script>
        function HelloController($scope) {
            $scope.greeting = {text: "Hello" };
        }
    </script>
</body>

</html>
