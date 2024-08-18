# Jeco Wordpress Shell
Developer oriented Wordpress starter theme.
It offers you a folder structure, already connected with Gulp as a task manager and a basic file structure to jump directly into coding and adding the blocks required for any proyect.

It has a basic hello-world already added to be used as an starting point.
It also includes a list of my suggested plugins.

## Steps
1- Once you cloned the repo, activate the plugins. It is recommended to activate one by one so you can apply the configuration individually and according to your taste.
2- Activate the theme Jeco-Wordpress-Shell
3- In your code editor, go to the themes folder and then to the Jeco-Wordpress-Shell folder.
4- Now that you have activated the theme, lets go with the environment

## Environment
1- open the terminal in the theme's folder to run the commands coming up.

2- Making sure you are using the right Node version. You can take a look at the `.nvmrc` file and install the coresponding Node version, but I suggest you use NVM and run the command.
```
nvm use
```

2- Lets run npm i to setup all the dependencies.
```
npm i
```

3- PROBABLY you do not have Gulp installed globally. So lets be sure you do. If you want to follow the instructions from the main site, here it is[text](https://gulpjs.com/docs/en/getting-started/quick-start) 

```
npm install --global gulp-cli
```
and then
```
npm install --save-dev gulp
```


3- With gulp installed locally and globally, run once the command 'gulp' to be sure that it compiles all the files.
```
gulp
```

At this point the theme files are compiled and ready to be used. You are now set up to use Wordpress with a default configuration.
However, as a developer, you will be looking to create your own blocks.

## Adding Blocks

To start adding your own blocks, go to the folder blocks/ located in the root of the theme.

There you will find a simple hello-world block as an example. You are free to use it, but it is there only for visual purposes and to offer a basic example of the setup.

In my example I am using render.php as the render file and the style.scss for the styling. But there is no naming convention that you need to stick to it. Feel free.

Create a folder with the name of your block, and create your .php and .scss files.

In order to register the block, you will need to use the ACF-extended plugin.

Please follow the instruction in the documentation of the plugin https://www.acf-extended.com/features/modules/dynamic-block-types

Once the block has been registered, you will see your block available in the Gutenberg editor.

Now you can use the ACF to add all the fields and functionalities you need. 

At last, run the gulp command again to compile your SCSS and make it available.

You are all set.

# Notes
As developers, you will not want to run the `gulp` command everytime. For that you can use the watcher functionality and it will re-compile your changes while you are working.
```
gulp watcher
``` 

## Next steps for the future
My plan is to optimize this theme structure and make it Woocommerce vailable, to also have basic blocks that uses the Woocommerce API in order to let all the tools available for developers to only work in the HTML and CSS of the Woocommerce pages, and let all the other functionalities ready to go, such as Purchase Cart and Payment integrations