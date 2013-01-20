<?php
if (!defined("__ROOT__"))
    return;

function center($message)
{
    echo "CENTER_MESSAGE ".$message."\n";
}

function con($message)
{
    echo "CONSOLE_MESSAGE ".$message."\n";
}   

function pm($player, $message)
{
    echo "PLAYER_MESSAGE ".$message."\n";
}

//  custom player message - sends a custom message to that selected player (if they exist)
//  $language_command is the langauge string command in language files to load
function cpm($player, $language_command, $params = array())
{
    if (count($params) == 0)
        echo "CUSTOM_PLAYER_MESSAGE ".$player." ".$langauge_command."\n";
    else
    {
        $extras = "";
        foreach($params as $param)
        {
            $extras .= $param." ";
        }
        echo "CUSTOM_PLAYER_MESSAGE ".$player." ".$langauge_command." ".$extras."\n";
    }
}

//  custom message - sends a custom message to all clients, public message to simplify
//  $language_command is the langauge string command in language files to load
function cm($language_command, $params = array())
{
    if (count($params) == 0)
        echo "CUSTOM_MESSAGE ".$langauge_command."\n";
    else
    {
        $extras = "";
        foreach($params as $param)
        {
            $extras .= $param." ";
        }
        echo "CUSTOM_MESSAGE ".$langauge_command." ".$extras."\n";
    }
}

function roundBegan()
{
    global $game;

    $game->timer->reset();
    $game->timer->start();
    
    //  clear all previous race data
    unset($game->races);
    $game->races = array();
    $game->firstTime_ = -1;
    $game->countdown_ = -1;
    $game->finishRank = 1;

    $game->roundFinished = false;
    
    //showLadder();      //  show ranks
}

function roundEnded()
{
    global $game;

    $game->timer->stop();
    $game->roundFinished = true;
    
    //  reset done for rotation per round
    if ($game->rotation_type == 1)
        $game->rotation->done = false;
    
    //showLadder();

    clearCycles();
}

function declareRoundWinner($name)
{
    echo "DECLARE_ROUND_WINNER ".$name."\n";
}
?>