<?php

namespace DatabaseBackups\Service;

use DatabaseBackups\Exceptions\Exception;
use DatabaseBackups\Interfaces\HooksInterface;
use DatabaseBackups\Core\AbstractService;
use DatabaseBackups\Core\Container;

/**
 * Class CronService
 * @package DatabaseBackups\Service
 */
class CronService extends AbstractService implements HooksInterface
{
    /**
     *
     */
    public function initHooks()
    {
        add_action('init', [$this, 'cronCheck']);
        add_filter('cron_schedules', [$this, 'cronSchedules']);
    }

    /**
     *
     */
    public function cronCheck()
    {
        if (true !== OptionsService::getOption('cron')) {
            $this->clearSchedule();
            return;
        }

        add_action(Container::key() . '-cron', [$this, 'doCronJobs']);

        if (!wp_next_scheduled(Container::key() . '-cron')) {
            wp_schedule_event(time(), OptionsService::getOption('cron_frequency'), 'database-backups-cron');
        }
    }

    /**
     * Adds new schedules to WP
     *
     * @param $schedules
     * @return array
     */
    public function cronSchedules($schedules)
    {
        $schedules['daily'] = [
            'interval' => 60 * 60 * 24,
            'display' => __('Every Day', Container::key())
        ];

        $schedules['weekly'] = [
            'interval' => 60 * 60 * 24 * 7,
            'display' => __('Once Weekly', Container::key())
        ];
        $schedules['weekly_twice'] = [
            'interval' => round((60 * 60 * 24 * 7) / 2),
            'display' => __('Twice Weekly', Container::key())
        ];
        $schedules['monthly'] = [
            'interval' => 60 * 60 * 24 * 7 * 31,
            'display' => __('Once Monthly', Container::key())
        ];
        $schedules['monthly_twice'] = [
            'interval' => round((60 * 60 * 24 * 7 * 31) / 2),
            'display' => __('Twice Monthly', Container::key())
        ];

        return $schedules;
    }

    /**
     *
     */
    public function clearSchedule()
    {
        wp_clear_scheduled_hook(Container::key() . '-cron');
    }

    /**
     *
     * @throws Exception
     */
    public function doCronJobs()
    {
        try {
            /**
             * @var $backupService BackupService
             */
            $backupService = $this->container->get(BackupService::class);
            $backupService->checkOldCopies();
            $backupService->createBackup();
        } catch (Exception $exception) {

        }
    }
}