<!DOCTYPE html>
<html lang="en">
<head>
  <title>Exemplo de Firebase</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
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
  	function lerCidades(){
  		 $.ajax({
		    url: 'https://urbanweb.herokuapp.com/androidlercidade.php',
		    dataType: 'application/json',
		    complete: function(data){
		        alert(data.status)
		        alert(data.description);
		        // for results you have to iterate because it is an array
		        var len =  data.results.length;
		        for(var i=0;i<len;i++ ){
		            alert(data.results[i]);
		        }
		    },
		    success: function(data){
		        alert(data)
		    }
		})
  	}

    function adicionarParagrafo(pai, texto){
      let paragrafo = document.createElement("P");
      paragrafo.innerHTML = texto;
      document.getElementById(pai).appendChild(paragrafo);
    }
    function adicionarImagem(pai, link, altura, largura){
      let imagem = document.createElement("img");
      imagem.src = link;
      imagem.style.height = altura;
      imagem.style.width = largura;
      document.getElementById(pai).appendChild(imagem);
    }
    function adicionarDiv(pai, id, classe){
      let div = document.createElement("div");
      div.id = id;F=
      div.setAttribute("class", classe);
      document.getElementById(pai).appendChild(div);
      return id;
    }
    function removerElemento(id){
      let elemento = document.getElementById(id);
      if(elemento != null){
        elemento.remove();
      }
    }
    function apagarvariosElementos(pai){
      let vetor = document.getElementById(pai);
      vetor.innerHTML = "";

    }
    function adicionarBotao(pai, classe, titulo, listaParametros){
      let botao = document.createElement("button");
      botao.setAttribute("class", classe);
      botao.addEventListener('click', function(){
        switch(titulo){
          case "Mostrar alunos":
          //mostrarAlunos(pai, listaParametros["universidade"], listaParametros["curso"]);
          break;
          case "Mostrar cursos":
          //mostrarCursos(pai, listaParametros["universidade"]);
          break;
        }
      });
      botao.setAttribute("name", titulo);
      botao.appendChild(document.createTextNode(titulo));
      document.getElementById(pai).appendChild(botao);
    }
    window.onload = lerCidades;
  </script>
</body>
</html>