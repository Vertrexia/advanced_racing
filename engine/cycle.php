<?php
if (!defined("__ROOT__"))
    return;

class Cycle
{
    var $player;            //  owner of the cycle
    
    var $spawnPos;          //  original spawn position of cycle
    var $spawnDir;          //  original spawn direction of cycle
    
    var $pos;
    var $dir;
    var $speed = 0;

    var $isAlive = false;
    var $deathTime = -1;

    var $chances = 0;

    var $kill_idle_break = -1;
    var $kill_idle_activated = false;
    var $idle_begin = -1;
    var $idle_limit = -1;
    var $idle_warn = false;

    function Cycle($name, Coord $pos, Coord $dir)
    {
        global $game;
        
        $player = getPlayer($name);
        if ($player)
        {
            $this->player = $player;
            $this->spawnPos = $pos;
            $this->spawnDir = $dir;
            $this->isAlive  = true;
            
            $game->cycles[] = $this;
        }
    }
}

function cycleExists($name)
{
    global $game;
    
    if (count($game->cycles) > 0)
    {
        foreach ($game->cycles as $cycle)
        {
            if ($cycle instanceof Cycle)
            {
                $player = $cycle->player;
                if ($player instanceof Player)
                {
                    if ($player->log_name == $name)
                        return true;
                }
            }
        }
    }
    return false;
}

function getCycle($name)
{
    global $game;
    
    if (count($game->cycles) > 0)
    {
        foreach ($game->cycles as $cycle)
        {
            if ($cycle instanceof Cycle)
            {
                $player = $cycle->player;
                if ($player instanceof Player)
                {
                    if ($player->log_name == $name)
                        return $cycle;
                }
            }
        }
    }
    return false;
}

function cycleDestroyed($name)
{
    global $game;
    $player =  getPlayer($name);

    if ($player)
    {
        $cycle = getCycle($name);

        if ($cycle)
        {
            $cycle->deathTime = $game->timer->gameTimer();
            $cycle->isAlive = false;

            //  check if chances are enabled
            //  also check if players have enough chances to be respawned
            if (($game->chances > 0) && ($cycle->chances > 0))
                respawnCycle($cycle);
        }
    }
}

function respawnCycle($cycle)
{
    global $game;
    if (($game->chances > 0) && ($cycle->chances > 0))
    {
        respawnPlayer($cycle->player->screen_name, $cycle->spawn_pos, $cycle->spawn_dir);
        cpm($cycle->player->screen_name,"racing_respawn_limit", array($cycle->chances));

        $cycle->isAlive = true;
        $cycle->chances--;
    }
}

function player_gridpos($name, $x, $y, $xdir, $ydir, $speed)
{
    global $game;
    $player = getPlayer($name);
    if ($player)
    {
        $cycle = getCycle($name);
        if ($cycle)
        {
            $cycle->speed = $speed;
            if ($cycle->isAlive && empty($cycle->pos) && empty($cycle->dir))
            {
                //  set the new pos, dir and speed of the cycle
                $cycle->pos = new Coord($x, $y);
                $cycle->dir = new Coord($xdir, $ydir);
            }
            else
            {
                if ($cycle->isAlive && $game->kill_idle)
                {                    
                    if ($cycle->isAlive && ($cycle->speed <= $game->kill_idle_speed))
                    {
                        if (!$cycle->kill_idle_activated)
                        {
                            if ($cycle->idle_begin == -1)
                            {
                                //  waits this many seconds before rechecking if player is still moving or stopped
                                $cycle->idle_limit = $game->timer->gameTimer() + $game->kill_idle_wait;
                                $cycle->idle_begin = $game->timer->gameTimer();
                            }
                            
                            if ($game->timer->gameTimer() >= $cycle->idle_limit)
                                $cycle->kill_idle_activated = true;
                        }
                        else
                        {
                            if (!$cycle->idle_warn)
                            {
                                //  send the player a warning
                                cpm($player->screen_name, "race_idle_warning");
                                
                                //  we warned them
                                $cycle->idle_warn = true;
                                
                                //  resetting this for after warning purpose
                                $cycle->idle_begin = -1; 
                            }
                            else
                            {
                                if ($cycle->idle_begin == -1)
                                {
                                    $cycle->idle_limit = $game->timer->gameTimer() + $game->kill_idle_wait;
                                    $cycle->idle_begin = $game->timer->gameTimer();
                                }
                                
                                //  after all that, they aren't reacting?
                                if ($game->timer->gameTimer() >= $cycle->idle_limit)
                                {
                                    //  no choice, let's remove them from the grid
                                    killPlayer($player->screen_name);
                                    
                                    cm("race_idle_kill", array($player->screen_name));
                                }
                            }
                        }
                    }
                    else
                    {
                        //  if cycle is moving faster, no need for the activation of idle killing
                        //  reset values to default
                        $cycle->kill_idle_activated = false;
                        $cycle->kill_idle_break = -1;
                        $cycle->idle_begin = -1;
                        $cycle->idle_limit = -1;
                        $cycle->idle_warn = false;
                    }
                }

                //  set the new pos, dir and speed of the cycle
                $cycle->pos = new Coord($x, $y);
                $cycle->dir = new Coord($xdir, $ydir);
            }
        }
    }
}

function clearCycles()
{
    global $game;
    if (count($game->cycles) > 0)
    {
        unset($game->cycles);
        $game->cycles = array();
    }
}

function respawnPlayer($name, Coord $pos, Coord $dir)
{
    echo "RESPAWN_PLAYER ".$name." ".$pos->x." ".$pos->y." ".$dir->x." ".$dir->y."\n";
}

function killPlayer($name)
{
    echo "KILL ".$name."\n";
}
?>