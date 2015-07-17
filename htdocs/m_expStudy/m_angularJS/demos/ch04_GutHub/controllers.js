var app = angular.module('guthub', ['guthub.directives', 'guthub.services', 'ngRoute']);

app.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/', {
        controller: 'ListCtrl',
        resolve: {
            recipes: function(MultiRecipeLoader) {
                return MultiRecipeLoader();
            }
        },
        templateUrl: 'ch04_GutHub/list.html'
    }).when('/edit/:recipeId', {
        controller: 'EditCtrl',
        resolve: {
            recipe: function(RecipeLoader) {
                return RecipeLoader();
            }
        },
        templateUrl:'/ch04_GutHub/recipeForm.html'
    }).when('/view/:recipeId', {
        controller: 'ViewCtrl',
        resolve: {
            recipe: function(RecipeLoader) {
                return RecipeLoader();
            }
        },
        templateUrl:'/ch04_GutHub/viewRecipe.html'
    }).when('/new', {
        controller: 'NewCtrl',
        templateUrl: '/ch04_GutHub/recipeForm.html'
    }).otherwise({redirectTo: '/'});
}]);

app.controller('ListCtrl', ['$scope', 'recipes', function($scope, recipes) {
    $scope.recipes = recipes;
}]);

app.controller('ViewCtrl', ['$scope', '$location', 'recipe', function($scope, $location, recipe) {
    $scope.recipe = recipe;
    
    $scope.edit = function() {
        $location.path('/edit/' + recipe.id);
    };
}]);

app.controller('EditCtrl', ['$scope', '$location', 'recipe', function($scope, $location, recipe) {
    $scope.recipe = recipe;
    
    $scope.save = function() {
        $scope.recipe.$save(function(recipe) {
            $location.path('/view/' + recipe.id);
        });
    };

    $scope.remove = function() {
        delete scope.recipe;
        $location.path('/');
    };
}]);

app.controller('NewCtrl', ['$scope', '$location', 'Recipe', function($scope, $location, Recipe) {
    $scope.recipe = new Recipe({
        ingredients: [ {} ] 
    });

    $scope.save = function() {
        $scope.recipe.$save(function(recipe) {
            $location.path('/view/' + recipe.id);
        });
    };
}]);

app.controller('IngredientsCtrl', ['$scope', function($scope) {
    $scope.addIngredient = function() {
        var ingredients = $scope.recipe.ingredients;
        ingredients[ingredients.length] = {};
    };

    $scope.removeIngredient = function(index) {
        $scope.recipe.ingredients.splice(index, 1);
    };
}]);
