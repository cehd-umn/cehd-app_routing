# Getting Started

* [Twig Templates](templates.md)
* [Sass](sass.md)
* JavaScript
* Gulp
  * [Getting Started](gulp-getting-started.md)

## Sites

* Ed Psych
  * [App](edpsych/)
  * [Templates](edpsych/templates.md)
  * Sass
  * JavaScript

### Assets

There is some complexity with managing CSS and JavaScript assets. This is the
result of:

1. The goal of optimizing page performance
2. The goal of having some level of automation to perform repetitive tasks

Let's take those one at a time.

#### Performance

From the outset we've tried to optimize page speed performance in the
following ways:

1. "Minified" files to reduce file size
2. Fewer network requests for CSS and JavaScript files

We achieve both of these by bundling.

#### Sass

[Sass](http://sass-lang.com/) will bundle multiple files together by using the
`@import` declaration.

```
@import "variables";
```

That declaration loads the `_variables.scss` file where most of our variables
are declared. That way we can organize all the Sass code and include them
all into `edpsych.scss` to be bundled together.

Note the underscore in the `_variables.scss` file name. That's a Sass
convention. Sass knows to skip the underscore in the `@import "variables";`
declaration. Prepend an underscore to any Sass file name when the file is
meant to be imported like this. It groups all those files together.

Another benefit we get from using Sass is minification. We can specify
how Sass should render the CSS. Minification removes all comments and
concatenates all the CSS together on one line to reduce files size as much
as possible. It's not human-readable, but browsers can read it just fine.

[Go here for more details on how we're using Sass](sass.md).

#### Gulp

[Gulp.js](https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md)
is an automation tool. Performance-wise it helps manage the JavaScript and
bundle it together into a single, uglified and miniifed file.

What is uglification, you ask? In addition to minifying, which we covered
above, uglifiers can interpret the code (called *introspection*) and
rename objects and methods. For example:

```
var MyObject {
   mySuperLongMethodNameThatIsVeryDescriptive: function() {
   }
}
```

An uglifier might change that name to something like:

```
var MyObject {
   a: function() {
   }
}
```

It's smart enough to know which methods it can rename. Again, not
human-readable, but perfectly fine to a browser. It's all about saving
bytes where we can.

Like Sass, Gulp helps manage all the steps for minification, uglification
and concatenation so we get one, very small JavaScript file.

There's a lot more to setting up and using Gulp, which is covered in
[Getting Started With Gulp](gulp-getting-started.md).
