<?php
if (!defined("__ROOT__"))
    return;

require __ROOT__."/src/engine/cycle.php";
require __ROOT__."/src/engine/player.php";
require __ROOT__."/src/engine/rotation.php";
require __ROOT__."/src/engine/timer.php";

require __ROOT__."/src/tron/game.php";
require __ROOT__."/src/tron/queue.php";
require __ROOT__."/src/tron/race.php";
require __ROOT__."/src/tron/record.php";

require __ROOT__."/src/tools/coord.php";
require __ROOT__."/src/tools/sql.php";
require __ROOT__."/src/tools/string.php";

class Base
{
    var $roundFinished = true;      //  flag for checking if round has finished or not
    var $current_map   = "";        //  holds the currently playing map of the match
    
    var $timer;                     //  holds the timer class
    var $sql;                       //  holds the sql class
    var $rotation;                  //  holds the rotation class
    
    var $players    = array();       //  hold players' data
    var $cycles     = array();       //  hold cycles' data
    var $races      = array();       //  hold racers' data (for those crossing the finish line)
    var $zones      = array();       //  hold zones' data (all zones that were created onto the field)
    
    //  cycle settings
    var $chances            = 0;           //  number of times players can be respawned per round
    var $kill_idle          = true;        //  should players get killed for remaining idle in one position
    var $kill_idle_wait     = 6;           //  waits for this many seconds before checking on cycle's position
    var $kill_idle_speed    = 10;          //  what is the idle speed that the idle kill should activate at?

    //  queueing values
    var $queue_increase_time    = 0;            //  should the queues each player increase depending on the time they play for in the server
    var $queue_give             = 4;            //  the amount of queues each player gets
    var $queue_accesslevel      = 15;           //  access level required to activate queueing
    var $queue_items            = array();      //  contains all items waiting to be loaded
    var $queue_copies           = false;        //  should queueing allow copies of different configs?

    //  rotation items to load
    var $rotation_max = 3;
    var $rotation_min = 0;

    //  zone settings
    var $zonesCollapseAfterFinish = false;

    //  race settings
    var $countdown      = true;
    var $countdownMax   = 80;
    var $smartTimer     = true;
    var $countdown_     = -1;
    var $firstTime_     = -1;
    var $finishRank     = 1;    // increments as playes cross the finish line
    
     var $race_prv_sync = 0;
         
    function Base()
    {
        $this->timer    = new Timer;
        $this->sql      = new Sql;
        $this->rotation = new Rotation;
    }
}
?>