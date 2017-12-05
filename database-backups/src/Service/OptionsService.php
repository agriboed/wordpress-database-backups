<?php

namespace DatabaseBackups\Service;

use DatabaseBackups\Core\AbstractService;
use DatabaseBackups\Core\Container;

/**
 * Class Option
 * @package DatabaseBackups\Service
 */
class OptionsService extends AbstractService
{
    /**
     * Returns option
     *
     * @param string $option_name
     * @param null $default
     * @return mixed
     */
    public static function getOption($option_name, $default = null)
    {
        return get_option(Container::key() . '_' . $option_name, $default);
    }

    /**
     * Set option
     *
     * @param $option_name
     * @param $value
     * @return bool
     */
    protected function setOption($option_name, $value)
    {
        return update_option(Container::key() . '_' . $option_name, $value);
    }

    /**
     * Set whole options
     *
     * @param array $options
     * @throws \Exception
     */
    public function setOptions(array $options)
    {
        if (isset($options['directory']) && !empty($options['directory'])) {
            $this->setOption('directory', trim($options['directory']));
        }

        $this->setOption('limit', isset($options['limit']) ? (int)$options['limit'] : 0);
        $this->setOption('prefix', isset($options['prefix']) ? true : false);
        $this->setOption('clean', isset($options['clean']) ? true : false);
        $this->setOption('notify', isset($options['notify']) ? true : false);
        $this->setOption('gzip', isset($options['gzip']) ? true : false);
        $this->setOption('utf8', isset($options['utf8']) ? true : false);
        $this->setOption('cron', isset($options['cron']) ? $options['cron'] : 0);
        $this->setOption('delete', isset($options['delete']) ? true : false);
        $this->setOption('delete_days', isset($options['delete_days']) ? (int)$options['delete_days'] : 0);
        $this->setOption('delete_copies', isset($options['delete_copies']) ? (int)$options['delete_copies'] : 0);

        if (self::getOption('delete_copies') === 0 && self::getOption('delete_days') === 0) {
            $this->setOption('delete', 0);
        }

        if (isset($options['cron']) && $options['cron'] !== self::getOption('cron')) {
            /**
             * @var $cronService CronService
             */
            $cronService = $this->container->get(CronService::class);
            $cronService->clearSchedule();
        }
    }
}