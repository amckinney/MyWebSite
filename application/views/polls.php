<?php
/**
 * @file polls.php
 * @author Alex McKinney
 * @date 5 June 2015
 * @brief This file simply serves up the original angular frontpage
 */
echo doctype('html5');
?>


<html lang="en" ng-app="pollsApp">
<head>
  <meta charset="utf-8">
  <title>Polls</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <?php
  $links = array (
    "angularjs/scripts/angular.js",
    "angularjs/scripts/angular-route.js",
    "angularjs/js/app.js",
    "angularjs/js/controllers/pollsControllers.js" #TODO: Come up with a way to fancy include every subdirectory
    );
  $scripts = "";
  foreach ($links as $value) {
      $scripts.= '<script src="';
      $scripts.= base_url($value);
      $scripts.= '"></script>';
      $scripts.= "\n";
  }
  echo $scripts;
  ?>

</head>
<body>

   <?php echo include __DIR__ . "/../includes/header.html"; ?>
   <div ng-view></div>

</body>
</html>
