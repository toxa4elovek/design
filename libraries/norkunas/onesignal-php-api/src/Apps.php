<?php

namespace OneSignal;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Apps
{
    /**
     * @var OneSignal
     */
    protected $api;

    /**
     * Constructor.
     *
     * @param OneSignal $api
     */
    public function __construct(OneSignal $api)
    {
        $this->api = $api;
    }

    /**
     * Get information about application with provided ID.
     *
     * User authentication key must be set.
     *
     * @param string $id ID of your application
     *
     * @return array
     */
    public function getOne($id)
    {
        return $this->api->request('GET', '/apps/' . $id, [
            'headers' => [
                'Authorization' => 'Basic ' . $this->api->getConfig()->getUserAuthKey(),
            ],
        ]);
    }

    /**
     * Get information about all your created applications.
     *
     * User authentication key must be set.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->api->request('GET', '/apps', [
            'headers' => [
                'Authorization' => 'Basic ' . $this->api->getConfig()->getUserAuthKey(),
            ],
        ]);
    }

    /**
     * Create a new application with provided data.
     *
     * User authentication key must be set.
     *
     * @param array $data Application data
     *
     * @return array
     */
    public function add(array $data)
    {
        $data = $this->resolve($data);

        return $this->api->request('POST', '/apps', [
            'headers' => [
                'Authorization' => 'Basic ' . $this->api->getConfig()->getUserAuthKey(),
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);
    }

    /**
     * Update application with provided data.
     *
     * User authentication key must be set.
     *
     * @param string $id   ID of your application
     * @param array  $data New application data
     *
     * @return \GuzzleHttp\Message\Response
     */
    public function update($id, array $data)
    {
        $data = $this->resolve($data);

        return $this->api->request('PUT', '/apps/' . $id, [
            'headers' => [
                'Authorization' => 'Basic ' . $this->api->getConfig()->getUserAuthKey(),
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);
    }

    protected function resolve(array $data)
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired('name');
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setDefined(['apns_env', 'apns_p12', 'apns_p12_password', 'gcm_key']);
        $resolver->setAllowedTypes('apns_env', 'string');
        $resolver->setAllowedValues('apns_env', ['sandbox', 'production']);
        $resolver->setAllowedTypes('apns_p12', 'string');
        $resolver->setAllowedTypes('apns_p12_password', 'string');
        $resolver->setAllowedTypes('gcm_key', 'string');

        return $resolver->resolve($data);
    }
}
