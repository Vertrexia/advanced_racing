<?php
if (!defined("__ROOT__"))
    return;

class Sql
{
    //  mysql: that stores player's records
    var $mysql_server           = "localhost";
    var $mysql_username         = "root";
    var $mysql_password         = "";
    var $mysql_database         = "racing";
    
    var $mysql_table_records    = "records";
    var $mysql_table_maps       = "maps";
    var $mysql_table_rotation   = "rotation";
    var $mysql_table_queuers    = "queuers";
    
    var $sql;   //  handler for connecting to mysql
    
    function connect()
    {
        $this->sql = mysql_connect($this->mysql_server, $this->mysql_username, $this->mysql_password);
        mysql_select_db($this->mysql_database, $this->sql);
    }
    
    function close()
    {
        mysql_close($this->sql);
    }
    
    function addErrors($message)
    {
        mysql_query('INSERT INTO `errors`(`date`, `message`) VALUES ("'.date("Y-m-d g:i:s a").'", "'.$message.'")', $this->sql);
    }
}
?>