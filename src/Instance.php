<?php

namespace Brujeo\AWS\EC2;

class Instance
{
    const STATE_STOPPED = 'stopped';
    const STATE_RUNNING = 'running';
    
    protected $id;
    protected $name;
    protected $state;
    
    protected $client;
    
    public function getId()
    {
        return $this->id;
    }
        
    public function getName()
    {
        return $this->name;
    }

    public function getState()
    {
        return $this->state;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setState($state)
    {
        $this->state = $state;
    }
    
    
    
    // ====================================
    
    public static function getByOId($id)
    {
        $ec2instance = ClientFactory::getClient()->getInstanceById($id);   
        return $ec2instance;
    }
    
    public function isActive()
    {
        return ($this->state == self::STATE_RUNNING);
    }
    
    public function shutdown()
    {
        echo "Shutting down {$this->getId()} ... " . PHP_EOL;
        return ClientFactory::getClient()->stopInstance($this);
    }
    
    public function startup()
    {
        echo "Starting up {$this->getId()} ... " . PHP_EOL;
        return ClientFactory::getClient()->startInstance($this);
    }
    
}

