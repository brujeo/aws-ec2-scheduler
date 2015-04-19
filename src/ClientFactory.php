<?php

namespace Brujeo\AWS\EC2;

use Symfony\Component\Yaml\Yaml;

/**
 * EC2 Client Factory class
 * 
 * @author Leonid Brujev <brujev@gmail.com>
 */
class ClientFactory
{
    /**
     * EC2 client instance
     * 
     * @var Brujeo\AWS\EC2\Client
     */
    public static $instance;
    
    /**
     * Gets EC2 client instance
     * 
     * @return Brujeo\AWS\EC2\Client
     */
    public static function getClient()
    {
        if (!isset(self::$instance)) {
            
            $config = Yaml::parse(
                file_get_contents(BASE_PATH . '/config/settings.yml')
            );
            
            self::$instance = new Client($config['aws_key'], $config['aws_secret'], $config['aws_region']);
        }
        return self::$instance;
    }
}