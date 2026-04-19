=== IAR Elementor Widgets ===
Contributors: iamroothr
Tags: elementor, widgets, slider, hero, custom
Requires at least: 5.8
Tested up to: 6.7
Stable tag: 1.0.3
Requires PHP: 8.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Custom Elementor widgets by I am Root agency — Hero Slider, Hero, Image Grid and more.

== Description ==

IAR Elementor Widgets is a collection of custom Elementor widgets designed to extend your page-building capabilities. Includes a feature-rich Hero Slider with parallax and fade effects, a static Hero widget, and an Image Grid widget.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/iar-elementor-widgets` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Ensure Elementor is installed and active.
4. The widgets will appear in the Elementor editor under the "IAR Widgets" category.

== Changelog ==

= 1.0.3 - 2026-04-19 =
* Added: Responsive typography controls for Hero Slider title and subtitle (Elementor Group_Control_Typography)
* Added: Responsive background size control (cover/contain/auto) per breakpoint
* Added: Responsive background position control per breakpoint
* Changed: Removed hardcoded font sizes from Hero Slider CSS in favour of Elementor controls
* Fixed: Parallax effect background not covering on mobile portrait viewports
* Fixed: Hero Slider grey bands caused by Elementor column padding
* Fixed: Navigation arrows hidden on mobile (≤767px) for cleaner touch experience

= 1.0.2 - 2026-04-19 =
* Bump version

= 1.0.1 - 2026-04-19 =
* Added: Dynamic slide rotation for Hero Slider — rotates first N slides on each page load
* Added: ImageGrid limited to 4 items

= 1.0.0 - 2026-04-19 =
* Initial release
* Added: Hero Slider widget with slide, fade, and parallax effects
* Added: Hero widget
* Added: Image Grid widget
* Added: Button arrow icon support

== Upgrade Notice ==

= 1.0.3 =
Adds responsive style controls to the Hero Slider widget. Background images now properly cover on all screen sizes.
