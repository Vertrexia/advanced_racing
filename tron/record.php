<?php
if (!defined("__ROOT__"))
    return;

class Record
{
    var $rank;
    var $name;
    var $time;
    
    function Record($rank, $name, $time)
    {
        $this->rank = $rank;
        $this->name = $name;
        $this->time = $time;
    }
}

function numRecords($limit = 0)
{
    global $game;
    
    $game->sql->connect();
    
    $rows = 0;
    if ($limit > 0)
    {
        $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_records.'` WHERE `map`=\''.$game->current_map.'\' ORDER BY `time` ASC LIMIT 0, '.$limit, $game->sql->sql);
        if ($result)
        {
            $rows = mysql_num_rows($result);
        }
    }
    else
    {
        $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_records.'` WHERE `map`=\''.$game->current_map.'\'" ORDER BY `time` ASC', $game->sql->sql);
        if ($result)
        {
            $rows = mysql_num_rows($result);
        }
    }
    
    $game->sql->close();
    
    return $rows;
}

function getRecords($limit = 0)
{
    global $game;
    
    if ($limit > 0)
    {
        $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_records.'` WHERE `map`=\''.$game->current_map.'\' ORDER BY `time` ASC LIMIT 0, '.$limit, $game->sql->sql);
        if ($result)
        {
            return $result;
        }
    }
    else
    {
        $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_records.'` WHERE `map`=\''.$game->current_map.'\' ORDER BY `time` ASC', $game->sql->sql);
        if ($result)
        {
            return $result;
        }
    }
}

function recordExists($name)
{
    global $game;
    
    //  open a new mysql connection
    $game->sql->connect();
    
    $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_records.'` WHERE `player`=\''.addslashes($name).'\' AND `map`=\''.$game->current_map.'\'', $game->sql->sql);
    if ($result)
    {
        if (mysql_num_rows($result) == 1)
        {
            $row = mysql_fetch_assoc($result);
            if (($row["player"] == $name) && ($row["map"] == $game->current_map))
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

function newRecord($name, $time)
{
    global $game;
    
    if (!recordExists($name))
    {
        $game->sql->connect();
        $player = getPlayer($name);
        $result = mysql_query('INSERT INTO `'.$game->sql->mysql_table_records.'`(`player`, `map`, `time`) VALUES ("'.addslashes($name).'", "'.$game->current_map.'", "'.$time.'")');
        
        cpm($player->screen_name, "race_personal_time", array($time));
        
        //  if operation failed, log an error message in database
        if (!$result)
        {
            $game->sql->addErrors(mysql_error());
        }
        
        $game->sql->close();
    }
}

function adjustRecord($name, $time)
{
    global $game;
    
    $game->sql->connect();
    
    $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_records.'` WHERE `player`=\''.addslashes($name).'\' AND `map`=\''.$game->current_map.'\'', $game->sql->sql);
    
    //  if operation failed, log an error message in database
    if ($result)
    {
        $row = mysql_fetch_assoc($result);
        if ($row["player"] == $name)
        {
            $player = getPlayer($name);
            if ($player)
            {
                $prevTime = $row["time"];
                if ($time < $prevTime)
                {
                    $result = mysql_query('UPDATE `'.$game->sql->mysql_table_records.'` SET `time`="'.$time.'" WHERE `player`=\''.addslashes($name).'\' AND `map`=\''.$game->current_map.'\'', $game->sql->sql);
                    
                    if (!$result)
                        $game->sql->addErrors(mysql_error());
                        
                    cpm($player->screen_name, "race_time_faster", array($time, $time - $prevTime));
                }
                else
                {
                    cpm($player->screen_name, "race_time_slower", array($time, $prevTime - $time));
                }
            }
        }
    }
    else
        $game->sql->addErrors(mysql_error());
    
    $game->sql->close();
}

function showRecords()
{
    global $game;
    
    $game->sql->connect();
    if ($game->roundFinished)
    {
        $rank = 1;
        $records = array();
        $maxlen = 0;
        
        $result = mysql_query('SELECT * FROM `'.$game->sql->mysql_table_records.'` WHERE `map`=\''.$game->current_map.'\' ORDER BY `time` ASC');
        if ($result)
        {
            if (mysql_num_rows($result) > 0)
            {
                //  first get all records for that map
                while($row = mysql_fetch_assoc($result))
                {                    
                    $records[] = new Record($rank, $row["player"], $row["time"]);
                    
                    //  increment the player's rank
                    $rank++;
                }
                
                if (count($records) > 0)
                {
                    //  fetch the max length
                    foreach ($records as $record)
                    {
                        if ($record instanceof Record)
                        {
                            if (strlen($record->name) > $maxlen)
                                $maxlen = strlen($record->name); 
                        }
                    }
                    
                    //  next, display the top 5 (if <5) racers
                    for($i = 0; $i < 5; $i++)
                    {
                        $record = $records[$i];
                        if ($record instanceof Record)
                        {
                            $output = "";
                            $output .= "  0x00ffff".$record->rank;
                            $output .= "  0x66ff22|  0xff5500".$record->name;
                            
                            setPos($output, strlen($output) + ($maxlen - strlen($record->name)), false);
                            
                            $output .= "  0x66ff22|  0x9999ff".$record->time;

                            con($output);
                        }
                    }
                    
                    //  fianlly, display individual ranks to those people
                    foreach ($records as $record)
                    {
                        if ($record instanceof Record)
                        {
                            $player = getPlayer($record->name);
                            if ($player)
                            {
                                cpm($player->screen_name, "race_ladder_individual", array($record->rank, $record->time));
                            }
                        }
                    }
                }
            }
        }
    }
    $game->sql->close();
}
?>