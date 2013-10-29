(function (angular){

    var booksLibraryApp = angular.module('booksLibraryApp', []);


    function configurarRutas($routeProvider) {
        $routeProvider.
            when('/list', {
                templateUrl: 'views/list.html',
                controller: 'booksListController'
            }).
            when('/edit/:id', {
                templateUrl: 'views/edit.html',
                controller: 'editBookController'
            }).
            when('/new', {
                templateUrl: 'views/edit.html',
                controller: 'newBookController'
            }).
            otherwise({
                redirectTo: '/list'
            });
    }

    booksLibraryApp.config(['$routeProvider', configurarRutas]);

    booksLibraryApp.factory('bookService', ['$http', bookService]);

    booksLibraryApp.controller('booksListController', ['$scope', '$window', '$location', 'bookService', booksListController]);
    booksLibraryApp.controller('editBookController',  ['$scope', '$location', '$routeParams', 'bookService', editBookController]);
    booksLibraryApp.controller('newBookController',   ['$scope', '$location', 'bookService', newBookController]);


    function bookService($http) {

        var booksApiUrl = 'http://localhost/bookslibrary/api/index.php/books';
        
        return {
            getAll: function() {
                return $http.get(booksApiUrl);
            },
            get: function (id) {
                return $http.get(booksApiUrl + '/' + id);
            },
            add: function (book) {
                return $http.post(booksApiUrl, book);
            },
            remove: function(book) {
                return $http.delete(booksApiUrl + '/' + book.id);
            },
            update: function (book) {
                return $http.put(booksApiUrl, book);
            }
        };
    }

    function booksListController($scope, $window, $location, bookService) {

        $scope.books = $scope.books || [];

        bookService.getAll().success(function (books) {
            
            for (var i = 0; i < books.length; i++) {
                $scope.books.push(books[i]);
            }

        });

        $scope.eliminar = function (book) {
            
            if (!$window.confirm('Are you sure?')) {
                return;
            }
            
            bookService.remove(book).success(function () {
                var index = $scope.books.indexOf(book);
                if (index >= 0) {
                    $scope.books.splice(index, 1);
                }
            });

        };

        $scope.edit = function(book) {
             $location.path('/edit/' + book.id);
        }
        
    }

    function editBookController($scope, $location, $routeParams, bookService) {


        bookService.get($routeParams.id).success(function (book) {
        
            $scope.id = book.id;
            $scope.title = book.title;
            $scope.author = book.author;
            $scope.description = book.description;
            $scope.year = book.year;

        });

        $scope.save = function () {

            var book = {
                id : $scope.id,
                title : $scope.title,
                author : $scope.author,
                description : $scope.description,
                year : $scope.year
            };
            
            bookService.update(book).success(function () {
                $location.path('/list');
            });        
            
        };
        
    }

    function newBookController($scope, $location, bookService) {
        
        $scope.id = null;
        $scope.title = null;
        $scope.author = null;
        $scope.desciption = null;
        $scope.year = (new Date()).getFullYear();
        
        $scope.save = function () {
            
            var book = {
                id: $scope.id,
                title : $scope.title,
                author : $scope.author,
                description : $scope.description,
                year : $scope.year
            };
            
            bookService.add(book).success(function (book) {
                $location.path('/list');
            });
        };
        
    }

})(angular);
