# WebComplete Starter Kit

This is starter theme that you can build from. The primary purpose of this theme is to provide a file structure rather
than a framework for markup or styles. Configure your SASS files, scripts, and task runners however you would like!

## Warning

To ensure the proper functionality of this theme, it is required to install the **ACF PRO** plugin. Please make sure to
have it activated before using the theme.

## Installation

1. Download the theme files.
2. Upload the theme to your WordPress installation.
3. Install and activate the **ACF PRO** plugin.
4. Activate the theme from the WordPress admin panel.

## Project Structure

```
acf-json/
assets/
    └── fonts/
    └── img/
    └── js/
        └── scripts/
            └── admin/
            └── helpers.js
    └── scss/
        └── base/
        └── components/
        └── sections/
        └── page/
        └── wcl-style.scss
        └── wcl-admin-style.scss
inc/
    └── actions/
    └── api/
    └── cron/
    └── custom-post-types/
    └── walker/
    └── acf-blocks.php
    └── helpers.php
    └── i18n.php
node_modules/
template-parts/
    └── acf-blocks/
        └── bloc-name/
            └── block.json
            └── render.php
            └── script.js
            └── style.scss
            └── style.css
    └── components/
vendor/
.env
.gitignore
404.php
composer.json
composer.lock
footer.php
functions.php
header.php
index.php
package.json
package-lock.json
page-test.php
page.php
screenshot.png
single.php
style.css
webpack.mix.js
```

### acf-json/

**File changes aren’t allowed!**
Contains automatically generated JSON files for Advanced Custom Fields (ACF) settings. These files are used to manage
and import/export ACF field groups.

### assets/

This directory holds all the static assets for the theme.

