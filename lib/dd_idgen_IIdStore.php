<?php

interface dd_idgen_IIdStore {
    
    /**
     * Store an identity
     * @param $identity
     * @param $mob
     */
    public function store($identity, $mob = null);
    
}