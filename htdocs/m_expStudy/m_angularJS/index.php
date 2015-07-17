<html>
<head>
    <meta charset="utf-8"/>
    <script src="../../js-base/angularJS/angular.min.js"></script>
    <script src="../../js-base/angularJS/jquery.min.js"></script>

    <style>
        ul li {margin-bottom: 5px}
        a {text-decoration: none}
        a:hover {text-decoration: underline}
    </style>
</head>

<body>
<h4>AngularJS 学习</h4>
<ul>
<?php
    $filenames = glob("./demos/*.php");
    foreach ($filenames as $filename) {
        echo "<li><a target='_blank' href='$filename'>" . basename($filename) . "</a></li>";
    }
?>
</ul>
</body>

</html>
