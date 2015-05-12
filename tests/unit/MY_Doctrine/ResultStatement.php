<?php

namespace Doctrine\DBAL\Driver;

class ResultStatement
{
    public function closeCursor()
    {
        return true;
    }

    public function columnCount()
    {
        return 1;
    }

    public function setFetchMode($fetchMode, $arg2 = null, $arg3 = null)
    {
        return true;
    }

    public function fetch($fetchMode = null)
    {
        return array();
    }

    public function fetchAll($fetchMode = null)
    {
        return array();
    }

    public function fetchColumn($columnIndex = 0)
    {
        return 'column';
    }
}
