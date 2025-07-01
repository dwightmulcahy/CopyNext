# DokuWiki Copy Next Plugin

This DokuWiki plugin provides functionality to copy a "journal" page to a new page exactly seven days in the future. It's designed for journal systems where you might have daily entries and want to quickly create the next week's entry based on a previous template, while filtering out specific content.

## Features

* Copies a DokuWiki page with a specific ID format (`journal:day:YYYY:MM:DD`).
* Creates a new page for seven days in the future.
* Updates the main title of the new page to reflect the new date (e.g., `====== Mon 07/07 ======`).
* Filters out lines containing `<todo ... #dwight:...>` tags during the copy process.
* Redirects to the new page in edit mode upon successful creation.

## Installation

1.  Download the plugin's `.zip` file.
2.  Extract the contents into your DokuWiki's `lib/plugins/` directory. Ensure the plugin files are within a folder named `copynext` (e.g., `dokuwiki/lib/plugins/copynext/action.php`).
3.  Go to your DokuWiki's Admin > Extension Manager and verify that the "Copy Next" plugin is listed.

## Usage

1.  Navigate to a DokuWiki page with the ID format `journal:day:YYYY:MM:DD` (e.g., `journal:day:2025:07:01`).
2.  Append `?copynext` to the URL. For example: `https://yourdokuwiki.com/doku.php?id=journal:day:2025:07:01&copynext`
3.  The plugin will attempt to create a new page with the ID `journal:day:YYYY:MM:DD` for the date seven days from the current page.
4.  If successful, you will be redirected to the new page in edit mode, with its content copied and filtered, and the title updated.

## Notes

* This plugin is specifically designed for the `journal:day:YYYY:MM:DD` page ID format.
* It will not overwrite an existing target page. If the target page already exists, a message will be displayed, and no action will be taken.
* Lines containing any `<todo ... #dwight:...>` (case-insensitive) will be removed from the copied content.

## License

This plugin is released under the MIT License. (You may choose a different license, e.g., GPL)


