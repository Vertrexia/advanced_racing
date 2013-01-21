<?php
if (!defined("__ROOT__")) 
    return;

//  class to store racing stats for the round, temporary
class Race
{
    var $name;
    var $time;

    var $first = false; //  did this player cross the finish line, first?
    
    function Race()
    {
        global $game;
        
        $game->races[] = $this;
    }
}

//  syncher for racing
function racesync()
{
    global $game;
    
    //  don't run this if countdown is not enabled
    if (!$game->countdown) return;

    //  if round has ended, don't go through anymore
    if ($game->roundFinished) return;

    $ais    = 0;
    $humans = 0;
    $alive  = 0;

    if (count($game->players) > 0)
    {
        foreach ($game->players as $p)
        {
            if ($p instanceof Player)
            {
                if ($p->isHuman)
                    $humans++;
                else
                    $ais++;
                
                $cycle = getCycle($p->log_name);
                if ($cycle & $cycle->isAlive)
                    $alive++;
            }
        }
    }
    else
    {
        return;
    }
    
    //  perform actions if no one is alive and there are humans players present in the server
    if (($humans > 0) && ($alive == 0) && ($ais == 0))
    {
        $game->roundFinished = true;
        decideWinner();
        return;
    }

    //  perform actions if only one player is alive and in the presence of themselves or more human players
    if (($humans > 0) && ($alive == 1) && ($ais == 0))
    {
        if ($game->smartTimer)
        {
            if (numRecords() > 0)
            {
                if ($game->countdown_ == -1)
                {
                    $game->sql->connect();
                    $result = getRecords(3);
                    if ($result)
                    {
                        $time = 0;
                        $rows = mysql_num_rows($result);
                        
                        while($row = mysql_fetch_assoc($result))
                        {
                            $time += round($row["time"]);
                        }
                        
                        $game->countdown_ = (($time / $rows) * 1.2) + 1;
                    }
                    $game->sql->close();
                }
            }
            else
            {
                if ($game->countdown_ == -1)
                    $game->countdown_ = $game->countdownMax + 1;
            }
        }
        else
        {
            if ($game->countdown_ == -1)
                $game->countdown_ = $game->countdownMax + 1;
        }
        
        //  make sure there is a 1 second time gap between countdown.
        //  otherwise, it will finish too quickly
        if (($game->timer->gameTimer() - $game->race_prv_sync) >= 1)
        {
            $game->countdown_--;

            if ($game->countdown_ > 0)
                center("0xff7777".$game->countdown_."                    ");
            else
            {
                $game->roundFinished = true;
                decideWinner();
            }
            
            $game->race_prv_sync = $game->timer->gameTimer();
        }          
    }
}

//  player crossing the finish line
function crossLine($name)
{
    global $game;

    $found = false;
    if (count($game->races) > 0)
    {
        foreach ($game->races as $racer)
        {
            if ($racer instanceof Race)
            {
                if ($racer->name == $name)
                    $found = true;
            }
        }
    }
    
    //  if player has already crossed the finish line, don't do the following
    if ($found) return;
    
    $rPlayer = new Race;
    if ($rPlayer)
    {
        $rPlayer->name = $name;
        $rPlayer->time = $game->timer->gameTimer();

        if (!$game->firstTime_ == -1)
        {
            $rPlayer->first = true;
            $game->firstTime_ = $rPlayer->time;

            $player = getPlayer();
            if ($player)
            {
                cm("race_finish_first", array($player->screen_name, $rPlayer->time));
            }
        }
        else
        {
            $player = $game->p->getPlayer();
            if ($player)
            {
                $game->finishRank++;
                cm("race_finish_after_first", array($player->screen_name, $game->finishRank, $rPlayer->time, ($rPlayer->time - $game->firstTime_)));
            }
        }

        if (recordExists($name))
        {
            adjustRecord($name, $rPlayer->time);
        }
        else
            newRecord($name, $rPlayer->time);
    }
}

//  decicing a winner
function decideWinner()
{
    global $game;
    
    if ((count($game->races) > 0) && ($game->firstTime_ != -1))
    {
        foreach ($game->races as $racer)
        {
            if ($racer instanceof Race)
            {
                $player = getPlayer($racer->name);
                if ($player && $racer->first)
                {
                    declareRoundWinner($player->screen_name);
                }
            }
        }
    }
}
?>