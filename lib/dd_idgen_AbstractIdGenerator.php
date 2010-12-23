<?php

require_once('dd_idgen_IIdGenerator.php');
require_once('dd_idgen_IIdStore.php');

abstract class dd_idgen_AbstractIdGenerator implements dd_idgen_IIdGenerator {

    /**
     * (non-PHPdoc)
     * @see dd_idgen_IIdGenerator::generate()
     */
    final public function generate(dd_idgen_IIdStore $idStore, $mob = null, $suggestion = null) {
        if ( $suggestion !== null ) { return $idStore->store($suggestion, $mob); }
        return $this->generateInternal($idStore, $mob); 
    }
    
    /**
     * Generate and ID
     * 
     * This is used internally by dd_idgen_AbstractIdGenerator and is what
     * subclasses of dd_idgen_AbstractIdGenerator should implement.
     * @param dd_idgen_IIdStore $idStore
     * @param string $mob
     */
    abstract protected function generateInternal(dd_idgen_IIdStore $idStore, $mob = null);
    
}