<?php

namespace Brujeo\AWS\EC2;

class Client
{
	
    protected $ec2client;
    
    protected $instances = array();
    
    public function __construct($awsKey, $awsSecret, $awsRegion)
    {
        $this->ec2client = \Aws\Ec2\Ec2Client::factory(array(
            'key'    => $awsKey,
            'secret' => $awsSecret,
            'region' => $awsRegion,
        ));
        
        $this->loadAwsData();
    }
    
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
    
    public function getInstances()
    {
        return $this->instances;
    }
    
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
    
    public function getInstanceById($id)
    {
        foreach ($this->instances as $instance) {
            if ($instance->getId() == $id) {
                return $instance;
            }
        }
    }
    
    public function stopInstance(Instance $ec2instance)
    {
        $result = $this->ec2client->stopInstances(array(
            'DryRun'        => false,
            'InstanceIds'   => array($ec2instance->getId()),
            'Force'         => false,
        ));
        return $result;
    }
    
    public function startInstance(Instance $ec2instance)
    {
        $result = $this->ec2client->startInstances(array(
            'DryRun'        => false,
            'InstanceIds'   => array($ec2instance->getId()),
        ));
        return $result;
    }
}
