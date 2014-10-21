<?php namespace EmailValidator;

/**
 * EmailValidator
 *
 * @package    EmailValidator
 * @copyright  Copyright (c) 2014 James Jackson
 * @license    The MIT License (MIT)
 */
class Validator
{
    /**
     * An array of disposable email domains. Populated when required.
     *
     * @var mixed
     * @access private
     */
    private $disposable = null;

    /**
     * An array of role email users. Populated when required.
     *
     * @var mixed
     * @access private
     */
    private $role = null;

    /**
     * An array of example TLDs.
     * As defined in http://tools.ietf.org/html/rfc2606
     *
     * @var array
     * @access private
     */
    private $exampleTlds = [
        '.test',
        '.example',
        '.invalid',
        '.localhost',
    ];

    /**
     * An array of example domains.
     * As defined in http://tools.ietf.org/html/rfc2606
     *
     * @var array
     * @access private
     */
    private $exampleDomains = [
        'example.com',
        'example.net',
        'example.org',
    ];

    /**
     * Detected example email addresses.
     * As defined in http://tools.ietf.org/html/rfc2606
     *
     * @param string $email Address
     * @return boolean|null
     */
    public function isExample($email)
    {
        $hostname = $this->hostnameFromEmail($email);


        if ($hostname) {
            if (in_array($hostname, $this->exampleDomains)) {
                return true;
            }

            foreach ($this->exampleTlds as $tld) {
                $length = strlen($tld);
                $subStr = substr($hostname, -$length);

                if ($subStr == $tld) {
                    return true;
                }
            }

            return false;
        }

        return null;
    }

    /**
     * Test email address for MX records
     *
     * @param string $email Address
     * @return boolean|null
     */
    public function hasMx($email)
    {
        $hostname = $this->hostnameFromEmail($email);

        if ($hostname) {
            $mxhosts = [];

            getmxrr($hostname, $mxhosts);

            if (is_array($mxhosts) && !empty($mxhosts)) {
                return true;
            }

            return false;
        }

        return null;
    }

    /**
     * Detected disposable email domains
     *
     * @param string $email Address
     * @return boolean|null
     */
    public function isDisposable($email)
    {
        $hostname = $this->hostnameFromEmail($email);

        if ($hostname) {

            // Load disposable domains
            if (is_null($this->disposable)) {
                $this->disposable = include('data/disposable.php');
            }

            // Search array for hostname
            if (in_array($hostname, $this->disposable)) {
                return true;
            }

            return false;
        }

        return null;
    }

    /**
     * Detected role based email addresses
     *
     * @param string $email Address
     * @return boolean|null
     */
    public function isRole($email)
    {
        $user = $this->userFromEmail($email);

        if ($user) {
            // Load disposable domains
            if (is_null($this->role)) {
                $this->role = include('data/role.php');
            }

            // Search array for hostname
            if (in_array($user, $this->role)) {
                return true;
            }

            return false;
        }

        return null;
    }

    /**
     * Get the user form an email address
     *
     * @access private
     * @param string $email Address
     * @return string|null
     */
    private function userFromEmail($email)
    {
        $parts = explode('@', $email);

        if (count($parts) == 2) {
            return strtolower($parts[0]);
        }

        return null;
    }

    /**
     * Get the hostname form an email address
     *
     * @access private
     * @param string $email Address
     * @return string|null
     */
    private function hostnameFromEmail($email)
    {
        $parts = explode('@', $email);

        if (count($parts) == 2) {
            return strtolower($parts[1]);
        }

        return null;
    }
}