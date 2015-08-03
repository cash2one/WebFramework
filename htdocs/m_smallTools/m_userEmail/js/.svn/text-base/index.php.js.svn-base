var mail = angular.module('MailList', ["ngResource"])

mail.factory("Users", function($resource) {
    return $resource("output_data/output.json", {}, {
        query: {
            method: "GET",
            isArray: true
        }
    });
});

mail.factory("Group", function($resource) {
    return $resource("php/read_group.php", {}, {
        query: {
            method: "GET",
            isArray: true
        }
    });
});

function UsersCtrl($scope, $timeout, Users, Group) {
    $scope.usersInput = "";
    $scope.usersInput2 = "";
    $scope.users = Users.query();
    $scope.groups = Group.query();
    $scope.link = "mailto:";

    $scope.myFilter = function(user) {
        return user.to == true || user.cc == true;
    }

    $scope.myFilterUserInput = function(user) {
        if ($scope.usersInput2 == "") return true;
       
        var input_words = $scope.usersInput2.split(/[,\s]+/g);
        for (var i = 0; i < input_words.length; i++) {
            var found = true;
            var inputWord = input_words[i];
            var letters = inputWord.split("");

            var idx = 0;
            for (var j = 0; j < letters.length; j++) {
                idx = user.ldap.indexOf(letters[j], idx);
                if (idx == -1) {
                    found = false;
                    break;
                }
            }

            if (found == false) {
                found = true;
                var ilist = inputWord.split("");
                inputWord = ilist.sort().join("")
                
                idx = user.pname.indexOf(inputWord);
                if (idx == -1) {
                    found = false;
                }
            }

            if (found == true)
                break;
        }

        return found;
    }

    $scope.buildLink = function() {
        var to = "";
        var cc = "";
        for (var i = 0; i < $scope.users.length; i++) {
            var user = $scope.users[i];
            if (user.to == true) {
                to += user.ldap + "@rd.xxx.com;";
            } 
            if (user.cc == true) {
                cc += user.ldap + "@rd.xxx.com;";
            }
        }

        $scope.link = "mailto:" + to + "&cc=" + cc;
    }
    
    var timeout;
    $scope.$watch('usersInput', function(newVal) {
        if (timeout) $timeout.cancel(timeout);
        timeout = $timeout(function() {$scope.usersInput2 = $scope.usersInput}, 350);
    });
}
