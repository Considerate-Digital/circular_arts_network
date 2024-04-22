# Circular Arts Network Plugin Documentation

## Overview

CAN is a recycling and reuse tool that helps the arts combat the climate emergency. It gives you access to the materials you need for your creative projects by providing a place where materials and resources can be exchanged. CAN stands for Circular Arts Network, it’s an online platform that supports  a circular economy within the arts.

 
CAN encourages reuse across all artforms, it works by connecting people and organisations together.  CAN partners with other industries such as construction and manufacturing, helping creative communities access their surplus materials and supporting industry to be more sustainable.

 
It’s basically a Gumtree but for the arts. Finding affordable (or FREE!) materials and resources that are used by artists and makers in an easy and connected way.  CAN also facilitates the sharing of resources such as transport, equipment, time, and other essentials. We believe that by doing so, users will be able to make the most of their time and resources.

 
The National Theatre of Scotland, National Galleries Scotland, Glasgow School of Art and Edinburgh University are among 700+ users who have already benefited from CAN.

CAN is not for profit, and is administrated by Sculpture Placement Group CIC.

We welcome feedback, which you can give by emailing info@canarts.org.uk

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

**Shortcode**: `[uclwp_listings]`

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

**Shortcode**: `[uclwp_categories]`

This shortcode allows you to display categories.

#### Attributes

- **columns**: (Default: auto) Number of columns per row.
- **style**: (Default: 1) Style template for the categories display.
- **image_size**: (Default: thumbnail) Size for category thumbnails.

### 3. Seller Dashboard

**Shortcode**: `[uclwp_dashboard]`

This shortcode provides a dashboard for sellers to manage their listings and profile. It shows a login form when the user is not logged in.

### 4. Search Form

**Shortcode**: `[uclwp_search_form]`

Displays a customisable search form.

#### Attributes

- **columns**: (Default: auto) Number of columns for the form.
- **style**: (Default: 1) Styling of the search form.
- **fields**: (Default: search_field, regular_price, purpose, condition) Fields to include in the form.
- **results_selector**: HTML selector to append AJAX search results.
- **results_url**: URL to redirect for search results, disabling AJAX.
- **bg_colour**: (Default: #f5f5f5) Background colour of the search form.

### 5. Search Results

**Shortcode**: `[uclwp_search_results]`

This shortcode is used to display the search results.

