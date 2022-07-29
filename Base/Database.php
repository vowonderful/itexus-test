<?php

namespace Base;

use PDO;

/**
 * This class chooses which view to show.
 */
class Database
{
    private readonly string $driver;
    private readonly string $host;
    private readonly string $database;
    private readonly string $username;
    private readonly string $password;
    private readonly string $charset;
    private readonly string $collation;
    private readonly string $prefix;

    public function __construct()
    {
        $DataBase = _DataBaseParams;

        $this->driver = $DataBase['driver'];
        $this->host = $DataBase['host'];
        $this->database = $DataBase['database'];
        $this->username = $DataBase['username'];
        $this->password = $DataBase['password'];
        $this->charset = $DataBase['charset'];
        $this->collation = $DataBase['collation'];
        $this->prefix = $DataBase['prefix'];
    }

    public function getDSN(): string {
        return $this->driver . ":dbname=" . $this->database . ';host=' . $this->host . ';charset=' . $this->charset;
    }

    public function PDO(): PDO
    {
        return new PDO($this->getDSN(), $this->getUsername(), $this->getPassword(), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    public function getDriver(): string
    {
        return $this->driver;
    }
    public function getHost(): string
    {
        return $this->host;
    }
    public function getDatabase(): string
    {
        return $this->database;
    }
    public function getUsername(): string
    {
        return $this->username;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getCharset(): string
    {
        return $this->charset;
    }
    public function getCollation(): string
    {
        return $this->collation;
    }
    public function getPrefix(): string
    {
        return $this->prefix;
    }
}