<?php
require_once(dirname(__FILE__).'/../init.php');

class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        chdir(dirname(__file__));
        foreach (glob("*Test.php") as $filename) {
            $this->addFile($filename);
        }
    }
}
?>