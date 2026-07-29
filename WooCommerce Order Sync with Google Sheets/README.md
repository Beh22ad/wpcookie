# Sync WooCommerce Orders with Google Sheets Automatically (No Plugins Required)

Managing WooCommerce orders can be time-consuming as your business grows. This guide provides a powerful, plugin-free solution to integrate your store with Google Sheets for streamlined order management.

## Overview

This integration uses:
- WooCommerce REST API
- Google Apps Script
- A small PHP code snippet

## Prerequisites

Before you begin:
- A working WooCommerce store
- A Google account
- Administrative access to your WordPress site

## Step-by-Step Guide

### 1. Enable WooCommerce REST API

1. Log in to your WordPress dashboard
2. Navigate to **WooCommerce > Settings > Advanced > REST API**
3. Click **Add Key**
4. Fill in the description, select a user with appropriate permissions, and set permissions to **Read/Write**
5. Click **Generate API Key**

> **Note:** Save the generated keys for later use.

### 2. Set Up Google Apps Script

1. Create a new Google Spreadsheet
2. Go to **Extensions > Apps Script**
3. Paste the Apps Script code into the editor
   
   > **Code Location:** `google-apps-script/Code.gs`

4. Replace the following variables in the script:
   - `consumerKey`: Your WooCommerce API consumer key
   - `consumerSecret`: Your WooCommerce API consumer secret
   - `siteUrl`: Your WordPress site URL

### 3. Configure Trigger Settings

1. In Apps Script, click **Triggers** in the left sidebar
2. Click **Add Trigger**
3. Configure trigger settings:
   - Choose function: `OnEdit`
   - Event type: On edit
4. Save the trigger configuration

### 4. Deploy the App

1. In the Apps Script editor, click **Deploy > New deployment**
2. Select **Web app**
3. Choose:
   - **Execute the app as:** "Me"
   - **Who has access to the app:** "Anyone" (or adjust as needed)
4. Click **Deploy**
5. Authorize the app to access your Google Drive
6. **Copy the web app URL**

### 5. Add PHP Code to Your WordPress Site

Add the PHP code to your WordPress site:

- In your theme's `functions.php` file (using a child theme)
- Or using the Code Snippets plugin

> **Code Location:** `wordpress-plugin/woocommerce-google-sheets-integration.php`

> **Important:** Create a child theme before modifying `functions.php` to prevent losing changes during updates.

Replace the placeholder for the Google Apps Script URL (`$url`) with the URL you copied in the previous step.

### 6. Fetch Orders

1. Refresh your Google Sheet
2. You should see an **"Orders"** menu
3. Click **Orders > Fetch Orders** to retrieve the last 10 orders
4. Clicking again will fetch the next 10, and so on

## Google Sheet Order Columns

| Column | Description |
|--------|-------------|
| **ID** | The unique order ID |
| **Status** | Current order status (Processing, Completed, Cancelled, etc.) |
| **Name** | Customer's full name |
| **Phone** | Customer's phone number |
| **Billing** | Customer's billing address and email |
| **Products** | Comma-separated list of purchased products |
| **Total** | Total order amount |
| **Date** | Order date |

## Two-Way Synchronization

This setup provides two-way synchronization:
- **Google Sheets → WooCommerce:** Updating order status in the sheet updates it in WooCommerce
- **WooCommerce → Google Sheets:** New orders automatically appear at the top of your order list

## Benefits

- No expensive plugins required
- Streamlined order management
- Improved processing efficiency
- Data accuracy across platforms
- Customizable and flexible solution

## Repository Structure
