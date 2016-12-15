# Getting Started With Gulp

This is a little technical. You'll have to use the command line. If you
haven't done that very much you can still follow these instructions and get
started. The command line is cool, kids.

## Install `nvm` (Node Version Manager)

Gulp requires Node Package Manager (`npm`), but if you're not careful, you can
get yourself into trouble with `npm` on a Mac.

Therefore we recommend you install [nvm (Node Version
Manager)](http://yoember.com/nodejs/the-best-way-to-install-node-js/) first.

Follow the instructions in the link above, then come back.

## Install Gulp

You should now have `nvm` *and* `npm` installed. To install Gulp, just run this
command:

```
npm install --global gulp-cli
```

Hopefully that all went smoothly. If not, see Tony.


## Using Gulp

Now that you've got Gulp installed, you'll never have to go through all that
again...unless you get a new computer or something.

To use Gulp for this project you'll have to `cd` (change directory) into
wherever you cloned this repository. I keep my stuff in a "Projects" directory
in my home folder.

Pro tip: `~` is an alias for your home folder in any Unix-like system,
including OS X or any flavor of Linux. (The more you know...)

So for me to get to my Projects directory inside my home directory, I just
enter `cd ~/Projects` in the command line.

My clone of this project is in `~/Projects/cehd-app_routing`, so I'm going to
enter `cd ~/Projects/cehd-app_routing` to get where I need to be to use Gulp.

After you've found your way into the root directory for this project, run
`gulp`. Hopefully a whole bunch of stuff happened that looks something like
this:

```
[12:02:02] Using gulpfile ~/Projects/cehd-app_routing/gulpfile.js
[12:02:02] Starting 'default'...
[12:02:02] Starting 'javascript'...
[12:02:02] Finished 'default' after 32 ms
[12:02:06] Finished 'javascript' after 3.11 s
[12:02:06] Starting 'sass-minified-task'...
[12:02:06] Finished 'sass-minified-task' after 105 ms
[12:02:06] Starting 'sass-expanded-task'...
[12:02:06] Finished 'sass-expanded-task' after 72 ms
[12:02:06] Starting 'copy-views'...
[12:02:06] Finished 'copy-views' after 9.07 ms
```

Excellent! We're on our way. But what just happened?

Gulp ran a series of tasks that are predefined in a `gulpfile.js` file.

To run all of those tasks you just run the `gulp` command like we just did.
Sometimes that's handy sometimes not. You can run tasks individually as you
need them by running `gulp <task-name>`.

Let's say I made a small change to a Sass file and I want to generate a new
minified version of the CSS to test in a browser. Just run `gulp
sass-minified-task`.

Similarly, if I make a small JavaScript tweak and need to regenerated the
JavaScript file we're using, run `gulp javascript`.

The tasks are defined. Gulp knows what to do.

`gulp watch` is very handy if you want it to manage these tasks automatically
while you work.
