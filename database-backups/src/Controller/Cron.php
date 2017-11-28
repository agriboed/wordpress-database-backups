<?php

namespace DatabaseBackups\Controller;


use DatabaseBackups\Core\Container;
use DatabaseBackups\Interfaces\HooksInterface;

class Cron extends ControllerAbstract implements HooksInterface
{
    /**
     *
     */
    public function initHooks()
    {
        add_action('init', array($this, 'cronCheck'));

        add_filter('cron_schedules', array($this, 'modifySchedules'));
    }

    public function cronCheck()
    {

    }

    /**
     * @param $schedules
     * @return array
     */
    public function modifySchedules($schedules)
    {
        $schedules['weekly'] = array(
            'interval' => 60 * 60 * 24 * 7,
            'display' => __('Once Weekly', Container::key())
        );
        $schedules['weekly_twice'] = array(
            'interval' => round((60 * 60 * 24 * 7) / 2),
            'display' => __('Twice Weekly', Container::key())
        );
        $schedules['monthly'] = array(
            'interval' => 60 * 60 * 24 * 7 * 31,
            'display' => __('Once Monthly', Container::key())
        );
        $schedules['monthly_twice'] = array(
            'interval' => round((60 * 60 * 24 * 7 * 31) / 2),
            'display' => __('Twice Monthly', Container::key())
        );
        return $schedules;
    }
}
