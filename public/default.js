
(function () {
    
    WinJS.UI.processAll().then(function () {
      
      var socket, serverGame;
      var username, playerColor;
      var game, board;
      var usersOnline = [];
      var myGames = [];
      socket = io();
           
      //////////////////////////////
      // Socket.io handlers
      ////////////////////////////// 
      
      socket.on('login', function(msg) {
            usersOnline = msg.users;
            updateUserList();
            
            myGames = msg.games;
            updateGamesList();
      });
      
      socket.on('joinlobby', function (msg) {
        addUser(msg);
      });
      
       socket.on('leavelobby', function (msg) {
        removeUser(msg);
      });
      
      socket.on('gameadd', function(msg) {
      });
      
      socket.on('resign', function(msg) {
            if (msg.gameId == serverGame.id) {

              socket.emit('login', username);

              $('#page-lobby').show();
              $('#page-game').hide();
            }            
      });
        
      var saveGameSession = "";
      socket.on('joingame', function(msg) {
        saveGameSession = msg;
        console.log("joined as game id: " + msg.game.id );
        playerColor = msg.color;
        if (msg.color == "black")
            displayUpdate("You are being challenged!", "Would you like to accept?", true);
        else
            displayUpdate("Challenge sent", "Response pending... If you are rejected you will return to the lobby", false);
      });
        
      socket.on('gameAccepted', function (msg) {
          alert('the game accepted function executes');
          $("#lightbox").hide();
          initGame(saveGameSession.game);
        
        $('#page-lobby').hide();
        $('#page-game').show();
        $('#returnButton').hide();
      });
        
      socket.on('move', function (msg) {
        if (serverGame && msg.gameId === serverGame.id) {
           game.move(msg.move);
           board.position(game.fen());
           if (game.game_over() && game.in_checkmate) {
                result = "lose";
                displayMessage("Checkmate! You lose");
            }
           else if (game.game_over() && game.in_draw()) {
                result = "draw";
                displayMessage('Draw!');
            }
           else if (game.game_over() && game.in_stalemate()) {
                result = "draw";
                displayMessage('Stalemate! It\'s a draw');
            }
        }
      });
     
      
      socket.on('logout', function (msg) {
        removeUser(msg.username);
      });
      

      
      //////////////////////////////
      // Menus
      ////////////////////////////// 
      $('#login').on('click', function() {
        username = $('#username').val();
        
        if (username.length > 0) {
            $('#userLabel').text(username);
            socket.emit('login', username);
            
            $('#page-login').hide();
            $('#page-lobby').show();
        } 
      });
      
      $('#game-back').on('click', function() {
        socket.emit('login', username);
        
        $('#page-game').hide();
        $('#page-lobby').show();
      });
      
      $('#game-resign').on('click', function() {
        socket.emit('resign', {userId: username, gameId: serverGame.id});
        
        socket.emit('login', username);
        $('#page-game').hide();
        $('#page-lobby').show();
      });
      
      var addUser = function(userId) {
        usersOnline.push(userId);
        updateUserList();
      };
    
     var removeUser = function(userId) {
          for (var i=0; i<usersOnline.length; i++) {
            if (usersOnline[i] === userId) {
                usersOnline.splice(i, 1);
            }
         }
         
         updateUserList();
      };
      
      var updateGamesList = function() {
        document.getElementById('gamesList').innerHTML = '';
        myGames.forEach(function(game) {
          $('#gamesList').append($('<button>')
                        .text('#'+ game)
                        .on('click', function() {
                          socket.emit('resumegame',  game);
                        }));
        });
        if (document.getElementById('gamesList').innerHTML == '') {
            document.getElementById('gamesList').innerHTML = 'No active games';
        }
      };
      
      var updateUserList = function() {
        document.getElementById('userList').innerHTML = '';
        usersOnline.forEach(function(user) {
          $('#userList').append($('<button>')
                        .text(user)
                        .on('click', function() {
                          socket.emit('invite',  user);
                        }));
        });
        if (document.getElementById('userList').innerHTML == '') {
            document.getElementById('userList').innerHTML = 'No users online';
        }
      };
           
      //////////////////////////////
      // Chess Game
      ////////////////////////////// 
      
      var initGame = function (serverGameState) {
        serverGame = serverGameState; 
        
          var cfg = {
            draggable: true,
            showNotation: false,
            orientation: playerColor,
            position: serverGame.board ? serverGame.board : 'start',
            onDragStart: onDragStart,
            onDrop: onDrop,
            onMouseoutSquare: onMouseoutSquare,
            onMouseoverSquare: onMouseoverSquare,
            onSnapEnd: onSnapEnd
          };
               
          game = serverGame.board ? new Chess(serverGame.board) : new Chess();
          board = new ChessBoard('game-board', cfg);
      }
       
      
    var removeGreySquares = function() {
     $('#game-board .square-55d63').css('background', '');
    };

    var greySquare = function(square) {
    var squareEl = $('#game-board .square-' + square);
  
    var background = '#a9a9a9';
    if (squareEl.hasClass('black-3c85d') === true) {
    background = '#696969';
    }

    squareEl.css('background', background);
    };
      
      // do not pick up pieces if the game is over
      // only pick up pieces for the side to move
      var onDragStart = function(source, piece, position, orientation) {
        if (game.game_over() === true ||
            (game.turn() === 'w' && piece.search(/^b/) !== -1) ||
            (game.turn() === 'b' && piece.search(/^w/) !== -1) ||
            (game.turn() !== playerColor[0])) {
            return false;
            }
      };  
      
    var result = "";
        
    var displayMessage = function (message) {
        $("#lightbox").css("display", "block");
        $("#messageHeader").text(message);
        $("#messageBody").empty();
        $("#messageBody").append("<p>Your statistics have been updated</p>");
        $("#button1").text("Return to lobby");
    };
        
    var displayUpdate = function (header, message, isYesNoButton) {
        $("#lightbox").css("display", "block");
        $("#messageHeader").text(header);
        $("#messageBody").empty();
        $("#messageBody").append("<p>" + message + "</p>");
        if (!isYesNoButton) {
            $("#button1").hide();
            $("#button2").hide();
            $("#button3").hide();
        }   
    }
    
        
    $('#button2').on('click', function() {
        socket.emit('gameAccepted', "message");
      });
    
    $('#button3').on('click', function() {
          $("#lightbox").hide();
          socket.emit('resign', {userId: username, gameId: serverGame.id});
      });
        
   $('#button1').click(function() {
    if ( result == "") {
        return;
    }
     $.ajax({
     type: "POST",
     url: "pvp.php",
    data: { 'result': result }
    }).done(function( msg ) {
         socket.emit('resign', {userId: username, gameId: serverGame.id});
        
        socket.emit('login', username);
        $('#page-game').hide();
        $('#page-lobby').show();
        $("#lightbox").css("display", "none");
    });    
    });
        
      var onDrop = function(source, target) {
          removeGreySquares();
          
        // see if the move is legal
        var move = game.move({
          from: source,
          to: target,
          promotion: 'q' // NOTE: always promote to a queen for example simplicity
        });
      
        // illegal move
        if (move === null) { 
          return 'snapback';
        } else {
           socket.emit('move', {move: move, gameId: serverGame.id, board: game.fen()});
           if (game.game_over() && game.in_checkmate) {
                result = "win";
                displayMessage("Checkmate! You win");
            }
             else if (game.game_over() && game.in_draw()) {
                result = "draw";
                displayMessage('Draw!');
            }
            else if (game.game_over() && game.in_stalemate()) {
                result = "draw";
                displayMessage('Stalemate! It\'s a draw');
            }
        }
      
      };
      
      
var onMouseoverSquare = function(square, piece) {
  // get list of possible moves for this square
  var moves = game.moves({
    square: square,
    verbose: true
  });

  // exit if there are no moves available for this square
  if (moves.length === 0) return;

  // highlight the square they moused over
  greySquare(square);

  // highlight the possible squares for this piece
  for (var i = 0; i < moves.length; i++) {
    greySquare(moves[i].to);
  }
};
        
   var onMouseoutSquare = function(square, piece) {
  removeGreySquares();
};
        
      // update the board position after the piece snap 
      // for castling, en passant, pawn promotion
      var onSnapEnd = function() {
        board.position(game.fen());
      };
    });
})();

