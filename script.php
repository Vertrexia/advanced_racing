<?php
define("__ROOT__", dirname(__FILE__));

require "base.php";
$game = new Base;

while(1)
{
    $line = rtrim(fgets(STDIN, 1024));
    if (startswith($line, "PLAYER_ENTERED"))
    {
        $pieces = explode(" ", $line);
        
        new Player($pieces[1], substr($line, strlen($pieces[0]) + strlen($pieces[1]) + strlen($pieces[2]) + 3));
        
        if (!queuerExists($pieces[1]))
            newQueuer($pieces[1]);
    }
    elseif (startswith($line, "PLAYER_RENAMED"))
    {
        $pieces = explode(" ", $line);
        
        $player = getPlayer($pieces[1]);
        if ($player)
        {
            $player->log_name       = $pieces[2];
            $player->screen_name    = substr($line, strlen($pieces[0]) + strlen($pieces[1]) + strlen($pieces[2]) + strlen($pieces[3]) + 4);
            
            if (!queuerExists($player->log_name))
                newQueuer($player->log_name);
        }
    }
    elseif (startswith($line, "PLAYER_LEFT"))
    {
        $pieces = explode(" ", $line);
        
        for($i = 0; $i < count($game->players); $i++)
        {
            $player = $game->players[$i];
            if ($player instanceof Player)
            {
                if ($player->log_name == $pieces[1])
                {
                    //  if the player left the grid and server at the same time, let's make the cycle not alive
                    $cycle = getCycle($player->log_name);
                    if ($cycle)
                    {
                        $cycle->isAlive = false;
                        $cycle->player  = null;
                    }
                    
                    //  let's delete player's data
                    unset($game->players[$i]);
                    unset($player);
                    
                    //  break out of the loop since our work is done
                    break;
                }
            }
        }
    }
    elseif (startswith($line, "CYCLE_CREATED"))
    {
        $pieces = explode(" ", $line);
        
        new Cycle($pieces[1], Coord($pieces[2], $pieces[3]), Coord($pieces[4], $pieces[5]));
    }
    elseif (startswith($line, "CYCLE_DESTROYED"))
    {
        $pieces = explode(" ", $line);
        
        cycleDestroyed($pieces[1], $pieces[2], $pieces[3], $pieces[4], $pieces[5]);
    }
    elseif (startswith($line, "PLAYER_GRIDPOS"))
    {
        $pieces = explode(" ", $line);
        
        player_gridpos($pieces[1], $pieces[2], $pieces[3], $pieces[4], $pieces[5], $pieces[6]);
    }
    elseif (startswith($line, "CURRENT_MAP"))
    {
        $pieces = explode(" ", $line);
        $game->current_map = $pieces[1];
        
        $game->sql->connect();
        $result = mysql_query('SELECT * FROM '.$game->sql->mysql_table_maps.' WHERE `name`=\''.$game->current_map.'\'', $game->sql->sql);
        if ($result)
        {
            $row = mysql_fetch_assoc($result);
            if (mysql_num_rows($result) == 1)
            {
                if ($row["name"] == $game->current_map)
                {
                    $played_times = $row["played"];
                    $played_times++;
                    mysql_query('UPDATE `'.$game->sql->mysql_table_maps.'` SET `played`="'.$played_times.'" WHERE `name`=\''.$game->current_map.'\'', $game->sql->sql);
                }
            }
            else
            {
                mysql_query('INSERT INTO `'.$game->sql->mysql_table_maps.'`(`name`) VALUES ("'.$game->current_map.'")', $game->sql->sql);
            }
        }
        else
        {
            $game->sql->addErrors(mysql_error());
        }
        $game->sql->close();
    }
    elseif (startswith($line, "NEW_ROUND"))
    {
        //  check if the queue is empty to do regular rotation
        if (count($game->queue_items) == 0)
        {
            //  rotate once the rotation max is reached
            if ($game->rotation_min == $game->rotation_max)
            {
                $game->rotation->rotate();        //  rotate item
                $game->rotation_min = 0;          //  reset value
            }
            else $game->rotation_min++;           //  increment value
        }
        else
        {
            $item = $game->queue_items[0];
            if ($item != "")
            {                
                echo "MAP_FILE ".$item."\n";
                
                cm("race_queue_loading", array($item));
            }
            
            //  remove queued item from list afterwards
            if (count($game->queue_items) == 1)
            {
                unset($game->queue_items[0]);
                $game->queue_items = array();
            }
            else
                unset($game->queue_items[0]);
        }
    }
    elseif (startswith($line, "WINZONE_PLAYER_ENTER"))
    {
        $lineExt = explode(" ", $line);
        crossLine($lineExt[1]);
    }
    elseif (startswith($line, "INVALID_COMMAND"))
    {
        $pieces = explode(" ", $line);
        $player = getPlayer($pieces[2]);
        
        if (!$player)
            return;
        
        $extra = substr($line, strlen($pieces[0]) + strlen($pieces[1]) + strlen($pieces[2]) + strlen($pieces[3]) + strlen($pieces[4]) + 5);
        
        //  chat command to add maps to queue
        if ($pieces[1] == "/q")
        {
            makeQueue($pieces[2], $extra);
        }
        //  display the maps currently loaded in rotation
        elseif ($pieces[1] == "/r")
        {
            $pos = 0;
            $page = extractNonBlankString($extra, $pos);
            
            if (is_numeric($page))
                $game->rotation->displayRotation($pieces[2]);
            else
            {
                pm($player->screen_name, '0x9999ffOpps, wrong usage.');
                pm($player->screen_name, 'Usage: /r page_number. Replace "page_number" with the id of the maps to view.');
                pm($player->screen_name, '0x9999ffIDs of the maps from 1 - 64. So, you have plenty of ids to choose from.');
            }
        }
        else
            pm($player->screen_name, "What are you doing? ".$pieces[1]." is an unknwon command.");
    }
    elseif ((startswith($line, "ZONE_CREATED")) || (startswith($line, "ZONE_SPAWNED")))
    {
        $pieces = explode(" ", $line);
        
        //  add spawned zone to list
        new Zone($pieces[1], $pieces[2]);
    }
    elseif (startswith($line, "SHUTDOWN"))
    {
        //  quit the script since server has shutdown
        exit();
    }

    racesync();
}
?>