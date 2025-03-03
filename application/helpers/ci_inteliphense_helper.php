<?php
/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpFullyQualifiedNameUsageInspection */

class CI_Controller {
    /** @var CI_Loader */
    public $load;
    /** @var CI_Input */
    public $input;
    /** @var CI_Session */
    public $session;
    /** @var CI_DB_query_builder */
    public $db;
    
}

class CI_Model {
    /** @var CI_DB_query_builder */
    public $db;
}
