<!DOCTYPE html>
<html lang="en">
<head>
  <title>Urban Web</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="codigo.js"></script>
  <style>
    .button {
      border: none;
      color: white;
      padding: 15px 32px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 4px 2px;
      cursor: pointer;
    }

    .button1 {background-color: #4CAF50;} /* Green */
    .button2 {background-color: #008CBA;} /* Blue */
    .flex-container {
	  display: flex;
	  align-items: center;
      justify-content: center;
	}

	.flex-container > div {
	  width: 100px;
	  margin: 10px;
	  text-align: center;
	  line-height: 75px;
	  font-size: 30px;
	}
  </style>
</head>
<body style="background-color:#f8f9fa">
  <div class="jumbotron text-center">
    <h1>Urban Web</h1>
    <p>Explore cidades do mundo.</p>
    <div class="row" id="row">
    
    </div>
  </div>
  <script>
    //window.onload = lerCidades;
  </script>
</body>
</html>