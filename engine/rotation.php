<?php
if (!defined("__ROOT__"))
    return;

class Rotation
{
    var $current    = "";      //  holds the currently loaded item
    var $currentID  = 0;       //  the current row of loaded item from mysql
    
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

                echo "MAP_FILE ".$this->current."\n";

                cm("race_rotation_loading", array($this->current));
            }
        }
        else
            $game->sql->addErrors(mysql_error());

        $game->sql->close();
    }

    function displayRotation($name, $page)
    {
        global $game;
        
        $player = getPlayer($name);
        if ($player)
        {
            $game->sql->connect();
            
            pm($player->screen_name, "0xff5500List of maps in rotation:");
            
            $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_rotation.'` ORDER BY `id` ASC LIMIT '.$page.', 10', $game->sql->sql);
            $max_id = 0;
            if ($result)
            {
                if (mysql_num_rows($result) > 0)
                {
                    while($row = mysql_fetch_assoc($result))
                    {
                        pm($player->screen_name, "+ 0xff00ff".$row["id"]." 0x3399ff".$row["item"]);
                        $max_id = $row["id"];
                    }
                }
                
                if ($max_id < mysql_num_rows($result))
                {
                    pm($player->screen_name, " 0x0099ffThere are ".(mysql_num_rows($result) - $max_id)." more maps in the rotation bank.");
                }
            }
            else
                $game->sql->addErrors(mysql_error());
            
            $game->sql->close();
        }
    }
}
?>