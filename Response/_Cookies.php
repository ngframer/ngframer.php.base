<?php

namespace NGFramer\NGFramerPHPBase\Response;

use NGFramer\NGFramerPHPBase\Defaults\Exceptions\ResponseException;

class _Cookies
{
    /**
     * Cookie configuration.
     * @var array
     **/
    private array $cookieConfig = [];


    /**
     * Function to set Cookies name.
     *
     * @param string $name
     * @return void
     */
    public function name(string $name): void
    {
            $this->cookieConfig['name'] = $name;
    }


    /**
     * Function to set Cookies value.
     *
     * @param mixed $value
     * @return void
     */
    public function value(mixed $value): void
    {
        $this->cookieConfig['value'] = $value;
    }


    /**
     * Function to set the Cookies expiry time.
     *
     * @param int $time
     * @return void
     */
    public function expires(int $time): void
    {
        $this->cookieConfig['expires'] = time() + $time;
    }


    /**
     * Function to set the Cookies path.
     *
     * @param string $path
     * @return void
     */
    public function path(string $path): void
    {
        $this->cookieConfig['path'] = $path;
    }


    /**
     * Function to set the Cookies domain.
     *
     * @param string $domain
     * @return void
     */
    public function domain(string $domain): void
    {
        $this->cookieConfig['domain'] = $domain;
    }


    /**
     * Function to set the Cookies secure.
     *
     * @param bool $secure
     * @return void
     */
    public function secure(bool $secure): void
    {
        $this->cookieConfig['secure'] = $secure;
    }


    /**
     * Function to set the Cookies httpOnly.
     *
     * @param bool $httpOnly
     * @return void
     */
    public function httpOnly(bool $httpOnly): void
    {
        $this->cookieConfig['httpOnly'] = $httpOnly;
    }


    /**
     * Function to set the Cookie.
     *
     * @return void
     * @throws ResponseException
     */
    public function set(): void
    {
        // Check for the name and value of the cookie.
        if (empty($this->cookieConfig['name'])) {
            throw new ResponseException('Cookie name is required.', 0, 'base.cookie.nameRequired', null, 500);
        }
        if (empty($this->cookieConfig['value'])) {
            throw new ResponseException('Cookie value is required.', 0, 'base.cookie.valueRequired', null, 500);
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
        return $_COOKIE[$this->cookieConfig['name']] ?? null;
    }


    /**
     * Function to delete the Cookie.
     *
     * @return void
     */
    public function delete(): void
    {
        if (isset($_COOKIE[$this->cookieConfig['name']])) {
            unset($_COOKIE[$this->cookieConfig['name']]);
        }
    }
}