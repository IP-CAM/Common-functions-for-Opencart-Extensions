<?php
/*
 *  location: catalog/model
 *
 */
class ModelExtensionProPatchSetting extends Model
{
    function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model('setting/setting');
    }

    public function getSetting($type, $code, $store_id = 0)
    {
        $config_filename = $this->getConfigFilename($code);

        if ($config_filename) {
            $this->config->load($config_filename);
        }

        $setting = ($this->config->get($code)) ? $this->config->get($code) : array();

        $setting_key = $this->getSettingKey($type, $code);
        $store_setting = $this->model_setting_setting->getSetting($setting_key, $store_id);

        foreach ($store_setting as $k => $v) {
            $key = str_replace("{$setting_key}_", "", $k);
            $store_setting[$key] = $v;
            unset($store_setting[$k]);
        }

        if (!empty($store_setting)) {
            $setting = array_replace_recursive($setting, $store_setting);
        }

        return $setting;
    }

    private function getConfigFilename($code)
    {
        if (file_exists(DIR_SYSTEM . "config/{$code}.php")) {
            return $code;
        }

        return false;
    }

    private function getSettingKey($type, $code)
    {
        if (VERSION >= '3.0.0.0') {
            return "{$type}_{$code}";
        } else {
            $code;
        }
    }
}
