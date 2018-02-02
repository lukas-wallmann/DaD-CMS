<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>newCMS</title>
</head>

<body>
	<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="index.php">newCMS</a>
      <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsDefault" aria-controls="navbarsDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="navbar-collapse collapse" id="navbarsDefault" style="">
        <ul class="navbar-nav mr-auto">

          <?php
						writeNav();
          ?>
        </ul>
      </div>
    </nav>
    <main role="main" class="container-fluid">
			<h1><?php echo $_currentNavPoint ?></h1>
