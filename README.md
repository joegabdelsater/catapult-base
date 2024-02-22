# Requirements
This package supports Laravel version 10.

# Installation

### This package should always be added as a dev dependency. It should never be used in a production environement.

To install the package:

```
composer require --dev joegabdelsater/catapult-base
```

Then pusblish the javascript assets:
```
php artisan vendor:publish --tag=catapult-base
```

And finally:

```
php artisan migrate
```

You can then navigate to the ```/catapult``` url to access the catapult dashboard.
