# eportfolios-plugin

## Work in progress!

This plugin is a work-in-progress application which takes the work done on the University of Lincoln ePortfolio project and builds it into a theme independent plugin. For best results, use with the Custom Field Editor!

## Setup

Install and activate the plugin

### Add User and Course types

Make sure that ID's do not include space characters - ideally, just use - and _

### Set up initial options

- Select the course type for the portfolio
- The status for a new portfolio should **ALWAYS** be *open*
- Set the type of user that will be submitting the portfolio

## Users

Once you have set up the user types, these can be assigned to users once they have been added. These are in **ADDITION** to the standard Wordpress user types.

## Pages

### Sign-off's

As you build pages you may want to set them to require sign off by certain user types. Simply tick the user type(s) that need to sign off the page on the edit screen and logged in users of that type will get the option when they are logged into the portfolio.

### Sign-off grid and submit

Use the shortcode \[signoff_grid] to set the page to display a sign off grid and submit button. This sign off grid will be a visual guide as to which pages of the portfolio still require signing off before the portfolio can be submitted.

### Forms

Some pages may require forms adding for users to complete. This can be done by installing and activating the 'Custom Form Editor' plugin which has hooks that are used by the EPortfolio plugin to provide extra functionality.

## Widgets

### Portfolio Status

A widget that allows custom messages and links to be assigned to different portfolio statuses to create different tiers of submission for the users.

## Maintenance

### Portfolio status

Once the portfolio has been submitted, a custom hook deactivates any Custom Forms used on the portfolio. Editing can be re-enabled by setting the portfolio status to 'open' in the admin options screen.

### Managing signatures

If a page has been eroniously signed off, you can remove any of the signatures from the 'edit' screen of that page.
