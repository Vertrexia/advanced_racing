<?php
if (!defined("__ROOT__"))
    return;

class Rotation
{
    var $current    = "";      //  holds the currently loaded item
    var $currentID  = 0;       //  the current row of loaded item from mysql
    var $done       = false;
    
    function rotate()
    {
        global $game;
        
        $game->sql->connect();
        
        $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_rotation.'` ORDER BY `id` ASC', $game->sql->sql);
        if ($result)
        {
            if (mysql_num_rows($result) > 0)
            {
                if ($this->currentID > mysql_num_rows($result))
                    $this->currentID = 0;
                
                $this->currentID++;
                while($row = mysql_fetch_assoc($result))
                {
                    if (isset($row["item"]))
                    {
                        if ($currentID == $row["id"])
                        {
                            //  make sure this item is allowed to be loaded
                            if ($row["allowed"] == 1)
                            {
                                $this->current = $row["item"];
                                break;
                            }
                            else
                            {
                                $currentID++;
                            }
                        }
                    }
                }

                //  make sure rotation is enabled
                if ($game->rotation_type > 0)
                {
                    /*if ($game->rotation_load == 0)
                    {
                        echo "INCLUDE ".$this->current."\n";
                    }
                    elseif ($game->rotation_load == 1)
                    {
                        echo "SINCLUDE ".$this->current."\n";
                    }
                    elseif ($game->rotation_load == 2)
                    {
                        echo "RINCLUDE ".$this->current."\n";
                    }*/
                    
                    echo "MAP_FILE ".$this->current."\n";

                    //con("Reading from ".$this->current);
                    cm("race_rotation_loading", array($this->current));
                }
            }
        }
        else
            $game->sql->addErrors(mysql_error());

        $game->sql->close();
    }

    function displayRotation($name)
    {
        global $game;
        
        $player = getPlayer($name);
        if ($player)
        {
            $game->sql->connect();
            
            pm($player->screen_name, "0xff5500List of maps in rotation:");
            
            $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_rotation.'` ORDER BY `id` ASC', $game->sql->sql);
            if ($result)
            {
                if (mysql_num_rows($result) > 0)
                {
                    while($row = mysql_fetch_assoc($result))
                    {
                        pm($player->screen_name, "0xff00ff+ 0x3399ff".$row["item"]);
                    }
                }
            }
            else
                $game->sql->addErrors(mysql_error());
            
            $game->sql->close();
        }
    }
}
?>