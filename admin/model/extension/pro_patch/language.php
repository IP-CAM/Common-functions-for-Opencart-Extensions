<?php
/*
 *  location: admin/model
 *
 */
class ModelExtensionProPatchLanguage extends Model
{
    public function loadStrings($strings)
    {
        $result = array();

        if (is_array($strings)) {
            foreach ($strings as $string) {
                $result[$string] = $this->language->get($string);
            }
        }

        return $result;
    }
}
