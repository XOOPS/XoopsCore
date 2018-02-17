<?php

class upgrade_2018 extends xoopsUpgrade
{
    public function isApplied()
    {
        return $this->check_config_type();
    }

    public function apply()
    {
        return $this->apply_alter_tables();
    }

    public function check_config_type()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $sql = "SHOW COLUMNS FROM " . $db->prefix("config") . " LIKE 'conf_title'";
        $result = $db->queryF($sql);
        while ($row = $db->fetchArray($result)) {
            if (strtolower(trim($row["Type"])) == "varchar(255)") {
                return true;
            }
        }
        return false;
    }

    public function query($sql)
    {
        //echo $sql . "<br>";
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        if (!($ret = $db->queryF($sql))) {
            echo $db->error();
        }
    }

    public function apply_alter_tables()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $this->fields = array(
            "config"            => array(
                "conf_title" => "varchar(255) NOT NULL default ''", "conf_desc" => "varchar(255) NOT NULL default ''"
            ), "configcategory" => array("confcat_name" => "varchar(255) NOT NULL default ''"),
        );

        foreach ($this->fields as $table => $data) {
            foreach ($data as $field => $property) {
                $sql = "ALTER TABLE " . $db->prefix($table) . " CHANGE `$field` `$field` $property";
                $this->query($sql);
            }
        }
        return true;
    }
}

$upg = new upgrade_2018();
return $upg;
