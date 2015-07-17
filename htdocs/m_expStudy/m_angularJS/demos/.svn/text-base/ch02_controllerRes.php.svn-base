<html ng-app>
<head>
    <meta charset="utf-8"/>
</head>

<body>
<pre>
Controllers have three responsibilities in your app:
• Set up the initial state in your application’s modelel
• Expose model and functions to the view (UI template) through $scopee
• Watch other parts of the model for changes and take action

To keep your controllers small and manageable, our recommendation is that you create
one controller per functional area in your view. That is, if you have a menu, create a
MenuController. If you have a navigational breadcrumb, write a BreadcrumbControl
ler, and so on.

If you have complex sections of your UI, you can keep your code simple and maintainable,
by creating nested controllers that can share model and functions through an
inheritance tree. Nesting controllers is simple; you do it by simply assigning a controller
to a DOM element that is inside another one, like so:
    &lt;div ng-controller="ParentController"&gt;
        &lt;div ng-controller="ChildController"&gt;...&lt;/div&gt;
    &lt;/div&gt;
Though we express this as nested controllers, the actual nesting happens in scopes. The
$scope passed to a nested controller prototypically inherits from its parent controller’s
$scope. In this case, this means that the $scope passed to ChildController will have
access to all the properties of the $scope passed to ParentController.
</pre>
</body>

</html>
