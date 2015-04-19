<?php

namespace Brujeo\AWS\EC2;

use Cron\CronExpression;

class Scheduler
{
    
    protected $scheduledTasks;
    
    public function __construct(array $scheduledTasks)
    {
        $this->scheduledTasks = $scheduledTasks;
    }
   
    public function run()
    {
        foreach ($this->scheduledTasks as $task) {
            
            // check startup
            $startupCron = CronExpression::factory($task['start']);
            if ($startupCron->isDue()) {
                $instance = Instance::getByOId($task['instance_id']);
                    
                if (!$instance->isActive()) {
                    $result = $instance->startup();
                    print_r($result);
                }
            }
            
            // check shutdown
            $shutdownCron = CronExpression::factory($task['shutdown']);
            if ($shutdownCron->isDue()) {
                $instance = Instance::getByOId($task['instance_id']);
                    
                if ($instance->isActive()) {
                    $result = $instance->shutdown();
                    print_r($result);
                }
            }
             
        }
    }
}
