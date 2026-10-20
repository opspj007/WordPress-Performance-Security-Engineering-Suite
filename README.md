WordPress-Performance-Security-Engineering-Suite
### Lightweight SEO-Friendly Breadcrumbs for WordPress

**WP Breadcrumbs** is a lightweight WordPress plugin that adds customizable, SEO-friendly breadcrumbs to WordPress websites. It supports automatic insertion, shortcode-based placement, WooCommerce, custom post types, hierarchical taxonomies, and Schema.org structured data.

## 🚀 Features

- 🔗 Automatic breadcrumb insertion
- `[wp_breadcrumbs]` shortcode
- 🧭 Pages and hierarchical page structures
- 🏷️ Categories, tags and custom taxonomies
- 🧩 Custom post type support
- 🛒 WooCommerce shop and product breadcrumbs
- 🔍 Schema.org `BreadcrumbList` JSON-LD
- ⚙️ Custom Home label and separator
- 🎨 Custom CSS class support
- ⚡ Lightweight and dependency-free
- 🔐 WordPress-native sanitization and escaping
- 🧹 Clean uninstall support

## 🏗️ Architecture

```text
WordPress
│
├── Breadcrumb Engine
│   ├── Pages
│   ├── Posts
│   ├── Taxonomies
│   ├── Custom Post Types
│   └── WooCommerce
│
├── Shortcode API
│
├── Automatic Content Integration
│
├── Schema.org JSON-LD
│
└── Admin Settings
```

## 🛠️ Requirements

- WordPress 5.8+
- PHP 7.4+
- WooCommerce *(optional)*

## 📦 Installation

1. Download the plugin ZIP.
2. Go to **WordPress → Plugins → Add New → Upload Plugin**.
3. Upload `wp-breadcrumbs.zip`.
4. Activate the plugin.
5. Configure it under **Settings → WP Breadcrumbs**.

Alternatively, upload the plugin directory to:

```text
wp-content/plugins/wp-breadcrumbs/
```

## 🔧 Shortcode

Display breadcrumbs anywhere using:

```text
[wp_breadcrumbs]
```

Add a custom CSS class:

```text
[wp_breadcrumbs class="my-breadcrumbs"]
```

## ⚙️ Settings

Navigate to:

**WordPress → Settings → WP Breadcrumbs**

Available options:

- Home label
- Breadcrumb separator
- Automatic insertion
- Show/hide Home
- Show/hide current page
- Enable/disable Schema.org JSON-LD

## 🔎 SEO Structured Data

The plugin automatically generates:

```text
Schema.org
└── BreadcrumbList
    ├── ListItem
    ├── ListItem
    └── ListItem
```

This provides search engines with structured information about the site's breadcrumb hierarchy.

## 🛒 WooCommerce Support

When WooCommerce is active, WP Breadcrumbs can generate breadcrumb paths for:

- Shop
- Product categories
- Parent product categories
- Products

## 👨‍💻 Developer

**Om Prakash Chaudhary**

Senior Software Developer | WordPress Engineer | Generative AI Engineer

GitHub: `opspj007`

## 📄 License

GPLv2 or later.
