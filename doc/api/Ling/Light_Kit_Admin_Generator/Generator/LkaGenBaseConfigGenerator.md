[Back to the Ling/Light_Kit_Admin_Generator api](https://github.com/lingtalfi/Light_Kit_Admin_Generator/blob/master/doc/api/Ling/Light_Kit_Admin_Generator.md)



The LkaGenBaseConfigGenerator class
================
2019-11-06 --> 2019-11-14






Introduction
============

The LkaGenBaseConfigGenerator class.



Class synopsis
==============


class <span class="pl-k">LkaGenBaseConfigGenerator</span> extends [BaseConfigGenerator](https://github.com/lingtalfi/Light_RealGenerator/blob/master/doc/api/Ling/Light_RealGenerator/Generator/BaseConfigGenerator.md)  {

- Inherited properties
    - protected [Ling\Light\ServiceContainer\LightServiceContainerInterface](https://github.com/lingtalfi/Light/blob/master/doc/api/Ling/Light/ServiceContainer/LightServiceContainerInterface.md) [BaseConfigGenerator::$container](#property-container) ;
    - protected array [BaseConfigGenerator::$config](#property-config) ;

- Methods
    - protected [getRouteNameByTable](https://github.com/lingtalfi/Light_Kit_Admin_Generator/blob/master/doc/api/Ling/Light_Kit_Admin_Generator/Generator/LkaGenBaseConfigGenerator/getRouteNameByTable.md)(string $table, array $config, ?bool $isListRoute = true) : string

- Inherited methods
    - public BaseConfigGenerator::__construct() : void
    - public BaseConfigGenerator::setContainer([Ling\Light\ServiceContainer\LightServiceContainerInterface](https://github.com/lingtalfi/Light/blob/master/doc/api/Ling/Light/ServiceContainer/LightServiceContainerInterface.md) $container) : void
    - protected BaseConfigGenerator::getTables() : array
    - protected BaseConfigGenerator::getKeyValue(string $keyPath, ?bool $throwEx = true, ?$default = null) : array | mixed | null
    - protected BaseConfigGenerator::setConfig(array $config) : void
    - protected BaseConfigGenerator::getGenericTagsByTable(string $table) : array
    - protected BaseConfigGenerator::getTableWithoutPrefix(string $table) : string

}






Methods
==============

- [LkaGenBaseConfigGenerator::getRouteNameByTable](https://github.com/lingtalfi/Light_Kit_Admin_Generator/blob/master/doc/api/Ling/Light_Kit_Admin_Generator/Generator/LkaGenBaseConfigGenerator/getRouteNameByTable.md) &ndash; Returns the route name based on the given table.
- BaseConfigGenerator::__construct &ndash; Builds the ListConfigGenerator instance.
- BaseConfigGenerator::setContainer &ndash; Sets the container.
- BaseConfigGenerator::getTables &ndash; Returns the tables to generate a config file for.
- BaseConfigGenerator::getKeyValue &ndash; Returns the value associated with the given keyPath.
- BaseConfigGenerator::setConfig &ndash; Sets the [configuration block](https://github.com/lingtalfi/Light_Kit_Admin_Generator/blob/master/doc/pages/lkagen-configuration-example.md).
- BaseConfigGenerator::getGenericTagsByTable &ndash; Returns the array of generic tags (used in the list and form configuration files), based on the given table.
- BaseConfigGenerator::getTableWithoutPrefix &ndash; Returns the table name without prefix.





Location
=============
Ling\Light_Kit_Admin_Generator\Generator\LkaGenBaseConfigGenerator<br>
See the source code of [Ling\Light_Kit_Admin_Generator\Generator\LkaGenBaseConfigGenerator](https://github.com/lingtalfi/Light_Kit_Admin_Generator/blob/master/Generator/LkaGenBaseConfigGenerator.php)



SeeAlso
==============
Previous class: [DeprecatedRouteGenerator](https://github.com/lingtalfi/Light_Kit_Admin_Generator/blob/master/doc/api/Ling/Light_Kit_Admin_Generator/Generator/DeprecatedRouteGenerator.md)<br>Next class: [MenuConfigGenerator](https://github.com/lingtalfi/Light_Kit_Admin_Generator/blob/master/doc/api/Ling/Light_Kit_Admin_Generator/Generator/MenuConfigGenerator.md)<br>