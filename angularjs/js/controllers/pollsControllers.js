(function () {
    'use strict';

    /* Controllers */

    // Global "database"

    var pollsControllers = angular.module('pollsControllers', []);

    pollsControllers.controller('PollCreateCtrl', ['$scope', '$http', '$routeParams',
        function($scope, $http, $routeParams) {
          $scope.message = '';
          $scope.finished = false;
          $scope.title = '';
          $scope.question = '';
          $scope.answerFields = [{'description':''}, {'description':''}];

          $scope.checkReady = function() {
            var state = true;
            if ($scope.answerFields.length == 0) {
              state = false;
            } else {
              for (var i = 0; i < $scope.answerFields.length; i += 1) {
                if ($scope.answerFields[i].description == '') {
                  state = false;
                }
              }
            }
            return (state && $scope.title != '' && $scope.question != '');
          };

          $scope.addAnswer = function() {
            $scope.answerFields.push({'description':''});
          };

          $scope.removeAnswer = function() {
            var lastItem = $scope.answerFields.length-1;
            $scope.answerFields.splice(lastItem);
          };

          $scope.displayMessage = function() {
            $scope.message = "This function has not yet been implemented! Please come back later :)";
          }

          $scope.createPoll = function () {
            var url = "http://csse-studweb3.canterbury.ac.nz/~amc359/365/polls/index.php/services/polls";
            var pollData = {'title':$scope.title, 'question':$scope.question};
            var pollId = '';
            var poll = '';
            $http.post(url, pollData)
              .success(function(response) {
                console.log("New poll created!");
              })
              .error(function() {
                console.log("Error in adding new poll to 'Polls' table...");
              });
            $http.get(url)
              .success(function(response) {
                for (var i = 0; i < response.length; i += 1) {
                  poll = response[i];
                  if (poll['title'] == $scope.title && poll['question'] == $scope.question) {
                    pollId = poll['id'];
                    break;
                  }
                }
              })
              .error(function() {
                console.log("Error in finding newly created poll...");
              });
            url = "http://csse-studweb3.canterbury.ac.nz/~amc359/365/polls/index.php/services/polls/create"
            answerData = {'pollId': pollId, 'answers':$scope.answerFields};
            $http.post(url, answerData)
              .success(function(response) {
                $scope.finished = true;
                console.log("Successfully created the Poll!");
              })
              .error(function() {
                console.log("Error in adding data to 'Answers' table...");
              });
          };

        }]);

    pollsControllers.controller('AboutCtrl', ['$scope',
        function ($scope) {
          $scope.author = 'Alex McKinney';
          $scope.studId = '24993165';
          $scope.email = 'amckinney@berkeley.edu';
        }]);

    pollsControllers.controller('VoteDetailCtrl', ['$scope', '$http', '$routeParams',
      function($scope, $http, $routeParams) {
        var pollId = $routeParams.pollId;
        var url = "http://csse-studweb3.canterbury.ac.nz/~amc359/365/polls/index.php/services/polls/" +
            pollId;
        $http.get(url)
          .success(function(response) {
            var answerList = [];
            var answer = {};
            for (var i = 0; i < response.answers.length; i += 1) {
              answer = {}
              answer['id'] = response.answerIds[i];
              answer['votes'] = response.votes[i];
              answer['description'] = response.answers[i];
              answerList.push(answer);
            }
            $scope.answers = answerList;
            $scope.poll = {'name': response.title};
          })
          .error(function() {
            console.log("Failure when fetching answer data...");
          });
      }]);

    pollsControllers.controller('PollListCtrl', ['$scope', '$http',
        function ($scope, $http) {
            var pollUrl = "http://csse-studweb3.canterbury.ac.nz/~amc359/365/polls/index.php/services/polls";
            $http.get(pollUrl)
              .success(function(response) {
                $scope.polls = response;
              })
              .error(function() {
                console.log("Error in fetching polls...");
              })
            $scope.author = 'Alex McKinney';
            $scope.studId = '24993165';
        }]);

    pollsControllers.controller('PollDetailCtrl', ['$scope', '$http', '$routeParams',
      function($scope, $http, $routeParams) {
          $scope.answers = [];
          var pollId = $routeParams.pollId;
          var url = "http://csse-studweb3.canterbury.ac.nz/~amc359/365/polls/index.php/services/polls/" +
                pollId;
          $http.get(url)
            .success(function(response) {
              for (var i = 0; i < response.answers.length; i += 1) {
                var answer = {};
                answer['id'] = response.answerIds[i];
                answer['description'] = response.answers[i];
                $scope.answers.push(answer);
              }
              $scope.name = response.title;
              $scope.question = response.question;
            })
            .error(function() {
              console.log("Error in getting Poll details...");
            });

          $scope.vote = {
            choice: 'none'
          };
          $scope.submitted = false;
          $scope.reset = false;

          $scope.addVote = function() {
            $scope.reset = false;
            $scope.submitted = true;
            var answerId = $scope.vote.choice;
            var postUrl = "http://csse-studweb3.canterbury.ac.nz/~amc359/365/polls/index.php/services/votes/" +
                  pollId + "/" + answerId;
            $http.post(postUrl)
              .success(function(response) {
                console.log("Vote successful!");
              })
              .error(function() {
                console.log("Failed to vote...");
              });
            $scope.vote.choice = 'none';
          };

          $scope.resetVotes = function() {
            var deleteUrl = "http://csse-studweb3.canterbury.ac.nz/~amc359/365/polls/index.php/services/votes/" +
                  pollId;
            $http.delete(deleteUrl)
              .success(function() {
                $scope.reset = true;
              })
              .error(function() {
                console.log("Error in reseting votes...");
              });
          };
      }]);
  }())
