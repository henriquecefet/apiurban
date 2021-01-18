function lerCidades(){
  		  var settings = {
              "url": "https://urbanweb.herokuapp.com/androidlercidade.php",
               "method": "GET",
               "timeout": 0,
               "processData": false,
               "mimeType": "multipart/form-data",
               "contentType": false,
         
          };
  		    $.ajax(settings).done(function (response) {
                console.log(response);
                var jx = JSON.parse(response);
                for(let i = 0; i<jx.cidades.length; i++){
                	 let pai = adicionarDiv("row", "pai"+jx.cidades[i].nome, "card mb-4 shadow-sm");
                	 let id = adicionarDiv(pai, jx.cidades[i].nome, "card-body");
                	 adicionarImagem(id,jx.cidades[i].imagem, "200px", "350px");
        			     adicionarParagrafo(id, jx.cidades[i].nome);
                   let divpai = document.getElementById(pai);
                   divpai.onclick = function(){
                      localStorage.setItem("cidade",jx.cidades[i].nome);
                      window.location.href = "listarecomendacoes.php";
                  };
                }
            });
  	}
  function lerHotspots(){
    let cidade = localStorage.getItem("cidade");
    let link = "https://apiurban.herokuapp.com/api.php?funcao=recomendarCidadeClimaCovid&cidade="+cidade;
    var settings = {
              "url": link,
               "method": "GET",
               "timeout": 0,
               "processData": false,
               "mimeType": "multipart/form-data",
               "contentType": false,
         
          };
    $.ajax(settings).done(function (response) {
              console.log(response);
               var jx = JSON.parse(response);
               for(let i = 0; i<jx.hotspot.length; i++){
                   let pai = adicionarDiv("row", "pai"+jx.hotspot[i].nome, "card mb-4 shadow-sm");
                   let id = adicionarDiv(pai, jx.hotspot[i].nome, "card-body");
                   adicionarImagem(id,jx.hotspot[i].imagem, "200px", "350px");
                   adicionarParagrafo(id, jx.hotspot[i].nome);
                   for(let j = 0; j<jx.hotspot[i].recomendacaoFutura.length; j++){
                      adicionarParagrafo(id, jx.hotspot[i].recomendacaoFutura[j]);
                   }
                   divpai.onclick = function(){
                      navigator.geolocation.getCurrentPosition(showPosition,jx.hotspot[i].latitude, jx.hotspot[i].longitude);
                  };
                  
              }
            });
  }
  function showPosition(position, latitude, longitude) {
    window.location.href = "https://www.google.com.br/maps/dir/"+position.coords.latitude+","+position.coords.longitude+"/"+latitude+","+longitude;
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