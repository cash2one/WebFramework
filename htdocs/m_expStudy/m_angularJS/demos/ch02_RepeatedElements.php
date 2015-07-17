<html ng-app>
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
</head>

<body>
    <div ng-controller='StudentListController'>
        <ul>
            <li ng-repeat='student in students'>
                <a href='/student/view/{{student.id}}'>{{student.name}}</a>
            </li>
        </ul>

        <table>
            <tr ng-repeat='student in students'>
                <td>{{$index + 1}}</td>
                <td>{{student.name}}</td>
                <td>{{student.id}}</td>
            </tr> 
        </table>
        <button ng-click="insertTom()">Insert</button>
    </div>

    <script>
    var students = [
        {name:'Mary Contrary', id:'1'},
        {name:'Jack Sprat', id:'2'},
        {name:'Jill Hill', id:'3'}
    ];

    function StudentListController($scope) {
        $scope.students = students;

        $scope.insertTom = function() {
            $scope.students.splice(1, 0, {name: 'Tom Thumb', id: '4'});
        }
    }
    </script>
</body>

</html>
