<?php

require_once('dd_idgen_IIdStore.php');

interface dd_idgen_IIdGenerator {

    /**
     * Generate an ID
     * @param dd_idgen_IIdStore $idStore
     * @param string $mob
     * @param string $suggestion
     */
    public function generate(dd_idgen_IIdStore $idStore, $mob = null, $suggestion = null);

}