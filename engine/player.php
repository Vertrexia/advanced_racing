<?php
if (!defined("__ROOT__"))
    return;

class Player
{
    var $log_name;
    var $screen_name;
    
    function Player($name, $screen_name)
    {
        global $game;

        $this->log_name     = $name;
        $this->screen_name  = $screen_name;
        
        $game->players[] = $this;
    }
}

function PlayerExists($name)
{
    global $game;
    
    if (count($game->players) > 0)
    {
        foreach ($game->players as $player)
        {
            if ($player instanceof Player)
            {
                if ($player->log_name == $name)
                    return true;
            }
        }
    }
    return false;
}

function getPlayer($name)
{
    global $game;
    
    if (count($game->players) > 0)
    {
        foreach ($game->players as $player)
        {
            if ($player instanceof Player)
            {
                if ($player->log_name == $name)
                    return $player;
            }
        }
    }
    return false;
}
?>