<?php

namespace Brujeo\AWS\EC2;

/**
 * EC2 Client class
 * 
 * @author Leonid Brujev <brujev@gmail.com>
 */
class Client
{
	
    /**
     * aws client instance
     * 
     * @var \Aws\Ec2\Ec2Client
     */
    protected $ec2client;
    
    /**
     * EC2 instances list
     * 
     * @var \Brujeo\AWS\EC2\Instance[]
     */
    protected $instances = array();
    
    /**
     * Constructor
     * 
     * @param string $awsKey
     * @param string $awsSecret
     * @param string $awsRegion
     */
    public function __construct($awsKey, $awsSecret, $awsRegion)
    {
        $this->ec2client = \Aws\Ec2\Ec2Client::factory(array(
            'key'    => $awsKey,
            'secret' => $awsSecret,
            'region' => $awsRegion,
        ));
        
        $this->loadAwsData();
    }
    
    /**
     * Loads EC2 instance details from AWS
     */
	protected function loadAwsData()
    {
      
        $response = $this->ec2client->describeInstances();

        // Create a list of the buckets in your account
        foreach ($response as $records) {

            if (!is_array($records)) {
                continue;
            }

            foreach ($records as $record) {

                foreach ($record['Instances'] as $instance) {

                    $instanceObject = new Instance();
                    
                    foreach ($instance['Tags'] as $tag) {

                        $instanceObject->setState($instance['State']['Name']);
                        $instanceObject->setId($instance['InstanceId']);
                        
                        if ($tag['Key'] == 'Name') {
                            $instanceObject->setName($tag['Value']);
                        }

                    }
                    
                    $this->instances[] = $instanceObject;
                    
                }
            }
        }
    }
    
    /**
     * Gets list of EC2 instances
     * 
     * @return \Brujeo\AWS\EC2\Instance[]
     */
    public function getInstances()
    {
        return $this->instances;
    }
    
    /**
     * Gets list of active EC2 instances
     * 
     * @return \Brujeo\AWS\EC2\Instance[]
     */
    public function getActiveInstances()
    {
        $instances = array();
        foreach ($this->instances as $instance) {
            if ($instance->isActive()) {
                $instances[] = $instance;
            }
        }
        return $instances;
    }
    
    /**
     * Gets EC2 instance object by instance id
     * 
     * @param string $id
     * @return \Brujeo\AWS\EC2\Instance
     */
    public function getInstanceById($id)
    {
        foreach ($this->instances as $instance) {
            if ($instance->getId() == $id) {
                return $instance;
            }
        }
    }
    
    /**
     * Stops EC2 instance
     * 
     * @param \Brujeo\AWS\EC2\Instance $ec2instance
     * @return string
     */
    public function stopInstance(Instance $ec2instance)
    {
        $result = $this->ec2client->stopInstances(array(
            'DryRun'        => false,
            'InstanceIds'   => array($ec2instance->getId()),
            'Force'         => false,
        ));
        return $result;
    }
    
    /**
     * Starts EC2 instance
     * 
     * @param \Brujeo\AWS\EC2\Instance $ec2instance
     * @return string
     */
    public function startInstance(Instance $ec2instance)
    {
        $result = $this->ec2client->startInstances(array(
            'DryRun'        => false,
            'InstanceIds'   => array($ec2instance->getId()),
        ));
        return $result;
    }
}
