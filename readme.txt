=== Plugin Name ===
Contributors: circularartsnetwork
Donate link: https://canarts.org.uk
Tags: circular, arts, classifieds, 
Requires at least: 4.7
Tested up to: 5.4
Stable tag: 4.3
Requires PHP: 7.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html


A recycling and reuse tool created specifically for WordPress.

== Description ==
## Overview

CAN is a recycling and reuse tool that helps the arts combat the climate emergency. It gives you access to the materials you need for your creative projects by providing a place where materials and resources can be exchanged. CAN stands for Circular Arts Network, it’s an online platform that supports  a circular economy within the arts.

 
CAN encourages reuse across all artforms, it works by connecting people and organisations together.  CAN partners with other industries such as construction and manufacturing, helping creative communities access their surplus materials and supporting industry to be more sustainable.

 
It’s basically a Gumtree but for the arts. Finding affordable (or FREE!) materials and resources that are used by artists and makers in an easy and connected way.  CAN also facilitates the sharing of resources such as transport, equipment, time, and other essentials. We believe that by doing so, users will be able to make the most of their time and resources.

 
The National Theatre of Scotland, National Galleries Scotland, Glasgow School of Art and Edinburgh University are among 700+ users who have already benefited from CAN.

CAN is not for profit, and is administrated by Sculpture Placement Group CIC.

We welcome feedback, which you can give by emailing info@canarts.org.uk

## Credits
Original site developed by Sculpture Placement Group (SPG), in collaboration with developer Ralph Mackenzie.

CAN branding, logo and original website layout all designed by Ralph Mackenzie.

Category Icons were commissioned for CAN, and illustrated by Esme Macintyre.

This WordPress plugin was adapted for CAN by Considerate Digital. It is based on the open source plugin Ultimate Classified Listings by “webcodingplace”.


## Installation

### Step 1: Download the Plugin

Obtain the ZIP file for the Circular Arts Network plugin from the source provided.

### Step 2: Log in to Your WordPress Dashboard

Access your WordPress site's admin panel by navigating to `yourdomain.com/wp-admin`.

### Step 3: Navigate to Plugins

In your WordPress dashboard, go to the "Plugins" section on the left-hand menu and click on "Add New".

### Step 4: Upload the Plugin

At the top of the page, click on the "Upload Plugin" button. Then, choose the ZIP file of the Circular Arts Network plugin that you downloaded earlier.

### Step 5: Install and Activate

After uploading the ZIP file, click on the "Install Now" button. Once installed, you will need to activate the plugin by clicking on the "Activate Plugin" link.

## Shortcodes Documentation

### 1. Display Listings

**Shortcode**: `[can_listings]`

This shortcode displays listings in a structured format with various customisation options.

#### Attributes

- **columns**: (Default: 3) Number of columns per row.
- **style**: (Default: 1) Style template of the listings.
- **image_size**: (Default: large) Size of the featured images.
- **pagination**: (Default: enable) Enables or disables bottom pagination.
- **top_bar**: (Default: enable) Enables or disables the top bar.
- **order**: (Default: ASC) Sorting order of listings.
- **orderby**: (Default: date) Basis for sorting listings.
- **orderby_custom**: Specify a custom field name for sorting.
- **author**: Filter listings by a specific author's ID.
- **tags**: Display listings tagged with specific tags.
- **categories**: Display listings from specific categories.
- **ids**: Display specific listings by their IDs.
- **exclude**: Exclude certain listings by their IDs.
- **total**: (Default: 9) Total number of listings to show.

### 2. Display Categories

**Shortcode**: `[can_categories]`

This shortcode allows you to display categories.

#### Attributes

- **columns**: (Default: auto) Number of columns per row.
- **style**: (Default: 1) Style template for the categories display.
- **image_size**: (Default: thumbnail) Size for category thumbnails.

### 3. Seller Dashboard

**Shortcode**: `[can_dashboard]`

This shortcode provides a dashboard for sellers to manage their listings and profile. It shows a login form when the user is not logged in.

### 4. Search Form

**Shortcode**: `[can_search_form]`

Displays a customisable search form.

#### Attributes

- **columns**: (Default: auto) Number of columns for the form.
- **style**: (Default: 1) Styling of the search form.
- **fields**: (Default: search_field, regular_price, purpose, condition) Fields to include in the form.
- **results_selector**: HTML selector to append AJAX search results.
- **results_url**: URL to redirect for search results, disabling AJAX.
- **bg_colour**: (Default: #f5f5f5) Background colour of the search form.

### 5. Search Results

**Shortcode**: `[can_search_results]`

This shortcode is used to display the search results.

## Why open source?
Open source software licensed under the **GPLv3** offers significant benefits by ensuring user freedoms and promoting collaboration. The license guarantees that anyone can use, study, modify, and share the software, empowering users to adapt it to their specific needs. This openness fosters a collaborative development environment where improvements, bug fixes, and innovations are shared with the community, accelerating progress and benefiting all users.

One of the key advantages of **GPLv3** is its protection against proprietary takeovers. The copyleft requirement ensures that any modifications must also be distributed under the same license, preventing companies from turning open-source software into proprietary products. This safeguard keeps the software, along with any improvements, freely accessible to everyone, preserving the community's contributions. Additionally, **GPLv3** includes strong patent protection, ensuring that users are safe from legal threats related to software patents.

The license also combats digital restrictions by preventing the use of hardware or DRM to block users from modifying the software. This keeps users in control, even when the software is embedded in devices. Transparency is another significant advantage, as the open nature of **GPLv3** software allows users to inspect the source code, building trust in its security and integrity.

Overall, **GPLv3** fosters ethical, sustainable software development. It ensures that software remains free, adaptable, and accessible, reducing costs for individuals and organisations. This model encourages long-term sustainability, as anyone can continue the development of the software if the original creators stop supporting it, making it a robust choice for both users and developers who value openness and collaboration.

## Contributing
We welcome contributions from everyone, whether that's fixing some of the core code or a spelling mistake. If you're interested in making contributions to CAN then please read our "Code of Conduct" before you make any suggestions or send a pull request.

### Reporting bugs
Before submitting an issue, please take a few seconds to do a quick search to check that your issue has not already been raised or fixed. 
You can report bugs by submitting a new issue. The more detail you can provide in your issue, the better our team will be able to help you. Note that we expect anyone submitting a bug to adhere to our "Code of Conduct".

### Future Development
Here, in no particular order, are a list of future developments we'd like to undertake:
- Adapt the search to incoporate advanced search options
- Add "create alert" functionality

== Changelog ==

= 0.2 =
* WordPress plugin requirements adhered to.

== Upgrade Notice ==

No upgrades at present.

