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
    elseif (startswith($line, "NEW_MATCH"))
    {
        //  check if the queue is empty to do regular rotation
        if (count($game->queue_items) == 0)
        {
            //  rotatie every match
            if ($game->rotation_type == 2)
            {
                $game->rotation->rotate();        //  rotate item
                $game->rotation->done = true;     //  yup, rotation has done it's job
            }
        }
    }    
    elseif (startswith($line, "NEW_ROUND"))
    {
        //  check if the queue is empty to do regular rotation
        if (count($game->queue_items) == 0)
        {
            //  rotate every round
            if (($game->rotation_type == 1) && (!$game->rotation->done))
            {
                $game->rotation->rotate();        //  rotate item
                $game->rotation->done = true;     //  yup, rotation has done it's job
            }
        }
        else
        {
            $item = $game->queue_items[0];
            if ($item != "")
            {
                /*if ($game->rotation_load == 0)
                {
                    echo "INCLUDE ".$item."\n";
                }
                elseif ($game->rotation_load == 1)
                {
                    echo "SINCLUDE ".$item."\n";
                }
                elseif ($game->rotation_load == 2)
                {
                    echo "RINCLUDE ".$item."\n";
                }*/
                
                echo "MAP_FILE ".$item."\n";
                
                //con("Reading from queue: ".$item);
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
    elseif (startswith($line, "MATCH_ENDED"))
    {
        //  reset done for rotation per match
        if ($game->rotation_type == 2)
            $game->rotation->done = false;
    }
    elseif (startswith($line, "WINZONE_PLAYER_ENTER"))
    {
        $lineExt = explode(" ", $line);
        crossLine($lineExt[1]);
    }
    elseif (startswith($line, "INVALID_COMMAND"))
    {
        $pieces = explode(" ", $line);
        
        //  chat command to add maps to queue
        if ($pieces[1] == "/q")
        {
            $extra = substr($line, strlen($pieces[0]) + strlen($pieces[1]) + strlen($pieces[2]) + strlen($pieces[3]) + strlen($pieces[4]) + 5);
            makeQueue($pieces[2], $extra);
        }
        //  display the maps currently loaded in rotation
        elseif ($pieces[1] == "/r")
        {
            $game->rotation->displayRotation($pieces[2]);
        }
        //  add a map to the rotation bank
        elseif ($pieces[1] == "/add")
        {
            //  code later
        }
        //  remove a map from the rotation bank (won't actually delete map but sets "allow" to 0 instead)
        elseif ($pieces[1] == "/remove")
        {
            //  code later
        }
    }
    
    racesync();
}
?>