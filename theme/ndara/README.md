# Ndara Academy Theme

A modern, responsive Moodle theme designed specifically for educational institutions. Built with the Poppins font family and featuring a clean, professional design with customizable color schemes.

## Features

- **Modern Design**: Clean and professional interface optimized for learning
- **Poppins Font**: Beautiful, readable typography using Google Fonts
- **Responsive Layout**: Fully responsive design that works on all devices
- **Customizable Colors**: Easy color customization through theme settings
- **Bootstrap 4 Based**: Built on the latest Bootstrap framework
- **Child Theme**: Inherits from the Boost theme for stability
- **Custom CSS Support**: Additional CSS customization options
- **SCSS Support**: Advanced styling with SCSS preprocessing

## Installation

1. Copy the `ndara` folder to your Moodle installation's `theme/` directory
2. Log in to your Moodle site as an administrator
3. Go to **Site administration** > **Appearance** > **Themes** > **Theme selector**
4. Select "Ndara Academy" as your site theme
5. Save changes

## Customization

### Theme Settings

Access theme settings at **Site administration** > **Appearance** > **Themes** > **Theme settings** > **Ndara Academy**

#### General Settings
- **Logo**: Upload your institution's logo
- **Primary Color**: Main color for buttons and links
- **Secondary Color**: Color for less prominent elements
- **Accent Color**: Highlight color for special elements
- **Custom CSS**: Add additional CSS styles

#### Advanced Settings
- **SCSS**: Add SCSS code that will be processed after other styles
- **SCSS Pre**: Add SCSS code that will be processed before other styles

### Color Variables

The theme uses CSS custom properties for easy color customization:

```css
:root {
    --ndara-primary: #2563eb;
    --ndara-secondary: #64748b;
    --ndara-success: #059669;
    --ndara-info: #0891b2;
    --ndara-warning: #d97706;
    --ndara-danger: #dc2626;
    --ndara-light: #f8fafc;
    --ndara-dark: #1e293b;
    --ndara-accent: #7c3aed;
    --ndara-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

### Custom CSS Classes

The theme provides several utility classes:

- `.text-gradient`: Apply gradient text effect
- `.bg-gradient`: Apply gradient background
- `.shadow-custom`: Custom shadow effect
- `.rounded-custom`: Custom border radius
- `.fade-in-up`: Fade in animation

## File Structure

```
theme/ndara/
├── config.php              # Theme configuration
├── lib.php                 # Theme functions
├── settings.php            # Theme settings
├── styles.css              # Main CSS file
├── version.php             # Theme version info
├── README.md               # This file
└── lang/
    └── en/
        └── theme_ndara.php # Language strings
```

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Internet Explorer 11+

## Requirements

- Moodle 4.0 or higher
- PHP 7.4 or higher
- Modern web browser with CSS Grid and Flexbox support

## Development

### Adding Custom Styles

1. Edit `styles.css` for basic CSS changes
2. Use the Custom CSS setting in theme settings for quick changes
3. Use SCSS settings for advanced styling

### Extending the Theme

The theme inherits from the Boost theme, so you can override any Boost templates by copying them to:

```
theme/ndara/templates/
```

### Building for Production

1. Ensure all CSS is minified
2. Test on multiple devices and browsers
3. Validate accessibility compliance
4. Check performance metrics

## Support

For support and customization requests, please contact the development team.

## License

This theme is licensed under the GNU General Public License v3.0.

## Credits

- Built for Ndara Academy
- Based on Moodle Boost theme
- Uses Poppins font from Google Fonts
- Icons from Bootstrap Icons

## Changelog

### Version 1.0.0 (2024-08-06)
- Initial release
- Basic theme structure
- Poppins font integration
- Customizable color scheme
- Responsive design
- Bootstrap 4 compatibility 