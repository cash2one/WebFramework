<html ng-app>
<!--
Angular automatically augments <form> elements with several nice features suitable for
single-page applications. One of these nice features is that Angular lets you declare valid
states for inputs within the form and allow submission only when the entire set of
elements is valid.

Notice that we’re using the required attribute and input types for email and number
from HTML5 to do our validation on some of the fields. This works great with Angular,
and in older non-HTML5 browsers, Angular will polyfill these with directives that perform
the same jobs.

Inside the controller, we can access the validation state of the form through a property
called $valid. Angular will set this to true when all the inputs in the form are valid. We
can use this $valid property to do nifty things such as disabling the Submit button
when the form isn’t completed yet.
-->
<head>
    <meta charset="utf-8"/>
    <script src="../../../js-base/angularJS/angular.min.js"></script>
</head>

<body>
    <h1>Sign Up</h1>
    <form name='addUserForm' ng-controller="AddUserController">
        <div ng-show='message'>{{message}}</div>
        <div>First name: <input ng-model='user.first' required></div>
        <div>Last name:  <input ng-model='user.last' required></div>
        <div>Email:      <input type='email' ng-model='user.email' required></div>
        <div>Age:        <input type='number' ng-model='user.age' ng-maxlength='3' ng-minlength='1'></div>
        <div><button ng-click='addUser()' ng-disabled='!addUserForm.$valid'>Submit</button></div>
    </form>
</body>

<script>
    function AddUserController($scope) {
        $scope.message = '';      
        
        $scope.addUser = function() {
            $scope.message = "Thanks, " + $scope.user.first + ", we added you!";
        }
    }
</script>

</html>
