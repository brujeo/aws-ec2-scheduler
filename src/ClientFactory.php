<?php

namespace Brujeo\AWS\EC2;

use Symfony\Component\Yaml\Yaml;

class ClientFactory
{
    public static $instance;
    
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