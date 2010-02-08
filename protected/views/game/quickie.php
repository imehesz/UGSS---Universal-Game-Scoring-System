<?php
$this->breadcrumbs=array(
	'Game'=>array('game/index'),
	'Quickie',
);?>
<h1>Thanks for playing a quickie<?php // echo $this->id . '/' . $this->action->id; ?></h1>

<style type="text/css">
	.simple-button{
		margin-bottom:5px;
		width:100px;
		border:1px solid #aaa;
		text-align:center;
		float:left;
		margin-right:5px;
	}

	.simple-button:hover
	{
		background-color: #aaa;
		color: #fff;
	}

	.gameboard
	{
		background-color: #fff7c5;
		border: 1px solid #fdbd59;
		padding: 10px;
		color: #fdbd59;
		display: none; 
	}

	.gameboard-title
	{
		text-align: center;
		font-size: 18px;
		font-weight: bolder;
	}

  	.player-panel{
		background-color: #fff;
		border: 1px solid #aaa;
		padding: 5px;
		font-size: 9px;
		color: #000;
		width:150px;
		/* margin-bottom:3px; */
    }

	.round
	{
		/* background-color: #fff; */
		width: 175px;
		float:left;
		vertical-align: middle;
	}

	input
	{
		border: 1px solid #aaa;
	}
	
</style>
<div class="form">
	<form> <?php /* form fo show */ ?>
		<div id="registration_panel">
			<input type="text" name="name" id="name" value="" size="25"> <span id="err" style="color:#f00;"></span>
			<div>
				<div onclick="addPlayer();" class="simple-button">Add player</div>
			 	<div id="starter" style="display:none;" onclick="startGame();" class="simple-button">Start Game</div>
				<div style="clear:both;"></div>
			</div>
		</div>
		<div id="round_manager" style="display:none;"> 
			<div class="simple-button" onclick="addNewRound();">
			New round
			</div>
			<div class="simple-button" onclick="startNewGame();">
			New game
			</div>
		</div>
	</form>
	<div style="clear:both;"></div>
</div>
<div class="gameboard" id="the_gameboard">
	<div class="gameboard-title">B O A R D</div>
	<div class="round" id="round_1"></div>
</div>
</div>
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<script language="javascript">
	var playerCnt 	= 0;
	var curr_round	= 1;
	var players 	= new Array();

	var addPlayer = function()
	{
		name = $('#name').val();
		if( ! name )
		{
			setErr( 'Please enter a name, dude' );
			return false;
		}

		errOff();
		playerCnt++;

		if( playerCnt > 0 )
		{
			$('#starter').show();
			$('#the_gameboard').fadeIn('slow');
		}

		// players.push( name );
		//$( '#round_1' ).append('<div class="player-panel">' + name + ' <input title="score" name="score_'+playerCnt+'" id="score_'+playerCnt+'" type="text" size="2" value="0"></div>');
		addPlayerToRound( name, 1 );
		$('#name').val( '' );

		// increasing the size of the board ...
		$('#the_gameboard').css({'height':$('#round_1').height()+20});
	}

	var addPlayerToRound = function( name, round )
	{
		players.push( name );
		// we have to have a unique ID for the players in
		// every round so we can call their score trough the IDs ...
		player_id = 'score_' + round + '_' + playerCnt;
		if( round == 1 )
		{
			 height = 5;	
		}
		else
		{
			 height = 5;

			 if( players.length == 1 )
			 {
			 	// height = height + 10;
			 }
			 else
			 {
			 	// height = height + 25;
			 }
		}
		$( '#round_'+round ).append( '<div class="player-panel" style="margin-top:'+ height +'px;">' + name + ' <input title="score" name="'+player_id+'" id="'+player_id+'" type="text" size="2" value="0"></div>' );
	}

	var setErr = function( msg )
	{
		$( '#err' ).html( msg );
		$( '#err' ).fadeIn('slow');
	}

	var startGame = function()
	{
		$('#registration_panel').hide();
		$('#round_manager').show();
	}

	var errOff = function()
	{
		$('#err').fadeOut('slow');
	}

	var addNewRound = function()
	{
		var answer = confirm("Are you sure you wanna start the new round?")
		if (answer){
			// we have to make sure that all the rounds are the same
			<?php /* 
				CHtml::ajax( 
							array('url' => $this->createUrl(
												'game/getnewround', 
												array('round' => "'+curr_round+'" ) 
									) 
							) 
				); */ ?>
				var data = '';

				// okay, it's pretty getthow to start a for loop with 1 
				// but there is no such a thing as a 0th round ;)
				for(var i=1; i<=players.length;i++ )
				{
					data = data+players[ i-1 ] + '=' + $('#score_' + curr_round + '_' + i).val() + '&';
				}

				if( data )
				{
					data = data+ 'ugss_round=' + curr_round;
				}

				$.ajax({ 
						type: "POST",
						url: "<?= $this -> createUrl( 'game/getnewquickieround' ) ?>", 
						data: data, 
						success:	function( msg ){
							if( msg != 'err' )
							{
								// drawing the new round
								curr_round++;
								$( '#the_gameboard' ).append('<div class="round" id="round_'+curr_round+'"></div>');
								setSize( 'round_'+curr_round );

								// reading the returned AJAX stuff
								var myobj = eval( msg );
								playerCnt 	= 0;
								players 	= new Array();
								for(var i=0;i<myobj.length;i++)
								{
									playerCnt++; //i think it stupid, have to revise it later
									addPlayerToRound( myobj[i].name, curr_round );
								}
							}
				}});
		}
	}

	var setSize = function( roundID )
	{
		$('#'+roundID).css({'min-height':$('#round_1').height()});
	}

	var startNewGame = function()
	{
		var answer = confirm("Are you sure you wanna restart the game?")
		if (answer){
			window.location = "";
		}
		return false;
	}

	$(document).ready( function(){
		// my jQuery goodies are coming here ...
	});
</script>
