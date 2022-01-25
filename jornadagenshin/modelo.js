 var perguntas = new Vue({            
            el: '#quizz',             
            data: {
              quizz:[
                 {
                  pergunta: "Quais dos idiomas a seguir o jogo atualmente não apresenta dublagem?",
                  respostas: [
                  "Japonês",
                  "Inglês",
                  "Coreano",
                  "Chinês",
                  "Espanhol"
                  ],
                  correta:"Espanhol"
                },
                {
                  pergunta: "Em que país real Inazuma é baseada?",
                  respostas: [
                  "Brasil",
                  "Japão",
                  "Filipinas",
                  "Coréia do Norte",
                  "Espanha"
                  ],
                  correta:"Japão"
                },
                {
                  pergunta: "A Ordem do Abismo é composta por monstros que eram pessoas de qual país destrído 500 anos atrás?",
                  respostas: [
                  "Dragonspine",
                  "Mondstadt",
                  "Tsurumi",
                  "Khaneri'ah",
                  "Enkanomya"
                  ],
                  correta:"Khaneri'ah"
                },
                {
                pergunta: "Quais são os nomes dos viajantes gêmeos?",
                  respostas: [
                  "Aether e Lumine",
                  "Aether e Kazuha",
                  "Kagamine Len e Kagamine Rin",
                  "Raiden Ei e Raiden Makoto",
                  "Bennett e Fischl"
                  ],
                  correta:"Aether e Lumine"
                },
                {
                pergunta: "Qual é o significado da jornada do viajante?",
                  respostas: [
                  "Descobrir a verdade do mundo",
                  "Fazer amigos",
                  "Não sabemos ainda",
                  "Coletar tesouros",
                  "Se rebelar contra divino"
                  ],
                  correta:"Não sabemos ainda"
                },
                {
                 pergunta: "Qual comissão de Inazuma foi responsável pelo decreto de caça ás visões?",
                  respostas: [
                  "Comissão Yashiro",
                  "Comissão Kanjou",
                  "Comissão Tenryou",
                  "Comissão Qixing",
                  "Comissão de Favonious"
                  ],
                  correta:"Comissão Tenryou"
                },
                 {
                 pergunta: "Qual é o nome da morada dos supostos deuses de Tayvat?",
                  respostas: [
                  "Celestia",
                  "Sangonomya",
                  "Phanes",
                  "Palácio de Jade",
                  "Pavilhão Dourado"
                  ],
                  correta:"Celestia"
                },
                {
                 pergunta: "Os 11 mensageiros dos Fatui são baseados em qual peça teatral?",
                  respostas: [
                  "Fausto",
                  "Commedia dell'Arte",
                  "Cats",
                  "Barbeiro de Servilha",
                  "Hamlet"
                  ],
                  correta:"Commedia dell'Arte"
                }, 
                {
                 pergunta: "Qual outro jogo a Mihoyo lançou além de Genshin Impact?",
                  respostas: [
                  "Pokemon FireRed",
                  "The Legend of Zelda",
                  "Honkai Impact",
                  "Tales of Arise",
                  "Android Become Human"
                  ],
                  correta:"Honkai Impact"
                },
                {
                 pergunta: "Quais desses é um elemento que não existe em Tayvat?",
                  respostas: [
                  "Água",
                  "Ar",
                  "Eletricidade",
                  "Luz",
                  "Gelo"
                  ],
                  correta:"Luz"
                },
                {
                 pergunta: "Obrigado por participar do quizz!",
                  respostas: [
                  " ",
                  " ",
                  " ",
                  " ",
                  " "
                  ],
                  correta:"a"
                }
              ],
              atual: 0,
              acertadas: 0,
              responder: function(correta, resposta){
                if(this.atual<10){
                  if(correta == resposta){
                  alert("Parabéns!");
                  this.acertadas++;
                  }
                  else{
                    alert("Errou!");
                  }
                  this.atual++;
                }
                else{
                  alert("O quizz acabou, obrigado por participar")
                }
              }
                

             
            }
            });