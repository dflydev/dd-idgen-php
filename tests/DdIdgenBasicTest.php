<?php

require_once('PHPUnit/Framework.php');

define('PACKAGE_LIB', dirname(dirname(__FILE__)) . '/lib/');
$includePath = explode(PATH_SEPARATOR, get_include_path());
array_unshift($includePath, PACKAGE_LIB);
set_include_path(implode(PATH_SEPARATOR, $includePath));

class DdIdgenBasicTest extends PHPUnit_Framework_TestCase {

    /**
     * Simple test to see if test starts up.
     */
    public function testStartup() {
        $dataSource = $this->dataSource(true);
        
        require_once('dd_idgen_DataSourceIdStore.php');
        require_once('dd_idgen_RandomStringIdGenerator.php');
        require_once('dd_idgen_IdGen.php');
        
        // Test with mob support.
        
        $idStore = new dd_idgen_DataSourceIdStore($dataSource, 'identities', 'identity', 'mob');
        $idGenerator = new dd_idgen_RandomStringIdGenerator(5);
        $idGen = new dd_idgen_IdGen($idStore, $idGenerator, 'dummy-testing');
        
        $id = $idGen->generate();
        $this->assertTrue(null !== $id);
        
        $id = $idGen->generate('fooBar');
        $this->assertEquals('fooBar', $id);
        
        $id = $idGen->generate('fooBar');
        $this->assertEquals(null, $id);
        
        $id = $idGen->generate('fooBarBaz');
        $this->assertEquals(null, $id);
        
        $id = $idGen->generate('fooBarBazWoot');
        $this->assertEquals('fooBarBazWoot', $id);
        
        // Test w/o mob support (from idgen)
        
        $idGen = new dd_idgen_IdGen($idStore, $idGenerator);
        
        // This test should fail since the Data Source ID Storage engine
        // does not allow for a NULL mob when a mob column name is specified.
        // This is because some (all?) common SQL engines do not handle
        // NULL in UNIQUE constraints in a way that makes sense.
        $id = $idGen->generate();
        $this->assertTrue(null === $id);

        // Test w/o mob support in table

        $idStore = new dd_idgen_DataSourceIdStore($dataSource, 'identitiesNoMob', 'identity');
        $idGenerator = new dd_idgen_RandomStringIdGenerator(5);
        $idGen = new dd_idgen_IdGen($idStore, $idGenerator);

        $id = $idGen->generate();
        $this->assertTrue(null !== $id);
        
        $id = $idGen->generate('fooBar');
        $this->assertEquals('fooBar', $id);
        
        $id = $idGen->generate('fooBar');
        $this->assertEquals(null, $id);
        
        $id = $idGen->generate('fooBarBaz');
        $this->assertEquals(null, $id);
        
        $id = $idGen->generate('fooBarBazWoot');
        $this->assertEquals('fooBarBazWoot', $id);
        
    }
    
    public function dataSource($initDb = false) {
        $dataSource = new PDO('sqlite:' . dirname(__FILE__) . '/DdIdgenBasicTest.sq3');
        $dataSource->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ( $initDb ) {
            $message = null;
            try {
                
                $dataSource->exec('DROP TABLE IF EXISTS identities');
                $dataSource->exec('
CREATE TABLE identities (
identitiesId INTEGER PRIMARY KEY AUTOINCREMENT,
identity TEXT,
mob TEXT,
UNIQUE(identity, mob)
)
');
                $dataSource->exec('INSERT INTO identities (identity, mob) VALUES ("fooBarBaz", "dummy-testing")');
                $dataSource->exec('INSERT INTO identities (identity) VALUES ("fooBarBaz")');
                
                $dataSource->exec('DROP TABLE IF EXISTS identitiesNoMob');
                $dataSource->exec('
CREATE TABLE identitiesNoMob (
identitiesNoMobId INTEGER PRIMARY KEY AUTOINCREMENT,
identity TEXT,
UNIQUE(identity)
)
');
                $dataSource->exec('INSERT INTO identitiesNoMob (identity) VALUES ("fooBarBaz")');
                
            } catch (Exception $e) {
                $message = $e->getMessage();
            }
            $this->assertEquals(null, $message);
            $dataSource->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        }
        return $dataSource;
    }

}
?>
