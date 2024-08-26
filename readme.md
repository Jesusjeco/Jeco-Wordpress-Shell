# Jeco Wordpress Shell
Developer oriented Wordpress starter theme.
It offers you a folder structure, already connected with Gulp as a task manager and a basic file structure to jump directly into coding and adding the blocks required for any proyect.

It has a basic hello-world already added to be used as an starting point.
It also includes a list of my suggested plugins.

## Notes
The files used are NOT an obligation. As a developer feel free to destroy/adjust all you need. The purpose of this theme is NOT to force a UX/UI but to offer an initial structure to save time.
I am adding some default styles but those are mostly to give you a starting point. Every single style file that you should need, is located in the src/ folder. All the names are pretty self explanatory 

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


3- With gulp installed locally and globally, run once the command `gulp` to be sure that it compiles all the files.
```
gulp
```

At this point the theme files are compiled and ready to be used. You are now set up to use Wordpress with a default configuration.
However, as a developer, you will be looking to create your own blocks.

## Adding Blocks

We all have our ways.
For this theme I am letting a copy of a block registration in the theme folder/includes/blocks-setup/register-blocks.php.
Right there you will see the code to register the hello-world I have as a default. You can copy and use it for your own blocks.

Also, there is the register-styles.php. You can use it for the regsitration of the styles. Using that strucutre, the style files are going to be print in the head, instead of the footer and only if the current view is using the specific block you are setting up.

# Notes
As developers, you will not want to run the `gulp` command everytime. For that you can use the watcher functionality and it will re-compile your changes while you are working.
```
gulp watcher
``` 

## Next steps for the future
My plan is to optimize this theme structure and make it Woocommerce available, to also have basic blocks that uses the Woocommerce API in order to let all the tools available for developers to only work in the HTML and CSS of the Woocommerce pages, and let all the other functionalities ready to go, such as Purchase Cart and Payment integrations