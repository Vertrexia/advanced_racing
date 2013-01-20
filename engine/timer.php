<?php
if (!defined("__ROOT__"))
    return;

class Timer
{
    var $startTime  = -1;
    var $stopTime   = -1;
    
    function start()
    {
        $this->$startTime = round(microtime(), 4);
    }
    
    function stop()
    {
        $this->$stopTime = round(microtime(), 4);
    }
    
    function reset()
    {
        $this->$startTime   = -1;
        $this->$stopTime    = -1;
    }
    
    function gameTimer()
    {
        if ($this->start != -1)
        {
            $gameTimer = round(microtime(), 4) - $this->$startTime;
            return $gameTimer;
        }
        return -1;
    }
    
    function elapsed()
    {
        if (($this->$startTime != -1) && ($this->$stopTime != -1))
        {
            $elapsed = $this->$stopTime - $this->$startTime;
            return $elapsed;
        }
        return -1;
    }
}
?>