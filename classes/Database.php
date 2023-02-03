<?php

/**
 * Connection to Database
 */

 class Database {

    /**
     * Hostname
     * @var string
     */
    private $dbhost;

    /**
     * Database name
     * @var string
     */
    private $dbname;

    /**
     * User granted to use database
     * @var string
     */
    private $dbuser;

    /**
     * Password required to connect database
     * @var string
     */
    private $dbpassword;

    /**
     * Constructor
     * @param string $dbhost Hostname
     * @param string $dbname Database name
     * @param string $dbuser Username
     * @param string $dbpassword Password
     *
     * @return void
     */
    function __construct($dbhost,$dbname,$dbuser,$dbpassword) {
        $this->dbhost = $dbhost;
        $this->dbname = $dbname;
        $this->dbuser = $dbuser;
        $this->dbpassword = $dbpassword;
    }

    /**
     * Get the database connection
     * @return PDO object. Connection to the database
     */
    public function getConnectionToDatabase() {
        try {
        $dbConnection = new PDO('mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname, $this->dbuser, $this->dbpassword);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

 }