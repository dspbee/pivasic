<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Database;

/**
 * Class MySQL
 * @package Dspbee\Bundle\Database
 */
class MySQL
{
    /**
     * @param string $host
     * @param string $login
     * @param string $password
     * @param string $database
     */
    public function __construct($host, $login, $password, $database) {
        $this->db = null;

        $this->db = new \mysqli($host, $login, $password, $database);
        if (!$this->db->connect_error) {
            $this->db->query("SET NAMES 'UTF8'");
        }
    }

    /**
     * Get connect to the Mysql database.
     *
     * @return \mysqli|null
     */
    public function connect()
    {
        return $this->db;
    }

    /**
     * Set server timezone to the MySQL database.
     */
    public function setTimezone()
    {
        if (null !== $this->db) {
            $now = new \DateTime();
            $minutes = $now->getOffset() / 60;
            $sgn = ($minutes < 0 ? -1 : 1);
            $minutes = abs($minutes);
            $hrs = floor($minutes / 60);
            $minutes -= $hrs * 60;
            $offset = sprintf('%+d:%02d', $hrs * $sgn, $minutes);
            $this->db->query("SET time_zone='{$offset}'");
        }
    }

    /**
     * @var \mysqli
     */
    private $db;
}