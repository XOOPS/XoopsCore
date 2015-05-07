<?php
namespace Doctrine\DBAL\Driver;

class Statement extends ResultStatement
{
    function bindValue($param, $value, $type = null)
    {
        return true;
    }

    function bindParam($column, &$variable, $type = null, $length = null)
    {
        return true;
    }

    function errorCode()
    {
        return 'error';
    }

    function errorInfo()
    {
        return array();
    }

    function execute($params = null)
    {
        return true;
    }

    function rowCount()
    {
        return 1;
    }
}
