<?php

require_once('dd_idgen_AbstractIdGenerator.php');
require_once('dd_idgen_IIdStore.php');

class dd_idgen_RandomStringIdGenerator extends dd_idgen_AbstractIdGenerator {
    
    /**
     * Max size of generated string
     * @var int
     */
    protected $maxSize;
    
    /**
     * Min size of generated string
     * @var int
     */
    protected $minSize;
    
    /**
     * Allowed characters
     * @var array
     */
    protected $allowedCharacters;
    
    /**
     * Create a new random string ID generator
     * @param $maxSize
     * @param $minSize
     * @param $allowedCharacters
     */
    public function __construct($maxSize, $minSize = null, $allowedCharacters = null) {
        
        if ( $minSize === null ) $minSize = $maxSize;
        if ( $allowedCharacters === null ) $allowedCharacters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        
        $this->maxSize = $maxSize;
        $this->minSize = $minSize;

        $this->allowedCharacters = $allowedCharacters;
        
    }

    /**
     * (non-PHPdoc)
     * @see dd_idgen_AbstractIdGenerator::generateInternal()
     */
    protected function generateInternal(dd_idgen_IIdStore $idStore, $mob = null) {
        $out = '';
        $allowedMax = strlen($this->allowedCharacters) - 1;
        while ( strlen($out) < $this->maxSize ) {
            $nextChar = $this->allowedCharacters[rand(0, $allowedMax)];
            $out .= $nextChar;
            if ( strlen($out) >= $this->minSize ) {
                $identity = $idStore->store($out, $mob);
                if ( $identity !== null ) return $identity;
            }
        }
        return null;
    }
    
}