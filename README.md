# aws-ec2-scheduler
Scheduler to launch and shutdown AWS EC2 instances on a regular basis

## Installing
if you have composer installed globally, just run 
```
composer install
```
otherwise get composer from https://getcomposer.org/download/

## Configuration
copy config/settings.yml.dist file to config/settings.yml and set **aws_key**, **aws_secret** and **aws_region**

## Scheduling EC2 instance start and shutdown
Edit config/schedule.yml file and set your schedules.

Sample schedule job
```
test001_job:
    instance_id: i-941d5871
    start: "30 8 * * *"
    shutdown: "30 17 * * *"
```

Schedule string format
```
*    *    *    *    *    *
-    -    -    -    -    -
|    |    |    |    |    |
|    |    |    |    |    + year [optional]
|    |    |    |    +----- day of week (0 - 7) (Sunday=0 or 7)
|    |    |    +---------- month (1 - 12)
|    |    +--------------- day of month (1 - 31)
|    +-------------------- hour (0 - 23)
+------------------------- min (0 - 59)
```
