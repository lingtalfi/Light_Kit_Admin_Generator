Light_Kit_Admin_Generator
===========
2019-11-06



A plugin to help creating an auto-admin in [Light_Kit_Admin](https://github.com/lingtalfi/Light_Kit_Admin). 

This is a [Light plugin](https://github.com/lingtalfi/Light/blob/master/doc/pages/plugin.md).

This is part of the [universe framework](https://github.com/karayabin/universe-snapshot).


Install
==========
Using the [uni](https://github.com/lingtalfi/universe-naive-importer) command.
```bash
uni import Ling/Light_Kit_Admin_Generator
```

Or just download it and place it where you want otherwise.









Summary
===========
- [Light_Kit_Admin_Generator api](https://github.com/lingtalfi/Light_Kit_Admin_Generator/blob/master/doc/api/Ling/Light_Kit_Admin_Generator.md) (generated with [DocTools](https://github.com/lingtalfi/DocTools))
- Pages
    - [Conception notes](https://github.com/lingtalfi/Light_Kit_Admin_Generator/blob/master/doc/pages/conception-notes.md)
    - [Configuration example](https://github.com/lingtalfi/Light_Kit_Admin_Generator/blob/master/doc/pages/lkagen-configuration-example.md)

- [Services](#services)
- [Usage example](#usage-example)



Services
=========


This plugin provides the following services:

- kit_admin_generator (returns a LightKitAdminGeneratorService instance)


Here is an example of the service configuration:

```yaml
kit_admin_generator:
    instance: Ling\Light_Kit_Admin_Generator\Service\LightKitAdminGeneratorService
    methods:
        setContainer:
            container: @container()



```



Usage example
=============

```php
$configFile = $appDir . '/config/data/Light_Kit_Admin/Light_Kit_Admin_Generator/jindemo.byml';
az($container->get("kit_admin_generator")->generate($configFile));
```





History Log
=============

- 1.2.0 -- 2019-11-25

    - update controller.tpl now accepts a $_GET.solo option 
    
- 1.1.1 -- 2019-11-19

    - fix baseController.tpl not handling ric update in the url correctly
    
- 1.1.0 -- 2019-11-14

    - update ControllerGenerator, implemented form.use_link_to_list

- 1.0.1 -- 2019-11-06

    - fix link typo in example
    
- 1.0.0 -- 2019-11-06

    - initial commit