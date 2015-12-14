<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\Session;

/**
 * Class Session
 * @package Dspbee\Bundle\Common\Session
 */
class Session
{
    public function __construct()
    {
        $this->session = null;
        ini_set('session.use_cookies', 1);
    }

    /**
     * @return SessionBag|null
     */
    public function bag()
    {
        return $this->session;
    }

    /**
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function start()
    {
        if (null !== $this->session) {
            return true;
        }

        if (\PHP_SESSION_ACTIVE === session_status()) {
            throw new \RuntimeException('Failed to start the session: already started by PHP.');
        }

        if (ini_get('session.use_cookies') && headers_sent($file, $line)) {
            throw new \RuntimeException(sprintf('Failed to start the session because headers have already been sent by "%s" at line %d.', $file, $line));
        }

        if (!session_start()) {
            throw new \RuntimeException('Failed to start the session');
        }

        $this->session = new SessionBag();

        return true;
    }

    /**
     * @param bool|false $destroy
     * @param null $lifetime
     * @return bool
     */
    public function regenerate($destroy = false, $lifetime = null)
    {
        if (\PHP_SESSION_ACTIVE !== session_status()) {
            return false;
        }

        if (null !== $lifetime) {
            ini_set('session.cookie_lifetime', $lifetime);
        }

        return session_regenerate_id($destroy);
    }

    public function destroy()
    {
        if (isset($_SESSION)) {
            $_SESSION = [];
        }
    }

    /**
     * Gets the session ID.
     *
     * @return string
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * Sets the session ID.
     *
     * @param string $id
     *
     * @throws \LogicException
     */
    public function setId($id)
    {
        if (null === $this->session) {
            throw new \LogicException('Cannot change the ID of an active session');
        }

        session_id($id);
    }

    /**
     * Gets the session name.
     *
     * @return string
     */
    public function getName()
    {
        return session_name();
    }

    /**
     * Sets the session name.
     *
     * @param string $name
     *
     * @throws \LogicException
     */
    public function setName($name)
    {
        if (null === $this->session) {
            throw new \LogicException('Cannot change the name of an active session');
        }

        session_name($name);
    }

    private $session;
}