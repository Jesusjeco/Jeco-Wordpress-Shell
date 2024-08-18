Developer oriented Wordpress starter theme.
It offers you a folder structure, already connected with Gulp as a task manager and a basic file structure to jump directly into coding and adding the blocks required for any proyect.

It has a basic hello-world already added to be used as an starting point.
It also includes a list of my suggested plugins.
# Steps

1- Once you cloned the repo, activate the plugins. It is recommended to activate one by one so you can apply the configuration individually.

2- Activate the theme JECO-Wordpress-Shell

3- In your code editor, go to the themes folder and then to the JECO-Wordpress-Shell folder.

4- Time to setup the environment

# Environment

1- be sure you are using a valid node js version. Run the command 
```
nvm use
```

2- run de commando
```
npm i
```

3- run the command gulp
```
gulp
```

At this point the theme files are compiled and ready to be used. You are now set up to use Wordpress with a default configuration.
However, as a developer, you will be looking to create your own blocks.

For that, go to the blocks/ located in the root of the theme JECO-Wordpress-Shell

There you will find a simple block file. You are free to use it, but it is there only for visual purposes.

Create a folder with the name of your block, and create a .php and a .scss with the same name.

In order to register the block, you will need to use the ACF-extended plugin.

Please follow the instruction in the documentation of the plugin https://www.acf-extended.com/features/modules/dynamic-block-types

Once the block has been registered, you will see your block available in the Gutenberg editor.

Now you can use the ACF to add all the fields and functionalities you need. 

At last, run the gulp command again to compile your SCSS and make it available.

You are all set.

# Notes
If you are going to do massive changes, you have the 
```
gulp watcher
``` 
command available

