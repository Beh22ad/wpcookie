/**
@OnlyCurrentDoc
 */
// Plugin Name: WooCommerce Google Sheets Integration
// Author: WPCookie
// Update: Maya1535 {at} gmail.com

// Global variables
var consumerKey = 'ck_0000000000000000';
var consumerSecret = 'cs_0000000000000000';
var siteUrl = 'https://00000000.com/';
var sheetName = 'Orders';


// Function to create the menu
function onOpen() {
  var ui = SpreadsheetApp.getUi();
  ui.createMenu('Orders')
    .addItem('Fetch orders', 'fetchOrders')
    .addToUi();

  // Ensure the "Orders" sheet exists and has the correct headers
  createOrdersSheet();
}

// Function to create the "Orders" sheet if it doesn't exist and add headers
function createOrdersSheet() {
  var spreadsheet = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = spreadsheet.getSheetByName(sheetName);

  if (!sheet) {
    sheet = spreadsheet.insertSheet(sheetName);
    var headers = ['ID', 'Status', 'Name', 'Phone', 'Billing', 'Products', 'Total', 'Date'];
    sheet.appendRow(headers);

    // Set data validation for the "Status" column
    var statusRange = sheet.getRange(2, 2, sheet.getMaxRows());
    var statusRule = SpreadsheetApp.newDataValidation()
      .requireValueInList(['processing', 'on-hold', 'completed', 'cancelled', 'pending', 'refunded'], true)
      .build();
    statusRange.setDataValidation(statusRule);

    // Format the header
    var headerRange = sheet.getRange(1, 1, 1, headers.length);
    headerRange.setFontWeight('bold')
               .setHorizontalAlignment('center')
               .setBackground('#A52A2A') // Brown background
               .setFontColor('#FFFFFF'); // White text

    // Freeze the top row
    sheet.setFrozenRows(1);

    // Format the "Total" column as number with comma separation
    var totalRange = sheet.getRange(2, 7, sheet.getMaxRows());
    totalRange.setNumberFormat('#,##0.00');

    // Format the "Date" column as date
    var dateRange = sheet.getRange(2, 8, sheet.getMaxRows());
    dateRange.setNumberFormat('yyyy-MM-dd');

    // Left-align the "ID" column
    var idRange = sheet.getRange(2, 1, sheet.getMaxRows());
    idRange.setHorizontalAlignment('left');
  }
}

// Function to fetch orders from WooCommerce
function fetchOrders() {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName(sheetName);
  var lastRow = sheet.getLastRow();
  var page = Math.ceil(lastRow / 10); // Calculate the page number based on the number of rows

  var options = {
    'method': 'get',
    'muteHttpExceptions': true,
    'headers': {
      'Authorization': 'Basic ' + Utilities.base64Encode(consumerKey + ':' + consumerSecret)
    }
  };

  var url = siteUrl + 'wp-json/wc/v3/orders?per_page=10&page=' + page;
  var response = UrlFetchApp.fetch(url, options);
  var orders = JSON.parse(response.getContentText());

  if (orders.length > 0) {
    addOrdersToSheet(orders);
  } else {
    SpreadsheetApp.getUi().alert('No more orders to fetch.');
  }
}

// Function to add orders to the sheet
function addOrdersToSheet(orders) {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName(sheetName);
  var data = [];
  orders.forEach(function(order) {
    var status = order.status;
    var name = order.billing.first_name + ' ' + order.billing.last_name;
    var phone = order.billing.phone;
    var billing = [order.billing.address_1, order.billing.address_2, order.billing.city, order.billing.state, order.billing.postcode, order.billing.country, order.billing.email].filter(Boolean).join(', ');
    var products = order.line_items.map(function(item) { return item.name; }).join(', ');
    var total = order.total;
    var date = new Date(order.date_created).toISOString().split('T')[0];

    data.push([order.id, status, name, phone, billing, products, total, date]);
  });

  sheet.getRange(sheet.getLastRow() + 1, 1, data.length, data[0].length).setValues(data);
}

// Function to update order status
function onEdit(e) {
  var range = e.range;
  var sheet = range.getSheet();
  var column = range.getColumn();
  var row = range.getRow();

  if (sheet.getName() === sheetName && column === 2) {
    var orderId = sheet.getRange(row, 1).getValue();
    var newStatus = range.getValue();
    updateOrderStatus(orderId, newStatus);
  }
}

// Function to update order status in WooCommerce
function updateOrderStatus(orderId, newStatus) {
  var options = {
    'method': 'put',
    'muteHttpExceptions': true,
    'headers': {
      'Authorization': 'Basic ' + Utilities.base64Encode(consumerKey + ':' + consumerSecret),
      'Content-Type': 'application/json'
    },
    'payload': JSON.stringify({
      'status': newStatus
    })
  };

  var url = siteUrl + 'wp-json/wc/v3/orders/' + orderId;
  var response = UrlFetchApp.fetch(url, options);
  Logger.log(response.getContentText());
}

// Function to handle incoming data from PHP script
function doPost(e) {
  try {
    // Parse the incoming JSON data
    var order = JSON.parse(e.postData.contents);

    // Get the active sheet for processing orders
    var sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName(sheetName);
    var orderId = order.id;
    var orderRow = findOrderRow(orderId);

    if (orderRow) {
      // Update existing order
      updateOrderInSheet(orderRow, order);
    } else {
      // Add new order
      addNewOrderToSheet(order);
    }

    // Respond to the request
    return ContentService.createTextOutput('Order received').setMimeType(ContentService.MimeType.TEXT);

  } catch (error) {
    // Log any errors to the console
    Logger.log('Error in doPost: ' + error.message);

    // Return an error message
    return ContentService.createTextOutput('Error processing the order').setMimeType(ContentService.MimeType.TEXT);
  }
}


// Function to find the row of an existing order by ID
function findOrderRow(orderId) {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName(sheetName);
  var data = sheet.getDataRange().getValues();
  for (var i = 1; i < data.length; i++) {
    if (data[i][0] == orderId) {
      return i + 1; // Return the row number (1-based index)
    }
  }
  return null;
}

// Function to update an existing order in the sheet
function updateOrderInSheet(row, order) {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName(sheetName);
  var status = order.status;
  var name = order.name;
  var phone = order.phone;
  var billing = order.billing;
  var products = order.products;
  var total = order.total;
  var date = order.date;

  var data = [order.id, status, name, phone, billing, products, total, date];
  sheet.getRange(row, 1, 1, data.length).setValues([data]);
}

// Function to add a new order to the top of the sheet
function addNewOrderToSheet(order) {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName(sheetName);
  var status = order.status;
  var name = order.name;
  var phone = order.phone;
  var billing = order.billing;
  var products = order.products;
  var total = order.total;
  var date = order.date;

  var data = [[order.id, status, name, phone, billing, products, total, date]];

  // Insert the new order at the top of the sheet
  sheet.insertRowBefore(2);
  sheet.getRange(2, 1, 1, data[0].length).setValues(data);
}
