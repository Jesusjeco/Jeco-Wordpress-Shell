The objective of this repository is to provide a basic starting shell to start developing your wordpress installation.

The repositori comes with the suggested wordpress and they will be changing depending on the new tendencies in security and settings.

#Steps

1- Once you cloned the repo, activate the plugins. It is recommended to acivated one by one so you can apply the configuration individually.

2- Activate the theme JECO-Wordpress-Shell

3- In your code editor, go to the themes folder and then to the JECO-Wordpress-Shell folder.

4- Time to setup the enviroment

#Enviroment

1- be sure you are using a valid node js version. Run the command 
```
nvm use
```

2- run de commando
```
npm i
```

3- run de command gulp
```
gulp
```

At this point the theme files are compiles and ready to be used. You are now setup to use Wordpress with a default configuration.
However, as a developer, you will be looking to create your own blocks.

For that, go to the blocks/ located in the root of the theme JECO-Wordpress-Shell

There you will find a simple block file. You are free to use it, but it is there only for visual porpuses.

Create a folder with the name of your block, and create a .php and a .scss with the same name.

In order to register the block, you will need to use the ACF-extended plugin.

Please follow the instruction in the documentation of the plugin https://www.acf-extended.com/features/modules/dynamic-block-types

Once the block has been registered, you will see your block available in the Gutenberg editor.

Now you can use the ACF to add all the fields and functionalities you need. 

At last, run the gulp command again to compile your SCSS and make it available.

You are all set.

#Notes
If you are going to do massive changes, you have the ```gulp watcher``` command available
