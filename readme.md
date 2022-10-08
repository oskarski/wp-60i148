# WordPress Framework

WordPress framework for an easy and fast development of custom themes.

## Installing

1. Clone this repository using `git clone <url>`
2. Run `./setup.sh`
3. Update `.htaccess` file
4. Update `wp-content/themes/custom-theme/.env` file
5. Enter ACF PRO activation key

# Use Cases

#### Menus
In `wp-content/themes/custom_theme/functions.php` in `td_menus()` function you can add new menus like this:

    MenuCreator::add( $menu_name, $location, $menu_class, $container_class, $container = 'nav' );


#### PostTypes
In `wp-content/themes/custom_theme/functions.php` in `td_post_types()` function you can add new post types like this:

    PostTypeCreator::add( $singular_name, $plural_name, $machine_name, $supports = [], $icon = 'dashicons-edit', $taxonomies = [], $capability_type = 'page', $public = true );

Once you created new post type you also need to create entity and repository. 
Please use `wp-content/themes/custom-theme/entity/Page.php` and 
`wp-content/themes/custom-theme/repository/PageRepository.php` as examples.
Keep in mind that file and class names prefixes must be the same.


You can create custom archives for every post type by adding `archive-{post-type-name}.php` in theme directory.
You can also create custom single page for every post type by adding `single-{post-type-name}.php` in theme directory.

#### Taxonomies
In `wp-content/themes/custom_theme/functions.php` in `td_taxonomies()` function you can add new taxonomies like this:

    TaxonomyCreator::add( $single_name, $plural_name, $machine_name, $post_types, $hierarchical = false, $public = true );

Once you created new taxonomy you also need to create entity and repository. 
Please use `wp-content/themes/custom-theme/entity/Category.php` and 
`wp-content/themes/custom-theme/repository/CategoryRepository.php` as examples.
Keep in mind that file and class names prefixes must be the same.


#### Custom Template Pages
You can create page template by adding `page-{template-name}-template.php` file in theme directory. 
In the first lines you MUST ADD following code
    
    <?php
    /* Template Name: {Template Name} */


#### ACF
After creating new field groups in wp-admin, new files will be added automatically to acf-json directory
in theme directory. Please remember to add them to GIT, because they are not added automatically! 


###### Sections
By default there is created `General` tab in `General Settings` in `wp-admin`. You can add your own
sections in `td_acf_init()` function in `wp-content/themes/custom_theme/functions.php` by calling

    AcfCreator::add_section( $name );

Later you can add fields to specific section. To retrieve section field value please use

    AcfCreator::get_option( $selector );

###### Fields
`AcfCreator` class provides static methods for retrieving acf data. Please do not call by your own `get_field()`, use 
`AcfCreator` methods instead. Here is list of methods:

1. `AcfCreator::get( $selector, $post_id, $tag = '', $class = '' );`
2. `AcfCreator::get_option( $selector );`
3. `AcfCreator::get_repeater( $selector, $post_id );`
4. `AcfCreator::get_link( $selector, $post_id );`
5. `AcfCreator::get_image( $selector, $post_id);`
6. `AcfCreator::get_gallery( $selector, $post_id );`


#### Forms
In `wp-content/themes/custom_theme/functions.php` in `td_forms()` function you can add new forms by
creating new object of type `valueObjects\Form.php` and passing it to `FormsCreator::add(Form $form)`:

    Form::__construct( $id, $success_callback )

`$id` must be unique and preferable describing form purpose.
`$success_callback` is an anonymous function fired on validation success. Form values are passed as array
to this function by first argument.

After that you can add new fields by calling:
   
    Form::add_form_control( FormControl $form_control, $force_control_name = false );

As `$form_control` you need to pass object of:

    FormControl::__construct( $name, $type, $label, $required = false, $placeholder = '', $value = '' );

`$name` must be unique per every form and is used in form template.

Every form must have it's own template in `wp-content/themes/custom-theme/templates/forms/` directory.
You must add new file using form `$id` like this: `{$id}-form.php`. Inside you can use `$form_id`, 
`$form_class` and every added FormControl. You can access FormControl by calling `${$name}_control`. Please
note that, if your `$name` contains `-` or `' '` it will be converted to `_` !

Form is rendered by calling

    FormCreator::render_form( $form_id );

Inside form template you can render controls by calling

    FormControl::render();

Sometimes you may want to use 

    FormControl::render_label();
     
or 

    FormControl::render_control();
     
separately.


#### Emails
You can send emails by calling `EmailCreator::send( Email $email )`. As `$email` you pass 
    
    Email::__construct( EmailAddress $to, EmailAddress $from, $subject, $template_name, array $params )
    
`$template_name` is used to get relevant file from `wp-content/themes/custom-theme/templates/emails/` directory.
File name should be `{$template_name}-email.php`. Inside you can use use: `$from`, `$to`, `$subject` and 
all variables passed in `$params`.


#### Widgets
You can create new widgets by creating class in `wp-content/themes/custom-theme/service/widgets/` directory. It
must extends `BaseWidget` class and override following parameters:
1. `$name`
2. `$description`
3. `$widget_id`

After that you must add class name to `$widgets` array in `td_widgets()` function in `wp-content/themes/custom-theme/functions.php`

There you can also register new sidebars by calling 

    WidgetCreator::add_widget_area( $widget_area_machine_name, $widget_area_name );


#### ShortCodes
You can create new shortcodes in `td_short_codes()` in `wp-content/themes/custom-theme/functions.php` file by calling

    ShortCodeCreator::register( $name, $params = [] );  

After that you must create new file `{$name}-short-code.php` in `wp-content/themes/custom-theme/templates/short-codes/` directory
Inside this file you can use every variable passed in `$params` and `$content` in case of closing short code.



#### Messages
You can add info messages for users by calling:

    MessageCreator::add( $text, $type = 'error' );
    
By default all messages are displayed in `header.php` file after opening `body` tag.