<?php


namespace Ling\Light_Kit_Admin_Generator\Generator;

use Ling\Bat\CaseTool;
use Ling\Bat\FileSystemTool;

/**
 * The ControllerGenerator class.
 *
 * The philosophy is that this tool is just a basic helper, it helps the developer getting 90% of the way,
 * but there is still work to do for the developer.
 *
 * In other words, this tool doesn't try to fine tune every settings that the developer wish for, but rather
 * helps getting the developer in the ball park.
 *
 * Note to myself: remember this philosophy when extending this class: don't overdo it...
 *
 *
 *
 */
class ControllerGenerator extends LkaGenBaseConfigGenerator
{

    /**
     * Generates the controller classes according to the given @page(configuration block).
     * @param array $config
     * @throws \Exception
     */
    public function generate(array $config)
    {
        $this->setConfig($config);
        $tables = $this->getTables();

        $appDir = $this->container->getApplicationDir();
        $tablePrefixes = $this->getKeyValue("table_prefixes", false, []);
        $classRootDir = $this->getKeyValue("controller.class_root_dir");
        $classRootDir = str_replace('{app_dir}', $appDir, $classRootDir);
        $useCustomController = $this->getKeyValue("controller.use_custom_controller", false, false);
        $controllerClassName = $this->getKeyValue("controller.controller_classname");
        $customControllerClassName = $this->getKeyValue("controller.custom_controller_classname", false, null);
        $baseControllerClassName = $this->getKeyValue("controller.base_controller_classname");
        $parentController = $this->getKeyValue("controller.parent_controller");
        $requestDeclarationIdFmt = $this->getKeyValue("controller.realist_request_declaration_id_format");
        $formTitle = $this->getKeyValue("form.title", false, "{Label} form");

        //--------------------------------------------
        $tplController = file_get_contents(__DIR__ . "/../assets/models/classes/controller.php.tpl");
        $tplCustomController = file_get_contents(__DIR__ . "/../assets/models/classes/customController.php.tpl");
        $tplBaseController = file_get_contents(__DIR__ . "/../assets/models/classes/baseController.php.tpl");
        $tplFormConf = file_get_contents(__DIR__ . "/../assets/models/kit_page_conf/form.byml");
        $tplListConf = file_get_contents(__DIR__ . "/../assets/models/kit_page_conf/list.byml");
        //--------------------------------------------


        foreach ($tables as $table) {


            //--------------------------------------------
            $uses = [];
            $usesCustom = [];
            $tableNoPrefix = $table;
            $p = explode('_', $tableNoPrefix);
            foreach ($tablePrefixes as $prefix) {
                if (0 === strpos($tableNoPrefix, $prefix)) {
                    $tableNoPrefix = substr($tableNoPrefix, strlen($prefix . "_"));
                }
            }
            $tableLabel = str_replace('_', ' ', $tableNoPrefix);
            $TableLabel = ucfirst($tableLabel);
            $Table = CaseTool::toPascal($table);
            $TableNoPrefix = ucfirst($tableNoPrefix);
            $tags = [
                '{Table}' => $Table,
                '{TableNoPrefix}' => $TableNoPrefix,
            ];
            $resolvedControllerClassName = $this->resolveTags($controllerClassName, $tags);
            $p = explode('\\', $resolvedControllerClassName);
            $controllerShortClassName = array_pop($p);
            $controllerNamespace = implode('\\', $p);

            $resolvedBaseControllerClassName = $this->resolveTags($baseControllerClassName, $tags);
            $p = explode('\\', $resolvedBaseControllerClassName);
            $baseControllerShortClassName = array_pop($p);
            $baseControllerNamespace = implode('\\', $p);
            if ($baseControllerNamespace !== $controllerNamespace) {
                $uses[] = "use $resolvedBaseControllerClassName;";
            }
            $sUse = implode(PHP_EOL, $uses);

            if (true === $useCustomController) {
                $resolvedCustomControllerClassName = $this->resolveTags($customControllerClassName, $tags);
                $p = explode('\\', $resolvedCustomControllerClassName);
                $customControllerShortClassName = array_pop($p);
                $customControllerNamespace = implode('\\', $p);
                if ($customControllerNamespace !== $controllerNamespace) {
                    $usesCustom[] = "use $resolvedControllerClassName;";
                }
                $sUseCustom = implode(PHP_EOL, $usesCustom);
            }
            $requestDeclarationId = str_replace('{table}', $table, $requestDeclarationIdFmt);
            $requestDeclarationId = str_replace('{tableNoPrefix}', $tableNoPrefix, $requestDeclarationId);


            $theFormTitle = $formTitle;
            $genericTags = $this->getGenericTagsByTable($table);
            $theFormTitle = str_replace(array_keys($genericTags), array_values($genericTags), $theFormTitle);
            //--------------------------------------------


            //--------------------------------------------
            // CREATING CONTROLLER
            //--------------------------------------------
            $_tplController = str_replace('TheNamespace', $controllerNamespace, $tplController);
            $_tplController = str_replace('//->use', $sUse, $_tplController);
            $_tplController = str_replace('TheController', $controllerShortClassName, $_tplController);
            $_tplController = str_replace('TheBaseController', $baseControllerShortClassName, $_tplController);
            $_tplController = str_replace('{tableLabel}', $tableLabel, $_tplController);
            $_tplController = str_replace('{TableLabel}', $TableLabel, $_tplController);
            $_tplController = str_replace('{table}', $table, $_tplController);
            $_tplController = str_replace('{request_declaration_id}', $requestDeclarationId, $_tplController);
            $_tplController = str_replace('{formTitle}', $theFormTitle, $_tplController);
            $f = $classRootDir . "/" . str_replace('\\', '/', $resolvedControllerClassName) . ".php";
            FileSystemTool::mkfile($f, $_tplController);


            //--------------------------------------------
            // CREATING CUSTOM CONTROLLER
            //--------------------------------------------
            if (true === $useCustomController) {
                $_tplCustomController = str_replace('TheNamespace', $customControllerNamespace, $tplCustomController);
                $_tplCustomController = str_replace('//->use', $sUseCustom, $_tplCustomController);
                $_tplCustomController = str_replace('TheCustomController', $customControllerShortClassName, $_tplCustomController);
                $_tplCustomController = str_replace('TheController', $controllerShortClassName, $_tplCustomController);


                $f = $classRootDir . "/" . str_replace('\\', '/', $resolvedCustomControllerClassName) . ".php";
                /**
                 * Never overwrite a custom file!!
                 */
                if (false === file_exists($f)) {
                    FileSystemTool::mkfile($f, $_tplCustomController);
                }
            }


            //--------------------------------------------
            // CREATE KIT PAGE CONFIGURATION FOR FORM AND LIST
            //--------------------------------------------
            /**
             * So far, we are using hardcoded paths, works fine.
             */
            $kitTags = [
                '{tableLabel}' => $tableLabel,
                '{TableLabel}' => $TableLabel,
            ];
            $pathForm = $appDir . "/config/data/Light_Kit_Admin/kit/zeroadmin/generated/${table}_form.byml";
            $pathList = $appDir . "/config/data/Light_Kit_Admin/kit/zeroadmin/generated/${table}_list.byml";
            $_tplFormConf = $this->resolveTags($tplFormConf, $kitTags);
            $_tplListConf = $this->resolveTags($tplListConf, $kitTags);
            FileSystemTool::mkfile($pathForm, $_tplFormConf);
            FileSystemTool::mkfile($pathList, $_tplListConf);


        }


        //--------------------------------------------
        // CREATING BASE CONTROLLER
        //--------------------------------------------
        $p = explode('\\', $baseControllerClassName);
        $baseControllerShortClassName = array_pop($p);
        $baseControllerNamespace = implode('\\', $p);


        $p = explode('\\', $parentController);
        $parentControllerShortClassName = array_pop($p);
        $parentControllerNamespace = implode('\\', $p);
        $sUse = '';
        if ($parentControllerNamespace !== $baseControllerNamespace) {
            $sUse = "use $parentController;" . PHP_EOL;
        }

        $tplBaseController = str_replace('TheNamespace', $baseControllerNamespace, $tplBaseController);
        $tplBaseController = str_replace('//->use', $sUse, $tplBaseController);
        $tplBaseController = str_replace('TheBaseController', $baseControllerShortClassName, $tplBaseController);
        $tplBaseController = str_replace('TheParentController', $parentControllerShortClassName, $tplBaseController);


        $f = $classRootDir . "/" . str_replace('\\', '/', $baseControllerClassName) . ".php";
        FileSystemTool::mkfile($f, $tplBaseController);
    }



    //--------------------------------------------
    //
    //--------------------------------------------
    /**
     * Replace the tags by their values in the given string, and returns the result.
     *
     * @param string $str
     * @param array $tags
     * @return string
     */
    private function resolveTags(string $str, array $tags): string
    {
        return str_replace(array_keys($tags), array_values($tags), $str);
    }
}