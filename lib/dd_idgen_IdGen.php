<?php

require_once('dd_idgen_IIdStore.php');
require_once('dd_idgen_IIdGenerator.php');

class dd_idgen_IdGen {
    
    /**
     * ID Store
     * @var dd_idgen_IIdStore
     */
    protected $idStore;
    
    /**
     * ID Generator
     * @var dd_idgen_IIdGenerator
     */
    protected $idGenerator;
    
    /**
     * Mob
     * @var string
     */
    protected $mob;
    
    /**
     * Create a new IdGen instance
     * @param $idStore
     * @param $idGenerator
     * @param $mob
     */
    public function __construct(dd_idgen_IIdStore $idStore, dd_idgen_IIdGenerator $idGenerator, $mob = null) {
        $this->idStore = $idStore;
        $this->idGenerator = $idGenerator;
        $this->mob = $mob;
    }
    
    /**
     * Generate a new identity
     * @param $suggestion
     */
    public function generate($suggestion = null) {
        return $this->idGenerator->generate($this->idStore, $this->mob, $suggestion);
    }
    
}