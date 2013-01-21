<?php
if (!defined("__ROOT__"))
    return;

class Zone
{
    var $id;
    var $name;
    
    function Zone($id, $name)
    {
        global $game;
        
        $this->id   = $id;
        $this->name = $name;
        
        $game->zones[] = $this;
    }
}

function clearZones()
{
    global $game;
    
    if (count($game->zones) > 0)
    {
        for($i = 0; $i < count($game->zones); $i++)
        {
            $zone = $game->zones[$i];
            if ($zone instanceof Zone)
            {
                collapseZoneId($zone->id);
                unset($game->zones);
                unset($zone);
                $i--;
            }
        }
    }
}

function collapseZone($name)
{
    echo "COLLAPSE_ZONE ".$name."\n";
}

function destroyZone($name)
{
    echo "DESTROY_ZONE ".$name."\n";
}

function collapseZoneId($id)
{
    echo "COLLAPSE_ZONE_ID ".$id."\n";
}

function destroyZoneId($id)
{
    echo "DESTROY_ZONE_ID ".$id."\n";
}
?>