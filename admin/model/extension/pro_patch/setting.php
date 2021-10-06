<?php
/*
 *  location: admin/model
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

    public function editSetting($type, $code, $data, $store_id = 0)
    {
        $setting_key = $this->getSettingKey($type, $code);

        if (VERSION >= '3.0.0.0') {

            $updated_data = array();
            foreach ($data as $k => $v) {
                $updated_data["{$setting_key}_{$k}"] = $v;
            }

            $this->model_setting_setting->editSetting($setting_key, $updated_data, $store_id);

        } else {
            $this->model_setting_setting->editSetting($setting_key, $data, $store_id);
        }
    }

    public function deleteSetting($code, $store_id = 0)
    {
        $this->model_setting_setting->deleteSetting($code, $store_id);
    }

    public function editSettingValue($code = '', $key = '', $value = '', $store_id = 0)
    {
        $this->model_setting_setting->deleteSetting($code, $key, $value, $store_id);
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
