# Sass

Sass is a CSS preprocessor. That's a techy way of saying it generates CSS while
offering additional functionality. In the end we're creating CSS.

CSS is Sass, but not all Sass is CSS. Sass' syntax is very CSS-like. So much so
that you can rename any valid CSS file with the `.scss` file extension and
Sass will recognize it.

Sass offers a lot of additional functionality to help organize and manage our
CSS. Some of the things we're using in Sass:

* variables
* nesting of styles
* mixins
* `each` and `for` loops
* minification

## Variables

We can use variables in Sass. A variable is prepended with `$`. E.g.,
`$link-base-blue: #0072bc;`.

This is helpful because how are you supposed to remember links are supposed
to be `#0072bc`? What if you want to change it? What if you want that color
to match the color of another element? With Sass, just define it in a variable
and use if everywhere. Change it in one place to change it everywhere.

[Read more about variables here](http://sass-lang.com/guide).

## Nesting

Being able to nest your styles helps keep them organized and keeps related
styles together all in one place.

This Sass:

```
a {
    color: $link-base-blue;

    &:hover {
        text-decoration: none;
    }

    &:visited {
        color: $link-base-visited;
    }

    &:active {
        color: $link-base-active;
    }

    img {
        border: 0;
    }
}
```

Creates this CSS:

```
a {
  color: #0072bc;
}

a:hover {
  text-decoration: none;
}

a:visited {
  color: #79111a;
}

a:active {
  color: #bc4a00;
}

a img {
  border: 0;
}
```

[Read more about nesting here](http://sass-lang.com/guide).

## Mixins

Sass gets really powerful with mixins. We're using two mixin libraries:
[Bourbon](http://bourbon.io/) and [Neat](http://neat.bourbon.io/).

Here's an example of how to use a Bourbon mixin to create vendor-prefixed
styles.

Sass:

```
.button {
    @include transition(color .5s ease-out 0s, background-color .5s ease-out 0s, border .5s ease-out 0s);
}
```

CSS:

```
.button {
  -webkit-transition: color 0.5s ease-out 0s, background-color 0.5s ease-out 0s, border 0.5s ease-out 0s;
  -moz-transition: color 0.5s ease-out 0s, background-color 0.5s ease-out 0s, border 0.5s ease-out 0s;
  transition: color 0.5s ease-out 0s, background-color 0.5s ease-out 0s, border 0.5s ease-out 0s;
}
```

Bourbon generated all the proper transition styles for us. No copy and paste
on our part.

[Read about all that Bourbon can do here](http://bourbon.io/docs/).

Neat is a grid framework. [You can read more about *that*
here](http://neat.bourbon.io/). (**Note:** Neat will most likely be deprecated
in favor of Bootstrap's grid framework.)

## Loops

Another programming-like function of Sass are loops. Let's set up a map of
keys and values to define our heading sizes:

```
// Look! It's a variable!
$heading-map-desktop: (h1: 48, h2: 36, h3: 30, h4: 24, h5: 20, h6: 18);
```

We can now use a loop to iterate over the keys and values to generate
all our heading font sizes.

Sass:

```
@each $heading, $size in $heading-map-desktop {
    #{$heading} {
        // Use the "em" Bourbon mixin to convert pixel sizes to ems
        font-size: em($size, 22);
    }
}
```

CSS:

```
h1 {
  font-size: 2.18182em;
}

h2 {
  font-size: 1.63636em;
}

h3 {
  font-size: 1.36364em;
}

h4 {
  font-size: 1.09091em;
}

h5 {
  font-size: 0.90909em;
}

h6 {
  font-size: 0.81818em;
}
```

You may have noticed there's a little more going on there than just a loop.
What's all this `#{$heading}` business? That's string interpolation. Fancy
term for insert the variable value somewhere.

Let's take a close look at that loop. First, we're using `@each`, which
means we're going to iterate over values. As in, "for each thing in this list."

We already talked about the map. Maps are like arrays if you're familiar with
those. In our map above, `h1` is a *key* and it's *value* is 48.

In this statement: `@each $heading, $size in $heading-map-desktop`, keys get
assigned to a variable named `$heading` and values get assigned to `$size`.

We want to create a style for each heading element. For the first item in the
map this:

```
#{$heading}
```

Will result in:

```
h1
```

It will do this for all the key/value pairs in the map to get the generated
CSS in the example above.

* [More Sass basics](http://sass-lang.com/guide).
* [Detailed Sass documentation](http://sass-lang.com/documentation/file.SASS_REFERENCE.html)
