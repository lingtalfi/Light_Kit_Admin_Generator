<?php


namespace Ling\Light_Kit_Admin_Generator\Light_PlanetInstaller;

use Ling\CliTools\Output\OutputInterface;
use Ling\Light_Logger\Helper\LightLoggerHelper;
use Ling\Light_PlanetInstaller\PlanetInstaller\LightBasePlanetInstaller;
use Ling\Light_PlanetInstaller\PlanetInstaller\LightPlanetInstallerInit2HookInterface;

/**
 * The LightKitAdminGeneratorPlanetInstaller class.
 */
class LightKitAdminGeneratorPlanetInstaller extends LightBasePlanetInstaller implements LightPlanetInstallerInit2HookInterface
{

    /**
     * @implementation
     */
    public function init2(string $appDir, OutputInterface $output, array $options = []): void
    {

        $planetDotName = "Ling.Light_Kit_Admin_Generator";

        //--------------------------------------------
        // logger
        //--------------------------------------------
        $output->write("$planetDotName: registering Ling.Light_Logger listeners to open system...");
        LightLoggerHelper::copyListenersFromPluginToMaster($appDir, $planetDotName);
        $output->write("<success>ok.</success>" . PHP_EOL);
    }

    /**
     * @implementation
     */
    public function undoInit2(string $appDir, OutputInterface $output, array $options = []): void
    {
        $planetDotName = "Ling.Light_Kit_Admin_Generator";

        //--------------------------------------------
        // logger
        //--------------------------------------------
        $output->write("$planetDotName: unregistering Ling.Light_Logger listeners from open system...");
        LightLoggerHelper::removeListenersFromMaster($appDir, $planetDotName);
        $output->write("<success>ok.</success>" . PHP_EOL);
    }


}