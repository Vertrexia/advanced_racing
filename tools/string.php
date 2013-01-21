<?php
if (!defined("__ROOT__"))
    return;

//  string beginning with
function startswith($str, $find)
{
    if (strlen($find) > strlen($str))
    {
        return false;
    }
    
    if (strlen($find) == 0)
    {
        return false;
    }
    
    $isThis = substr($str, 0, strlen($find));
    if ($isThis == $find)
    {
        return true;
    }
    return false;
}

//  string ends with
function endswith($str, $find)
{
    if (strlen($find) > strlen($str))
    {
        return false;
    }

    if (strlen($find) == 0)
    {
        return false;
    }

    $isThis = substr($str, strlen($str) - strlen($find));
    if ($isThis == $find)
    {
        return true;
    }
    return false;
}

//  bool: checks whether string exists in line
function contains($str, $find)
{
    if (strlen($find) > strlen($str))
    {
        return false;
    }

    if (strlen($find) == 0)
    {
        return false;
    }

    for ($i = 0; $i < strlen($str); $i++)
    {
        $isThis = substr($str, $i, strlen($find));
        if ($isThis == $find)
        {
            return true;
        }
    }

    return false;
}

//  extracts substring without spaces from that position
function extractNonBlankString($str, &$pos)
{
    if ($pos > strlen($str))
        return false;

    $toReturn = "";

    while (($pos < strlen($str)) && (is_null($str[$pos]) && ($str[$pos] == " ")))
    {
        $pos++;
    }
    
    if ($pos > strlen($str))
        return false;

    while (($pos < strlen($str)) && (!is_null($str[$pos]) && ($str[$pos] != " ")))
    {
        $toReturn .= $str[$pos++];
    }
    
    if ($pos > strlen($str))
        return false;

    return $toReturn;
}

function setPos(&$str, $pos, $cut)
{
    if ( $pos < strlen($str) )
    {
        if ( $cut )
        {
            if ( $pos > 0 )
            {
                $str .= ' ';
            }
        }
        else
        {
            $str .= ' ';
        }
    }
    if( $pos == strlen($str) && !$cut)
    {
        $str .= ' ';
    }
    for($i = strlen($str); $i < $pos; $i++)
        $str .= ' ';
}
?>