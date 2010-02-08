<?php

class GameController extends Controller
{
	// -----------------------------------------------------------
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/

	public function actionQuickie()
	{
		$this->render('quickie');
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionGetNewQuickieRound()
	{
		$round = Yii::app()->request->getParam( 'ugss_round' );
		if( $round )
		{
			unset( $_POST[ 'ugss_round' ] );
		
			// alright at this point $_POST should be an array with more than 0 
			// players in it ...
			if( sizeof( $_POST ) )
			{
				$pair1 		= $pair2 = NULL;
				$score1 	= $score2 = 0;
				$winners 	= array();

				// we grab the names in pairs with the scores and figure out who won  
				// and simply return the winners
				foreach( $_POST as $name => $score)
				{
					if( ! $pair1 )
					{
						$pair1 	= $name;
						$score1 = $score;
					}
					else
					{
						$pair2 	= $name;
						$score2 = $score;
					}

					// let's see who won
					if( $pair1 && $pair2 )
					{
						// if the game is a tie, the first player wins ,,,
						$winner = new stdClass();
						if( $score2 > $score1 )
						{
							$winner->name = $pair2;
							$winners[] = $winner;
						}
						else
						{
							$winner->name = $pair1;
							$winners[] = $winner;
						}

						// we zero everything out and start from scratch ...
						$pair1 		= $pair2 = NULL;
						$score1 	= $score2 = 0;
					}
				}

				// if there is a player left, it's an automatic winner
				if( $pair1 )
				{
					$winner = new stdClass();
					$winner->name = $pair1;
					$winners[] = $winner;
				}
	
				// apprarently you have to wrap the result in (); to pass it
				// as a JavaScript JSON ---
				header( 'Content-type: application/json' );
				die( '(' . json_encode( $winners ) . ');' );
			}
		}
		die( 'err' );
	}
}
