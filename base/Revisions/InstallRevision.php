<?php

namespace mp_general\Revisions;

interface InstallRevision
{
    public static function install(array $upgraderObject, array $options): void;
}
