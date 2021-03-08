# Quicks Started 

## Intallation

In your project run:
```bash
composer require metko/jose
```

If you start a nex project, you can use the starter pack based on bedrock. A Wordplate version is comming soon. 
```bash
composer create-project metko/jose-wp --dist bedrock

// or

composer create-project metko/jose-wp --dist wordplate
```



## Structure

> more docs comming here 

Jose allow you to decide your own structure. But we recomand to create a jose directory at your project root and put all the file inside.

Assuming we are in a Bedrock structure: 
```three
├── jose 
|   ├── Assets
|       ├── all your webpack stuff here
|   ├── Views
|       ├── views.twig
|   ├── PostType
|       ├── BookModel.php
|   ├── Taxonomies
|       ├── GenreTaxonomy.php
|   ├── Blocks
|       ├── custom-block.php
|           ├── custom-block.php
|           └── fields-custom-block.php
|   └── context.php
├── config              // Bedrock configuration folder
|   ── ...other config
|   ── jose.php
├── web                 // Bedrock app folder
├── .env
├── ...etc
├──
```


