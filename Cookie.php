<?php

namespace NGFramer\NGFramerPHPBase;

use NGFramer\NGFramerPHPBase\Defaults\Exceptions\CookieException;

class Cookie
{
    /**
     * Instance of the Cookie class.
     * @var Cookie
     */
    private static Cookie $cookie;


    /**
     * Cookie configuration.
     * @var array
     **/
    private array $cookieConfig = [];


    /**
     * Instantiate for Render.
     * Use the same object all the time
     */
    public static function init(): static
    {
        if (empty(self::$cookie)) {
            self::$cookie = new self();
        }
        return self::$cookie;
    }


    /**
     * Function to set Cookie name.
     *
     * @param string $name
     * @return static
     */
    public function name(string $name): static
    {
        $this->cookieConfig['name'] = $name;
        return $this;
    }


    /**
     * Function to set Cookie value.
     *
     * @param mixed $value
     * @return static
     */
    public function value(mixed $value): static
    {
        $this->cookieConfig['value'] = $value;
        return $this;
    }


    /**
     * Function to set the Cookies expiry time.
     *
     * @param int $time . Time from now in seconds.
     * @return static
     */
    public function expires(int $time): static
    {
        $this->cookieConfig['expires'] = time() + $time;
        return $this;
    }


    /**
     * Function to set the Cookies as flash.
     *
     * @return static
     */
    public function flash(): static
    {
        $this->cookieConfig['flash'] = true;
        return $this;
    }


    /**
     * Function to set the Cookies path.
     *
     * @param string $path
     * @return static
     */
    public function path(string $path): static
    {
        $this->cookieConfig['path'] = $path;
        return $this;
    }


    /**
     * Function to set the Cookies domain.
     *
     * @param string $domain
     * @return static
     */
    public function domain(string $domain): static
    {
        $this->cookieConfig['domain'] = $domain;
        return $this;
    }


    /**
     * Function to set the Cookies secure.
     *
     * @param bool $secure
     * @return static
     */
    public function secure(bool $secure): static
    {
        $this->cookieConfig['secure'] = $secure;
        return $this;
    }


    /**
     * Function to set the Cookies httpOnly.
     *
     * @param bool $httpOnly
     * @return static
     */
    public function httpOnly(bool $httpOnly): static
    {
        $this->cookieConfig['httpOnly'] = $httpOnly;
        return $this;
    }


    /**
     * Function to set the Cookie.
     *
     * @return void
     *
     * @throws CookieException
     */
    public function set(): void
    {
        // Check for the name and value of the cookie.
        if (empty($this->cookieConfig['name'])) {
            throw new CookieException('Cookie name is required.', 0, 'base.cookie.nameRequired', null, 500);
        }
        if (empty($this->cookieConfig['value'])) {
            throw new CookieException('Cookie value is required.', 0, 'base.cookie.valueRequired', null, 500);
        }
        if ($this->cookieConfig['flash'] ?? false) {
            $this->cookieConfig['name'] = 'flash.' . $this->cookieConfig['name'];
            $this->cookieConfig['expires'] = 0;
        }

        // Set cookie based on the data provided above.
        setcookie(
            $this->cookieConfig['name'],
            $this->cookieConfig['value'],
            $this->cookieConfig['expires'] ?? 0,
            $this->cookieConfig['path'] ?? '/',
            $this->cookieConfig['domain'] ?? '',
            $this->cookieConfig['secure'] ?? false,
            $this->cookieConfig['httpOnly'] ?? false
        );
    }


    /**
     * Function to get the Cookie.
     *
     * @return mixed
     */
    public function get(): mixed
    {
        // Get the key of the cookie.
        $cookieName = $this->cookieConfig['name'];
        $isFlash = $this->cookieConfig['flash'] ?? false;

        if ($isFlash) {
            if (empty($cookieName)) {
                return null;
            } else {
                $cookieName = 'flash.' . $cookieName;
                $cookieValue = $_COOKIE[$cookieName] ?? null;
                $this->delete();
                return $cookieValue;
            }
        } else {
            // Check if the cookie exists.
            if (empty($cookieName)) {
                return null;
            } else {
                return $_COOKIE[$cookieName] ?? null;
            }
        }
    }


    /**
     * Function to delete or expire the cookie.
     *
     * @return void
     */
    public function delete(): void
    {
        // Check if the cookie is a flash cookie.
        if (isset($this->cookieConfig['flash'])) {
            $this->cookieConfig['name'] = 'flash.' . $this->cookieConfig['name'];
        }
        // Check if the cookie exists.
        if (!isset($_COOKIE[$this->cookieConfig['name']])) {
            return;
        } else {
            $cookieName = $this->cookieConfig['name'];
            setcookie($cookieName, '', time() - 3600, '/');
            unset($_COOKIE[$cookieName]);
        }
    }
}
