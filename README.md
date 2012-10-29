Composer Command Integrator
===========================

Extending the functionality of Composer is not that easy.
There are several points, which make it harder to add functionality by external libraries.

This library adds some example code, how to add scripts, which are able to use composers internal structure.
This could be wanted for custom installers or module managers, which use composer as base
and want to offer additional commands which need things like information about libraries
installed with composer.

Also this library offers a simple API to add own Commands similar to the Composer ones.
Only difference is, we have an own script as entry point, which needs to be used.

All Commands added to the extra.composer-command-registry in your libraries composer.json get added to this entry point.

```json

    "extra":{
        "composer-command-registry": [
        "MagentoHackathon\\Composer\\Magento\\Command\\DeployCommand"
         ]
    }

```

As you see, this is an Array, so you can add more than one command per module.

You can execute them in your project with:

```

    php ./vendor/bin/composerCommandIntegrator.php

```



Projects wich implemented this
------------------------------

* [Magento Composer Installer](https://github.com/magento-hackathon/magento-composer-installer) - installer for Magento Modules via Composer