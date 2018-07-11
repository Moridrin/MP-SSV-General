<?php

namespace mp_general\Revisions;

interface InstallRevision
{
    public static function install(\Plugin_Upgrader $tmp): void;
}
