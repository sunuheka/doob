## Changelog ##

** [2017-09-20] **

* SSL and HTTPS are now on by default and a certificate is automatically created on provision. To accept the certificate follow the instructions below:
	1. Open the sites webroot and then open the provision/ssl folder in Finder.
	2. Open the .cert, doing this will automatically open the keychain Access app. 
	3. If a dialog box opens select "Always Trust" and skip step 4.
	4. Find the newly added certificate, doubleclick it and select "Always Trust" under the "Trust".
* Added new custom VVV site variable "ssl", it defaults to true. Set it to a "falsy" value to set the WP_SITEURL and WP_HOME constants to http://.

** [2017-08-31](https://bitbucket.org/triggerfish/wptemplate/commits/dbccdfca45db33362ba5f2c263d7fa2ecf12d6b3) **

* Update to Bootstrap 4.0.0 beta
	* [Official Bootstrap release notes](https://github.com/twbs/bootstrap/releases/tag/v4.0.0-beta)
* Added snippet to show which template we are currently viewing
	* [Commit](https://bitbucket.org/triggerfish/wptemplate/commits/40571cf69ea6ed73d8e68b572e7f369dbbf22517)

** [2017-06-12](https://bitbucket.org/triggerfish/wptemplate/commits/65de7ec533bb061c8c5c7b91f00151142486025f) **

* Update to Bootstrap 4.0.0 alpha 6.
* Usage of mixin [`m-mq`](https://bitbucket.org/triggerfish/wptemplate/commits/65de7ec533bb061c8c5c7b91f00151142486025f#Lwp-content/themes/wptemplate/assets/css/theme/mixins.scssF3T1) will now throw a fatal error, use [`media-breakpoint-down`](https://bitbucket.org/triggerfish/wptemplate/commits/65de7ec533bb061c8c5c7b91f00151142486025f#Lwp-content/themes/wptemplate/assets/css/parts/forms.scssT177) or the other [media query mixins](https://v4-alpha.getbootstrap.com/layout/overview/#responsive-breakpoints).
* Sizes/widths and breakpoints are now set with Sass maps. [Example](https://bitbucket.org/triggerfish/wptemplate/commits/65de7ec533bb061c8c5c7b91f00151142486025f#Lwp-content/themes/wptemplate/assets/css/theme/variables.scssT7)
* Added a `xxl` size/breakpoint, e.g. `col-xxl-6`. [variables.scss](https://bitbucket.org/triggerfish/wptemplate/commits/65de7ec533bb061c8c5c7b91f00151142486025f#chg-wp-content/themes/wptemplate/assets/css/theme/variables.scss)
* Make sure to read up on the changes with the update to version 4, e.g. grid, utility classes, flexbox etc.

** 2017-06-09 **

* Add comments to explain the app.js initialize flow. (https://bitbucket.org/triggerfish/wptemplate/commits/913f195d715a53f42712af01b681a852bad828f7)

**[2017-03-24](https://bitbucket.org/triggerfish/wptemplate/commits/e2367dd7ba91e079412948815b9dff4443e1fd6f)**

* Change name of javascript property `component` to `element`. **Be aware that `element` should NOT be set, it will be set automatically. See next item.**
* Instead of setting the `component` property in javascript components config, set the `selector` property to a selector **(NOT a jQuery object)**.

Old way
```
#!js
app.testModule = {
	config: {
		component: $( '.test-module' ),
	},

	init: function() {
		console.log( this.config.component );
	},
};
```

New way
```
#!js
app.testModule = {
	selector: '.test-module',

	init: function() {
		console.log( this.element );
	},
};
```