- **fonts/**: Contains custom font files used in the theme. **Recommended format .woof2**
- **img/**: Contains image files used throughout the code.
- **js/**: Contains JavaScript files.
    - **scripts/**: Subdirectory for organizing JavaScript files.
        - **admin/**: Contains JavaScript files specifically for the admin area.
        - **helpers.js**: A helper JavaScript file with utility functions.
- **scss/**: Contains SCSS files for styling.
    - **base/**: Base styles for the theme.
    - **components/**: Styles for reusable components.
    - **sections/**: Styles for different sections of the site.
    - **page/**: Styles specific to page layouts.
    - **wcl-style.scss**: The main SCSS file for UI styles into which other files are imported.
    - **wcl-admin-style.scss**: SCSS file for admin area styles.

### inc/

This directory contains various PHP files for theme functionality.

- **actions/**: Contains files that define AJAX actions for the theme.
- **api/**: Contains files for working with third-party API.
- **cron/**: Contains files for scheduled tasks (cron jobs).
- **custom-post-types/**: Contains files that register custom post types.
- **walker/**: Contains custom walker classes for menus or other structures.
- **acf-blocks.php**: File for registering ACF blocks.
- **helpers.php**: Contains helper functions used throughout the theme.
- **i18n.php**: Contains functions for internationalization and localization in JS files.

### node_modules/

**File changes aren’t allowed!**
Contains all the npm packages required for the project. This folder is generated when you run `npm install`.

### template-parts/

Contains reusable template parts for the theme.

- **acf-blocks/**: Directory for ACF block templates.
    - **bloc-name/**: Directory for a specific ACF block.
        - **block.json**: Configuration file for the ACF block.
        - **render.php**: PHP file that defines how the block is rendered.
        - **script.js**: JavaScript file for block-specific functionality.
        - **style.scss**: SCSS file for block-specific styles.
        - **style.css**: Compiled CSS file for the block.

- **components/**: Contains reusable components for the theme.

### vendor/

**File changes aren’t allowed!**
Contains third-party libraries and dependencies managed by Composer.

### .env

Environment configuration file for storing sensitive information like database credentials.

### .gitignore

Specifies files and directories that should be ignored by Git.

### 404.php

Template file for handling 404 errors (page not found).

### composer.json

**Change file aren’t allowed!** File that defines the PHP dependencies for the project.

### composer.lock

**Change file aren’t allowed!** File that locks the versions of the PHP dependencies.

### footer.php

Template file for the footer section of the theme.

### functions.php

Main file for defining theme functions, hooks, and features.

### header.php

Template file for the header section of the theme.

### index.php

**Change file aren’t allowed!** Main template file for the theme, used as a fallback for other templates.

### package.json

**Change file aren’t allowed!** File that defines the JavaScript dependencies and scripts for the project.

### package-lock.json

**Change file aren’t allowed!** File that locks the versions of the JavaScript dependencies.

### page-test.php

Template file for a specific page layout (test page). Used to test specific parts of a code or function. You need to
create a page with the slug _**test**_
S

### page.php

Template file for displaying standard pages.

### screenshot.png

Image file used as a screenshot of the theme in the WordPress admin.

### single.php

Template file for displaying single posts.

### style.css

**Change file aren’t allowed!** Main stylesheet for the theme, containing theme information and styles.

### webpack.mix.js

Configuration file for Laravel Mix, used for compiling assets.

### Template Naming Conventions

For specific page templates, use the naming convention `page-{slug}.php`. For example:

- `page-login.php` for the login page.
- `page-registration.php` for the registration page.

For individual pages of a custom post type, use the slug `single-{post_type}.php`. For example:

- `single-product.php` for individual product pages.

**Note:** Create separate templates for pages only when it is not possible to achieve the desired layout or
functionality using sections (blocks) in the `page.php` file.

## Compiling Styles and Scripts

To compile styles and scripts for the theme, you need to have Node.js and npm installed. Follow these steps:

1. **Install Dependencies**: Run the following command in the root directory of your project to install the required npm
   packages:

```
npm install
```

2. **Available Scripts**: You can use the following npm scripts to manage your assets:
   **Development**: To compile your assets for development, run:

```
npm run dev
```

**Watch**: To automatically compile your assets when changes are made, run:

```
npm run watch
```

**Production**: To compile and minify your assets for production, run:

```
npm run build
```

These commands utilize Laravel Mix to handle the compilation of your SCSS and JavaScript files, ensuring that your
styles and scripts are optimized for performance.

The `webpack.mix.js` file is used to define the asset compilation process for your theme using Laravel Mix. Below is an
overview of its contents:

```javascript
const mix = require('laravel-mix');

mix.webpackConfig({
    stats: {
        children: true
    }
});

// JavaScript
mix.js('assets/js/scripts/*.js', 'assets/js/wcl-scripts.js');
mix.js('assets/js/scripts/admin/*.js', 'assets/js/wcl-admin-scripts.js');

// SCSS
mix.options({
    processCssUrls: false,
    postCss: [
        require('autoprefixer'),
        require('postcss-sort-media-queries')
    ],
})

mix.sass('assets/scss/wcl-style.scss', 'assets/css')
    .sourceMaps();

mix.sass('assets/scss/wcl-admin-style.scss', 'assets/css')
    .sourceMaps();

// ACF Blocks
mix.sass('template-parts/acf-blocks/example/style.scss', 'template-parts/acf-blocks/example')
    .sourceMaps();
```

**JavaScript**: The first two mix.js commands compile JavaScript files from the specified directories into single output
files (wcl-scripts.js and wcl-admin-scripts.js).

**SCSS**: The mix.sass commands compile SCSS files into CSS, with source maps enabled for easier debugging.

**Do not modify this section if you are maintaining the folder structure.**

**ACF Blocks**: The section for ACF Blocks compiles the SCSS file located at `template-parts/acf-blocks/`
For each block, copy the following code, replacing the `example` with the folder name of the appropriate block

Make sure to run the appropriate npm commands to compile your assets after making any changes to your JavaScript or SCSS
files.

## Creating a Block

The `block.json` file defines the properties and settings for your custom block. Below is an example of a `block.json`
file:

```json
{
  "name": "wcl/example",
  "version": "0.0.1",
  "title": "Example",
  "description": "A custom example block",
  "style": [
    "file:./style.css"
  ],
  "script": [
    "file:./script.js"
  ],
  "category": "webcomplete",
  "icon": "block-default",
  "keywords": [
    "webcomplete"
  ],
  "acf": {
    "mode": "edit",
    "renderTemplate": "render.php",
    "validate": "false"
  },
  "supports": {
    "anchor": true
  }
}
```

### Key Points:

- **Name**: The block name must start with the company name (e.g., wcl/example). This ensures proper organization and
  avoids
  naming conflicts.
- **Title**: Choose an appropriate title for the block, avoiding words like "block" or "section." The title should be
  descriptive and user-friendly.
- **Description**: Provide a short description that explains what the block does. This helps users understand its
  purpose.
- **Icon**: Select a suitable icon from [WordPress Dashicons](https://developer.wordpress.org/resource/dashicons/) to
  represent the block visually.
- **Style**: Include the style section if the block requires additional styling. This should point to a CSS file that
  contains the styles for the block.
- **Script**: Include the script section if the block needs to execute specific JavaScript code. This should point to a
  JavaScript file that contains the necessary scripts.
- **Supports**: If the block occupies a specific section of the page, include the following to support custom anchor
  values:

```json
"supports": {
"anchor": true
}
```

In your PHP code, handle the anchor as follows:

```php
<?php
// Support custom "anchor" values.
$anchor = '';
if ( !empty( $block[ 'anchor' ] ) ) {
    $anchor = 'id="' . esc_attr( $block[ 'anchor' ] ) . '" ';
}
?>

<section <?= $anchor; ?>class="wcl-example">
...
</section>

```

- **Category**: The category must always be set to webcomplete. This is defined in the inc/acf-blocks.php file.
- **Keywords**: Include relevant keywords that help users find the block easily.

By following these guidelines, you can create well-structured and functional custom blocks for your theme.

### Creating ACF Fields for Blocks

When creating ACF fields for your custom blocks, it's important to structure them in a way that reflects the layout and
functionality of the block. Here are some guidelines:

- **Using Groups**: If your block contains multiple columns (e.g., a two-column layout), you should represent this in
  the admin area using a **Group** field. This allows you to organize the fields logically and makes it easier for users
  to manage the content.

- **Styling ACF Sections**: In the `wcl-admin-style.scss` file, there are specific classes that can help style ACF
  sections for better usability. Here are the relevant classes:

```scss
.wcl-2-columns,
.wcl-3-columns,
.wcl-4-columns,
.wcl-5-columns,
.wcl-6-columns {
  & > .acf-input > .acf-repeater > .acf-table {
    display: flex;
    flex-direction: column;

    & > tbody {
      display: flex;
      flex-wrap: wrap;

      & > .acf-row .acf-fields {
        width: 100% !important;
      }
    }
  }
}

.wcl-2-columns {
  & > .acf-input > .acf-repeater > .acf-table > tbody > .acf-row {
    width: 100%;
    max-width: 50%;
  }
}

.wcl-3-columns {
  & > .acf-input > .acf-repeater > .acf-table > tbody > .acf-row {
    width: 100%;
    max-width: 33%;
  }
}

.wcl-4-columns {
  & > .acf-input > .acf-repeater > .acf-table > tbody > .acf-row {
    width: 100%;
    max-width: 25%;
  }
}

.wcl-5-columns {
  & > .acf-input > .acf-repeater > .acf-table > tbody > .acf-row {
    width: 100%;
    max-width: 20%;
  }
}

.wcl-6-columns {
  & > .acf-input > .acf-repeater > .acf-table > tbody > .acf-row {
    width: 100%;
    max-width: 16.66%;
  }
}
```

These classes allow you to create responsive layouts for your ACF fields in the admin area, making it easier for users
to manage content in a visually appealing way.

By following these guidelines, you can create effective ACF fields that enhance the functionality and usability of your
custom blocks.

## Working with ACF Fields

When working with ACF fields, it's important to configure them correctly to ensure optimal functionality and usability.
Here are some guidelines for specific field types:

- **Text Area**: On the **Presentation** tab, make sure to enable the option: `Automatically add <br>` This will ensure
  that line breaks are added automatically in the output.

- **WYSIWYG Editor**: For the WYSIWYG Editor field, set the following options:
- **Toolbar**: Select `Basic` to provide a simplified editing interface.
- **Show Media Upload Buttons**: Set this to `false` to prevent users from uploading media directly within the editor.

- **Select Field**: For select fields, choose the option: `Stylized UI`This enhances the appearance of the select
  dropdown.

- **Links**: Use the **Link** field for creating hyperlinks. This field provides a user-friendly interface for adding
  links.

- **Taxonomy Fields**: If the field is a taxonomy, ensure that the option: `Create Terms`is set to `false`. This
  prevents users from creating new terms directly from the block.

- **Required Fields**: Mark any fields that are mandatory as required. For all non-required fields, ensure to validate
  them in your code.

```php
<?php
$description = get_field( 'description' );
?>

<?php if ( !empty( $description ) ): ?>
    <div class="wcl-example__description">
        <?= $description; ?>
    </div>
<?php endif ?>

```

- **Instructions**: For complex fields, add instructions to guide users. For example, you can specify recommended image
  sizes or other relevant information.

- **Grouping Fields**: Use tabs and groups to conveniently organize fields. This improves the user experience by making
  it easier to navigate through related fields.

- **Repeater Fields**: When using repeater fields, specify a relevant value in the **Button Label** that pertains to the
  block. For example, use `Add Step` for a Steps block or `Add Item` for a generic item block.

By following these guidelines, you can create ACF fields that are user-friendly and effective, enhancing the overall
functionality of your custom blocks.

## Best practice

### Naming Conventions for Styles

When creating styles for your blocks, it's important to follow a consistent naming convention to ensure clarity and
maintainability. Here are the guidelines for naming your CSS classes:

- **Prefix**: All classes should start with the prefix `wcl-`, followed by the block name. This helps to identify the
  styles associated with our company.

- **Name**: Use the name of the block or section as the next part of the class name. This should be in lowercase and
  hyphen-separated if necessary.

- **Sub-blocks**: For sub-elements or within the block, use the double underscore (`__`) notation followed by the name
  of the sub-block. This clearly indicates the relationship between the main block and its sub-elements.

- **Modifiers**: For modifiers that change the appearance or behavior of a block or sub-block, use the double
  hyphen (`--`) notation. This clearly indicates that the class is a variation of the base class.

For example, if you have a modifier for a button within the services block that indicates it is a primary button, you
might name it as follows:

```html

<button class="wcl-button wcl-button--primary">Learn More</button>
```

In this case, wcl-button--primary indicates that this button is a primary variation of the base button class.

### Example

For instance, if you have a block named "services," your CSS classes might look like this:

- The main section has the class `wcl-services`.
- The wrapper has the class `wcl-services__wrapper`.
- The title has the class `wcl-services__title`, and so on.

By following this naming convention, you can create a clear and organized structure for your styles, making it easier to
maintain and understand your code.