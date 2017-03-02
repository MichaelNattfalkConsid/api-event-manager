<?php

namespace HelsingborgsStad;

class AcfExportManager
{
    protected $exportFolder;
    protected $exportPosts = array();
    protected $textdomain;

    public function __construct()
    {
        add_action('acf/update_field_group', array($this, 'export'));
        add_action('acf/delete_field_group', array($this, 'deleteExport'));
        add_filter('acf/translate_field', array($this, 'translateFieldParams'));
    }

    /**
     * Import (require) acf export files
     * @return boolean
     */
    public function import() : bool
    {
        $files = glob($this->exportFolder . 'php/' . '*.php');

        if (empty($files)) {
            return false;
        }

        foreach ($files as $file) {
            require_once $file;
        }

        return true;
    }

    /**
     * Deletes export file for deleted fieldgroup
     * @param  array $fieldgroup
     * @return boolean
     */
    public function deleteExport(array $fieldgroup) : bool
    {
        $filename = $this->getExportFilename($fieldgroup);

        if (file_exists($this->exportFolder . $filename['php'])) {
            unlink($this->exportFolder . 'php/' . $filename['php']);
        }

        if (file_exists($this->exportFolder . $filename['json'])) {
            unlink($this->exportFolder . 'json/' . $filename['json']);
        }

        return true;
    }

    /**
     * Export all fieldgroups in exportPosts list
     * @return void
     */
    public function exportAll()
    {
        foreach ($this->exportPosts as $post) {
            $this->export(acf_get_field_group($post));
        }
    }

    /**
     * Does the actual export of the php fields
     * @param  array $fieldgroup  Fieldgroup data
     * @return array              Paths to exported files
     */
    public function export(array $fieldgroup) : array
    {
        // Bail if the fieldgroup shouldn't be exported
        if (!in_array($fieldgroup['ID'], $this->exportPosts)) {
            return array();
        }

        $this->maybeCreateExportFolders();

        if ($this->textdomain) {
            acf_update_setting('l10n', true);
            acf_update_setting('l10n_textdomain', $this->textdomain);
            acf_update_setting('l10n_var_export', true);
        }

        $filename = $this->getExportFilename($fieldgroup);

        // Export php file
        $code = $this->generatePhp($fieldgroup['ID']);
        $phpFile = fopen($this->exportFolder . 'php/' . $filename['php'], 'w');
        fwrite($phpFile, $code);
        fclose($phpFile);

        // Export json file
        $jsonFile = fopen($this->exportFolder . 'json/' . $filename['json'], 'w');
        $json = $this->getJson($this->getFieldgroupParams($fieldgroup['ID'], false));
        fwrite($jsonFile, $json);
        fclose($jsonFile);

        return array(
            'php' => $this->exportFolder . 'php/' . $filename['php'],
            'json' => $this->exportFolder . 'json/' . $filename['json']
        );
    }

    /**
     * Get fieldgroup as json
     * @param  array $fieldgroup
     * @return string
     */
    public function getJson(array $fieldgroup) : string
    {
        $json = json_encode($fieldgroup, JSON_PRETTY_PRINT);
        $json = str_replace('!!__(!!\'', '', $json);
        $json = str_replace("!!', !!'" . $this->textdomain . "!!')!!", '', $json);

        return '[' . $json . "]\n\r";
    }

    /**
     * Creates export folders if needed
     * @return void
     */
    public function maybeCreateExportFolders()
    {
        if (!file_exists($this->exportFolder . 'json')) {
            mkdir($this->exportFolder . 'json');
            chmod($this->exportFolder . 'json', 0777);
        }

        if (!file_exists($this->exportFolder . 'php')) {
            mkdir($this->exportFolder . 'php');
            chmod($this->exportFolder . 'php', 0777);
        }
    }

    /**
     * Get filename for the export file
     * @param  array $fieldgroup Fieldgroup data
     * @return array
     */
    public function getExportFilename(array $fieldgroup) : array
    {
        if ($key = array_search($fieldgroup['ID'], $this->exportPosts)) {
            return rtrim($key, '.php') . '.php';
        }

        return array(
            'php' => sanitize_title($fieldgroup['title']) . '.php',
            'json' => sanitize_title($fieldgroup['title']) . '.json'
        );
    }

