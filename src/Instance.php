<?php

namespace Brujeo\AWS\EC2;

/**
 * EC2 instance model class
 * 
 * @author Leonid Brujev <brujev@gmail.com>
 */
class Instance
{
    const STATE_STOPPED = 'stopped';
    const STATE_RUNNING = 'running';
    
    /**
     * Instance id
     * 
     * @var string 
     */
    protected $id;
    
    /**
     * Instance name
     * 
     * @var string
     */
    protected $name;
    
    /**
     * Instance state
     * 
     * @var string
     */
    protected $state;
  
    /**
     * EC2 client
     * 
     * @var Brujeo\AWS\EC2\Client 
     */
    protected $client;
    
    /**
     * Gets instance id
     * 
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
        
    /**
     * Gets instance name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets instance state
     * 
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
    
    /**
     * Sets instance id
     * 
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Sets instance name
     * 
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Sets instance state
     * 
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }
    
    // ====================================
    
    /**
     * Gets EC2 instance model by instance ID
     * 
     * @param string $id
     * @return Brujeo\AWS\EC2\Instance
     */
    public static function getByOId($id)
    {
        $ec2instance = ClientFactory::getClient()->getInstanceById($id);   
        return $ec2instance;
    }
    
    /**
     * Checks whether instance is active
     * 
     * @return bool
     */
    public function isActive()
    {
        return ($this->state == self::STATE_RUNNING);
    }
    
    /**
     * Shuts down EC2 instance
     */
    public function shutdown()
    {
        syslog("Shutting down {$this->getId()}");
        ClientFactory::getClient()->stopInstance($this);
    }
    
    /**
     * Starts up EC2 instance
     */
    public function startup()
    {
        syslog("Starting up {$this->getId()}");
        ClientFactory::getClient()->startInstance($this);
    }
    
}

