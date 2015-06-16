<?php

namespace Glad\Driver\Repository;

use Glad\Constants;

class RepositoryAdapter {

    protected $constants;

    public function __construct()
    {
        $this->constants = new Constants;
    }

    /**
     * Data serializer
     *
     * @param $data array
     *
     * @return string
     */
    protected function serializer(array $data)
    {
        if($this->config['type'] == 'serialize') {
            return serialize($data);
        }
        return json_encode($data);
    }

    /**
     * Data unserializer
     *
     * @param $data string
     *
     * @return array
     */
    protected function unserializer($data)
    {
        if($this->config['type'] == 'serialize' && preg_match('/[a-z]\:[0-9]/', $data)) {
            return unserialize($data);
        }
        return json_decode($data, true);
    }

    /**
     * Current timestamp
     *
     * @return integer
     */
    protected function now()
    {
        return time();
    }

    /**
     * Encrypt the session data
     *
     * @param $data string
     *
     * @return string
     */
    protected function dataCrypt($data)
    {
        if($this->config['crypt'] === true) {
            $data = $this->crypt->encrypt($data, $this->constants->secret); 
        }
        return $data;
    }

    /**
     * Decrypt the session data
     *
     * @param $data string
     *
     * @return string
     */
    protected function dataDecrypt($data)
    {
        if(! preg_match('/\{.*?\}/', $data)) {
            if($this->config['crypt'] === true) {
                $data = $this->crypt->decrypt($data, $this->constants->secret); 
            }
        }
        return $data;
    }
}