    /**
     * Generates PHP exportcode for a fieldgroup
     * @param  int    $fieldgroupId
     * @return string
     */
    protected function generatePhp(int $fieldgroupId) : string
    {
        $strReplace = array(
            "  "      => "    ",
            "!!\'"    => "'",
            "'!!"     => "",
            "!!'"     => "",
            "array (" => "array(",
            " => \n" => " => "
        );

        $pregReplace = array(
            '/([\t\r\n]+?)array/'   => 'array',
            '/[0-9]+ => array/'     => 'array',
            '/=>(\s+)array\(/'       => '=> array('
        );

        $fieldgroup = $this->getFieldgroupParams($fieldgroupId);

        $code = var_export($fieldgroup, true);
        $code = str_replace(array_keys($strReplace), array_values($strReplace), $code);
        $code = preg_replace(array_keys($pregReplace), array_values($pregReplace), $code);

        $export = "<?php \n\r\n\rif (function_exists('acf_add_local_field_group')) {\n\r";
        $export .= "    acf_add_local_field_group({$code});";
        $export .= "\n\r}";

        acf_update_setting('l10n_var_export', false);

        return $export;
    }

    /**
     * Get exportable fieldgroup params
     * @param  int    $fieldgroupId
     * @return array
     */
    public function getFieldgroupParams(int $fieldgroupId, bool $translate = true) : array
    {
        // Get the fieldgroup
        $fieldgroup = acf_get_field_group($fieldgroupId);

        // Bail if fieldgroup is empty
        if (empty($fieldgroup)) {
            trigger_error('The fieldgroup with id "' . $fieldgroupId . '" is empty.', E_USER_WARNING);
            return array();
        }

        // Get the fields in the fieldgroup
        $fieldgroup['fields'] = acf_get_fields($fieldgroup);

        // Translate
        if ($translate) {
            $fieldgroup = $this->translate($fieldgroup);
        }

        // Preapre for export
        return acf_prepare_field_group_for_export($fieldgroup);
    }

    /**
     * Translate fieldgroup
     * @param  array  $fieldgroup
     * @return array
     */
    public function translate(array $fieldgroup) : array
    {
        foreach ($fieldgroup['fields'] as &$field) {
            $field = acf_translate_field($field);
        }

        return $fieldgroup;
    }

    /**
     * Translate field params
     * @param  array $field  ACF Field params
     * @return array         Translated ACF field params
     */
    public function translateFieldParams(array $field) : array
    {
        $keys = array('prepend', 'append', 'placeholder');

        foreach ($keys as $key) {
            if (!isset($field[$key])) {
                continue;
            }

            $field[$key] = acf_translate($field[$key]);
        }

        if (isset($field['sub_fields'])) {
            foreach ($field['sub_fields'] as &$subfield) {
                $subfield = acf_translate_field($subfield);
            }
        }

        return $field;
    }

    /**
     * Wraps strings in translation function __()
     * @param  array  $fieldgroup
     * @return array
     */
    public function translateFieldgroup(array $fieldgroup) : array
    {
        $l10nBefore = acf_get_setting('l10n');
        $l10nVarExportBefore = acf_get_setting('l10n_var_export');
        $l10nTextdomainBefore = acf_get_setting('l10n_textdomain');

        acf_update_setting('l10n', true);
        acf_update_setting('l10n_var_export', true);
        acf_update_setting('l10n_textdomain', $this->textdomain);

        $fieldgroup['title'] = acf_translate($fieldgroup['title']);

        foreach ($fieldgroup['fields'] as &$field) {
            $keys = array(
                'default_value',
                'placeholder',
                'button_label',
                'append',
                'prepend',
                'label',
                'instructions',
                'choices',
                'sub_fields'
            );

            foreach ($keys as $key) {
                if (!isset($field[$key])) {
                    continue;
                }

                $field[$key] = acf_translate($field[$key]);
            }
        }

        acf_update_setting('l10n', $l10nBefore);
        acf_update_setting('l10n_var_export', $l10nVarExportBefore);
        acf_update_setting('l10n_textdomain', $l10nTextdomainBefore);

        return $fieldgroup;
    }

    /**
     * Set exports folder
     * @param string      $folder  Path to exports folder
     * @return void
     */
    public function setExportFolder(string $folder)
    {
        $folder = trailingslashit($folder);

        if (!file_exists($folder)) {
            if (!mkdir($folder)) {
                trigger_error('The export folder (' . $folder .') can not be found. Exports will not be saved.', E_USER_WARNING);
            } else {
                chmod($folder, 0777);
            }
        }

        if (!is_writable($folder)) {
            trigger_error('The export folder (' . $folder .') is not writable. Exports will not be saved.', E_USER_WARNING);
        }

        $this->exportFolder = $folder;
    }

    /**
     * Sets which acf-fieldgroups postids to autoexport
     * @param  array  $ids
     * @return void
     */
    public function autoExport(array $ids)
    {
        $this->exportPosts = array_replace($this->exportPosts, $ids);
        $this->exportPosts = array_unique($this->exportPosts);
    }

    /**
     * Sets the textdomain to use for field translations
     * @param string $textdomain
     */
    public function setTextdomain(string $textdomain)
    {
        $this->textdomain = $textdomain;
    }
}