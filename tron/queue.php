<?php
if (!defined("__ROOT__"))
    return;

function getQueuers($limit = 0)
{
    global $game;
    
    if ($limit > 0)
    {
        $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_queuers.'` LIMIT 0, '.$limit, $game->sql->sql);
        if ($result)
        {
            return $result;
        }
    }
    else
    {
        $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_queuers.'`', $game->sql->sql);
        if ($result)
        {
            return $result;
        }
    }
}

function queuerExists($name)
{
    global $game;
    
    $game->sql->connect();
    
    $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_queuers.'` WHERE `player`=\''.addslashes($name).'\'', $game->sql->sql);
    if ($result)
    {
        if (mysql_num_rows($result) == 1)
        {
            $row = mysql_fetch_assoc($result);
            if ($row["player"] == $name)
            {
                $game->sql->close();
                return true;
            }
        }
    }
    else
    {
        $game->sql->addErrors(mysql_error());
    }
    
    //  close the mysql connection
    $game->sql->close();
    
    return false;
}

function newQueuer($name)
{
    global $game;
    
    if (!recordExists($name))
    {
        $game->sql->connect();
        
        $result = mysql_query('INSERT INTO `'.$game->sql->mysql_table_queuers.'`(`player`, `amount`, `current`) VALUES ("'.addslashes($name).'", "'.$game->queue_give.'", "'.$game->queue_give.'")');
        
        //  if operation failed, log an error message in database
        if (!$result)
        {
            $game->sql->addErrors(mysql_error());
        }
        
        $game->sql->close();
    }
}

function queueHasMore($name)
{
    global $game;
    
    $game->sql->connect();
    
    $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_queuers.'` WHERE `player`=\''.addslashes($name).'\'', $game->sql->sql);
    if ($result)
    {
        
    }
    else
        $game->sql->addErrors(mysql_error());
    
    $game->sql->close();
}

function queueUseUp($name)
{
    global $game;
    
    $game->sql->connect();
    
    $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_queuers.'` WHERE `player`=\''.addslashes($name).'\'', $game->sql->sql);
    
    //  if operation failed, log an error message in database
    if ($result)
    {
        $row = mysql_fetch_assoc($result);
        if ($row["player"] == $name)
        {
            $player = getPlayer($name);
            if ($player)
            {
                $prevAmount = $row["current"];
                if ($prevAmount > 0)
                {
                    $prevAmount--;
                    $result = mysql_query('UPDATE `'.$game->sql->mysql_table_queuers.'` SET `current`="'.$prevAmount.'" WHERE `player`=\''.addslashes($name).'\'', $game->sql->sql);
                    
                    if (!$result)
                        $game->sql->addErrors(mysql_error());
                }
                else
                {
                    cpm($player->screen_name, "race_queuer_usedup");
                }
            }
        }
    }
    else
        $game->sql->addErrors(mysql_error());
    
    $game->sql->close();
}

function makeQueue($name, $extra)
{
    global $game;
    
    $pos = 0;
    $item = extractNonBlankString($extra, $pos);
    
    $game->sql->connect();
    
    $result = mysql_query('SELECT * FROM `'.$game->Sql->mysql_table_rotation.'`', $game->sql->sql);
    if ($result)
    {
        $player = getPlayer($name);
        if ($player)
        {
            if ($item != "")
            {
                if (mysql_num_rows($result) > 0)
                {
                    $found = array();
                    while($row = mysql_fetch_assoc($result))
                    {
                        if (contains($row["item"], $item))
                        {
                            $found[] = $row["item"];
                        }
                    }
                    
                    if (count($found) > 0)
                    {
                        if (count($find) == 1)
                        {
                            $game->queue_items[] = $find[0];
                            cm("race_queueing_added", array($player->screen_name, $find[0]));
                        }
                        else
                        {
                            cpm($player->screen_name, "race_queueing_foundmany", array($item));
                        }
                    }
                    else
                    {
                        cpm($player->screen_name, "race_queueing_failed", array($item));
                    }
                }
                else
                {
                    cpm($player->screen_name, "race_queueing_failed", array($item));
                }
            }
            else
            {
                pm($player->screen_name, "0xffdd77Oops. Your entry is empty.");
            }
        }
    }
    $game->sql->close();
}
?>