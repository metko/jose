# Quicks Started 

## Intallation

```bash
composer require metko/jose
```
 
You can also create a fresh project with bedorck and symfony encore built in. You can in this case pass directly to the configuration section.


## Structure

After you installed Jose, you should add a new folder called `/app` at the root of your bedrock project.

Add a configuration file `config.php` ( You can find the full configuration here).
Create a `context.php` for passing anything to all your views. (Useful for footer static info, logo etc...).
Create a `Views` folders that will contain all your twig file. (You can change it intp the config.php);
Create a `Models` folders for your models, terms and taxonomy.

> TODO: Automate the configuration generation after install

Your folder should look like this now:

```three
├── app 
|   ── Views
|       ├── views.twig
|   ├── Models
|       ├── BookModel.php
|   ├── config.php
|   └── context.php
├── config              // Bedrock configuration folder
├── web                 // Bedrock app folder
├── .env
├── ...etc
├──
```


