<?php

require_once('dd_idgen_IIdStore.php');

class dd_idgen_DataSourceIdStore implements dd_idgen_IIdStore {
    
    /**
     * PDO Data Source
     * @var PDO
     */
    protected $dataSource;
    
    /**
     * Table name
     * @var string
     */
    protected $tableName;
    
    /**
     * Identity Column Name
     * @var string
     */
    protected $identityColumnName;
    
    /**
     * Mob Column Name
     * @var string
     */
    protected $mobColumnName;

    /**
     * Create an instance of a Data Source backed ID Store
     * @param $dataSource
     * @param $tableName
     * @param $identityColumnName
     * @param $mobColumnName
     */
    public function __construct(PDO $dataSource, $tableName, $identityColumnName, $mobColumnName = null) {
        $this->dataSource = $dataSource;
        $this->tableName = $tableName;
        $this->identityColumnName = $identityColumnName;
        $this->mobColumnName = $mobColumnName;
    }
    
    /**
     * (non-PHPdoc)
     * @see dd_idgen_IIdStore::store()
     */
    public function store($identity, $mob = null) {
        
        if ( $mob === null and $this->mobColumnName !== null ) {
            return null;
        }
        
        // We do some things with our PDO Data Source that might be different
        // than how the caller might be wanting to deal with PDO.
        $originalPdoErrorMode = $this->dataSource->getAttribute(PDO::ATTR_ERRMODE);
        if ( $originalPdoErrorMode != PDO::ERRMODE_EXCEPTION ) {
            $this->dataSource->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        
        try {
            
            //
            // Insert into the database blindly.
            //
            // Relies on datbase driver to throw exception on duplicate key
            // errors. Relies on user to have designe database to have unique
            // constraint on id + mob columns. 
            //
            
            $values = array($identity);
            $sql = null;
            
            if ( $mob === null ) {
                $sql = 'INSERT INTO ' . $this->tableName . ' (' . $this->identityColumnName . ') VALUES (?)';
            } else {
                $sql = 'INSERT INTO ' . $this->tableName . ' (' . $this->identityColumnName . ', ' . $this->mobColumnName . ') VALUES (?, ?)';
                $values[] = $mob;
            }
            
            $sth = $this->dataSource->prepare($sql);
            $sth->execute($values);
            
        } catch (Exception $e) {
            // Any exception is a failure. Change the identity to null as
            // $identity is what is going to be returned to our caller.
            $identity = null;
        }
                
        if ( $originalPdoErrorMode != PDO::ERRMODE_EXCEPTION ) {
            $this->dataSource->setAttribute(PDO::ATTR_ERRMODE, $originalPdoErrorMode);
        }
        
        return $identity;

    }

}