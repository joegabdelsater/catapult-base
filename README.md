# Very Important
- This package is only meant to be used in development. Never use this package in production.
- Only use catapult on a fresh project. Otherwise it will overwrite your existing models, controllers, migrations and validation requests.

# Description

The purpose of this package is to help you setup your project as quickly as possible. You can install packages, create models, migrations, validation requests, controllers and routes through a dev-friendly GUI.
This minimizes the time spent doing redundant tasks, and let's you focus on the brain intensive logic building part of the project.

# Requirements
This package supports Laravel version 10.

# Installation

### This package should always be added as a dev dependency. It should never be used in a production environement.

To install the package:

```
composer require --dev joeabdelsater/catapult-base
```

Run the package migrations:
```
php artisan migrate
```

Finally, run the package installation script. This will publish javascript assets and create an entry for the already existing user model in your application:
```
php artisan catapult:install
```


You can now navigate to the ```/catapult``` url to access the catapult dashboard.

# How to use

## Setup the project
- First select the packages you need for your project, hit save and then follow the instructions shown on the screen.

## Setup the models
- Create the models by writing the model's name, selecting the configuration you wish to have on this model and hitting save.
- At this point, if you haven't clicked on generate next to the models, the configuration is only saved to the database, and your model files are not yet generated.
- When done, you can either generate the model files now, or wait to setup the relationships, and then generate the models.
- If you generate the models, then change something in your relationships, an alert will show next to the models so you know you need to regenerate them.



## Setup the relationships:
- Choose the model you want to create relationships for.
- Drag and drop the models from the models list on the right inside the type of relationship you wish to have.
- Fill any parameters you wish to fill, or customize any of the suggestions created.
- Hit save to save the relationships to the database. At this point, they are not yet generated.
- Once done, go back to the models section, and regenerate your models for the changes to apply



## Creating the migrations and validation requests:
- Select the model you wish to generate a migration for.
- The class shown in the editor uses ```CatapultSchema``` instead of the regular ```Schema``` class. DO NOT MODIFY IT.
- Write the migration code you normally write in your laravel migrations.
- if you wish to create validation on a specific field, chain the `->validation('')` method to your code.
  

```
  $table->string('first_name')->nullable()->validation('nullable|max:255');

  // Make sure you chain it right after foreignId('user_id')
  /** CORRECT */
  $table->foreignId('user_id')->validation('required|exists:users,id')->onDelete('cascade');

  /** WRONG */
  $table->foreignId('user_id')->onDelete('cascade')->validation('required|exists:users,id');




 /** CORRECT */
  $table->unsignedBigInteger('user_id')->validation('required|exists:users,id'); 
  $table->foreign('user_id')->references('users')->on('id')->onDelete('cascade');

  /** WRONG */
  $table->unsignedBigInteger('user_id'); 
  $table->foreign('user_id')->validation('required|exists:users,id')->references('users')->on('id')->onDelete('cascade');

  
```

- Hit Save & Generate the migrations.
- Sometimes you might save migrations in the wrong order before generating, that's okay. Catapult will show you a warning on the migration before you generate it in case you should generate another migration before it.




## Create the controllers.
- This should be straight forward. Just create the controllers by writing their names and hitting save.
- Click on generate to create the files.



## Setup the controller routes
- Select type of route on controller you wish to create routes for (Web or API). 
- Find the route method you wish to have, and fill in the information in the relevant form.
- Hit save and make sure to generate the routes.
- Once the routes are generated, you should regenerate the controllers to generate the methods you defined in the routes for each controller.
- Generated routes can be found in the ```/routes/catapult``` folder in your project's root. These files will be loaded in your web.php and api.php. Feel free to move them 
