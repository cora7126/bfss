<?php

/**
 * Entry point for legacy calls
 *
 * Devs not using composer autoload will have included this file directly.
 * Keeping it as a wrapper allows to retain compatibility with legacy projects
 * while allowing adjustments to the source to improve composer integration.
 */

define('FPDM_DIRECT', true);

require_once("fpdm.php");

require_once("filters/FilterASCIIHex.php");
require_once("filters/FilterASCII85.php");
require_once("filters/FilterFlate.php");
require_once("filters/FilterLZW.php");
require_once("filters/FilterStandard.php");
