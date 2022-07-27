## Fabio Import

### Cli for import csv or json customer files

### usage:
- install module
    - composer require efepimenta/module-import ^1.0
- run bin/magento setup:upgrade
- run bin/magento setup:di:compile
- run bin/magento customer:import <TYPE> <FILE>
    - TYPE must be csv or json
    - FILE is the absolute or relative file path
- see customer on magento admin panel

### validations
- for import type
- for file mimetype
- for import type X file mimetype
- headers of files
- if customer email exists, customer is updated

### logs
- fabio_import.log is generated in var/log
- some data is logged in
