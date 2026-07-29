/**
@OnlyCurrentDoc
 */
// @Update: WPCookie (Maya1535@gmail.com)

function constants() {
  var consumerKey = '0000000000000000000'; 
  var consumerSecret = '0000000000000000000';
  var siteUrl = 'https://0000000000000000000.com/';

  return [consumerKey,consumerSecret, siteUrl];
}

function onOpen() {
  var ui = SpreadsheetApp.getUi();
  ui.createMenu('WordPress')
    .addItem('Send Updated Prices', 'updateProductPrices')
    .addItem('Fetch Products', 'fetchProducts')
.addToUi();
  refreshUpdateColumn();
}

function fetchProducts() {
  var consumerKey = constants()[0];
  var consumerSecret = constants()[1];
  var siteUrl = constants()[2];
  siteUrl = siteUrl.replace(/\/$/, '');
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var tempFetchSheet = ss.getSheetByName("TempFetch");
  if (!tempFetchSheet) {
    tempFetchSheet = ss.insertSheet("TempFetch");
    var page = 1;
    tempFetchSheet.getRange("A1").setValue(page);
  } else {
	var pageValue = tempFetchSheet.getRange("A1").getValue();
	if (typeof pageValue === 'number') {
      var page = pageValue;
	} else {
	  var page = 1;
	}
  }
  var sheet = ss.getSheetByName('Products');
  var headers = ['ID', 'Type', 'SKU', 'Name', 'In stock?', 'Stock', 'Sale price', 'Regular price', 'Parent'];
  if ( page == 1 || !sheet )
  {
	    if (sheet) {
        ss.deleteSheet(sheet);
		}
		sheet = ss.insertSheet('Products');
	  if (sheet.getLastRow() === 0) {
		sheet.getRange(1, 1, 1, headers.length).setValues([headers]);
		sheet.getRange(1, 1, 1, headers.length).setFontWeight('bold');
	  }
	  page = 1;
  }
  var perPage = 10; // WooCommerce default max per page
  var currentRow = Math.max(sheet.getLastRow() + 1, 2); // Start after headers or last row
  while (true ) {
    var products = fetchProductBatch(siteUrl, consumerKey, consumerSecret, page, perPage);
    var productData = [];
    if (!products || products.length === 0) {
      break;
    }
    products.forEach(function(product) {
		SpreadsheetApp.flush();
      productData.push([
        product.id,
        product.type,
        product.sku,
        product.name,
        product.stock_status === 'instock' ? 1 : 0,
        product.stock_quantity || "",
        product.type === 'variable' ? '' : (product.sale_price || ''),
        product.type === 'variable' ? '' : (product.regular_price || product.price || ''),
        ''
      ]);
      // For variable products, fetch and write variations
      if (product.type === 'variable') {
        var variations = fetchVariations(siteUrl, consumerKey, consumerSecret, product.id);
        if (variations) {
          variations.forEach(function(variation) {
            productData.push( [
              variation.id,
              'variation',
              variation.sku,
              product.name + ' - ' + getVariationAttributes(variation),
              variation.stock_status === 'instock' ? 1 : 0,
              variation.stock_quantity || "",
              variation.sale_price || '',
              variation.regular_price || variation.price || '',
              "id:" + product.id
            ]);
          });
        }
      }
      // Auto-resize columns periodically (every 10 products)
      if (currentRow % 10 === 0) {
        sheet.autoResizeColumns(1, headers.length);
      }
    });
    if (productData.length > 0) {
		productData.forEach(function(product) {
			sheet.getRange(currentRow, 1, 1, headers.length).setValues([product]);
			currentRow++;
		});
      }
    page++;
    tempFetchSheet.getRange("A1").setValue(page);
    Utilities.sleep(500);
  }
  // Final auto-resize and hide columns
  sheet.autoResizeColumns(1, headers.length);
  hideColumns(sheet, ['Type', 'SKU', 'Parent'], headers);
  SpreadsheetApp.getActiveSpreadsheet().toast("Product fetching complete.", 'Success!');
  if (tempFetchSheet) {
    ss.deleteSheet(tempFetchSheet);
	}
  // add update check box
  refreshUpdateColumn();
}

function fetchVariations(siteUrl, consumerKey, consumerSecret, productId) {
  var endpoint = '/wp-json/wc/v3/products/' + productId + '/variations';
  var url = siteUrl + endpoint;

  // Set up parameters
  var params = {
    'per_page': 100  // Maximum variations per request
  };

  try {
    var response = makeAuthenticatedRequest(url, params, consumerKey, consumerSecret);
    return JSON.parse(response.getContentText());
  } catch (error) {
    Logger.log('Error fetching variations for product ' + productId + ': ' + error);
    return null;
  }
}

function getVariationAttributes(variation) {
  if (!variation.attributes || variation.attributes.length === 0) {
    return '';
  }

  return variation.attributes
    .map(function(attr) {
      return attr.option;
    })
    .join(', ');
}

function fetchProductBatch(siteUrl, consumerKey, consumerSecret, page, perPage) {
  var endpoint = '/wp-json/wc/v3/products';
  var url = siteUrl + endpoint;

  // Set up parameters
  var params = {
    'per_page': perPage,
    'page': page
  };

  try {
    var response = makeAuthenticatedRequest(url, params, consumerKey, consumerSecret);
    return JSON.parse(response.getContentText());
  } catch (error) {
    Logger.log('Error fetching products: ' + error);
    return null;
  }
}

function makeAuthenticatedRequest(url, params, consumerKey, consumerSecret) {
  // Create OAuth signature
  var timestamp = Math.floor(Date.now() / 1000);
  var nonce = Utilities.getUuid();

  var signatureBaseString = 'GET&' +
    encodeURIComponent(url) + '&' +
    encodeURIComponent(Object.keys(params)
      .sort()
      .map(function(key) {
        return key + '=' + encodeURIComponent(params[key]);
      })
      .concat([
        'oauth_consumer_key=' + consumerKey,
        'oauth_nonce=' + nonce,
        'oauth_signature_method=HMAC-SHA1',
        'oauth_timestamp=' + timestamp,
        'oauth_version=1.0'
      ])
      .sort()
      .join('&'));

  var signature = Utilities.computeHmacSignature(
    Utilities.MacAlgorithm.HMAC_SHA_1,
    signatureBaseString,
    consumerSecret + '&',
    Utilities.Charset.UTF_8
  );

  // Build authorization header
  var authHeader = 'OAuth ' +
    'oauth_consumer_key="' + consumerKey + '", ' +
    'oauth_nonce="' + nonce + '", ' +
    'oauth_signature="' + encodeURIComponent(Utilities.base64Encode(signature)) + '", ' +
    'oauth_signature_method="HMAC-SHA1", ' +
    'oauth_timestamp="' + timestamp + '", ' +
    'oauth_version="1.0"';

  var options = {
    'method': 'GET',
    'headers': {
      'Authorization': authHeader
    },
    'muteHttpExceptions': true
  };

  // Add parameters to URL
  url += '?' + Object.keys(params)
    .map(function(key) {
      return key + '=' + encodeURIComponent(params[key]);
    })
    .join('&');

  return UrlFetchApp.fetch(url, options);
}

function hideColumns(sheet, columnsToHide, headers) {
  columnsToHide.forEach(function(columnName) {
    var columnIndex = headers.indexOf(columnName) + 1;
    if (columnIndex > 0) {
      sheet.hideColumns(columnIndex);
    }
  });
}
//**********************************************//
//************updat price section **************//
//**********************************************//
function refreshUpdateColumn() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheetByName('Products');
  if (!sheet) {
        return
  }
  var headers = sheet.getRange(1, 1, 1, sheet.getLastColumn()).getValues()[0];
  var updateColIndex = headers.indexOf("Update") + 1;

  if (updateColIndex > 0) {
    sheet.deleteColumn(updateColIndex);
  }
  var lastColumn = sheet.getLastColumn();
  sheet.insertColumnAfter(lastColumn);
  sheet.getRange(1, lastColumn + 1).setValue("Update");

  var dataRange = sheet.getRange(2, lastColumn + 1, sheet.getLastRow() - 1, 1);
  dataRange.insertCheckboxes();
}

function onEdit(e) {
  var activeSheetName = e.source.getActiveSheet().getName();
  if (activeSheetName !== "Products") {
    return;
  }
  var sheet = e.source.getActiveSheet();
  var range = e.range;

  // Get the edited cell's row and column
  var row = range.getRow();
  var col = range.getColumn();

  // Get the last column and the position of the "Update" column
  var lastColumn = sheet.getLastColumn();
  var headers = sheet.getRange(1, 1, 1, lastColumn).getValues()[0];
  var updateCol = headers.indexOf("Update") + 1; // Add 1 because headers is zero-indexed

  // Check if the edited cell is in the Sale Price or Regular Price column
  var priceColumns = [4, 5, 6, 7,8]; // Adjust based on the actual column positions
  if (priceColumns.indexOf(col) !== -1) {
    // Get the checkbox cell
    var checkboxCell = sheet.getRange(row, updateCol);

    // Check the checkbox
    checkboxCell.setValue(true);
  }
}

function updateProductPrices() {
  var consumerKey = constants()[0];
  var consumerSecret = constants()[1];
  var siteUrl = constants()[2];
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
  var data = sheet.getDataRange().getValues();

  var idCol = 0, typeCol = 1, skuCol = 2, inStockCol = 4, stockCol = 5;
  var regularPriceCol = 7, salePriceCol = 6, parentCol = 8, updateCol = data[0].indexOf("Update");

  var simpleBatch = [];
  var variationBatches = {};
  var batchSize = 100;

  for (var i = 1; i < data.length; i++) {
    var productId = data[i][idCol];
    var productType = data[i][typeCol];
    var regularPrice = data[i][regularPriceCol];
    var salePrice = data[i][salePriceCol];
    var inStock = data[i][inStockCol];
    var stockQuantity = data[i][stockCol];
    var update = data[i][updateCol];
    var parent = data[i][parentCol];

    if (update) {
      var productData = {
        id: productId,
        regular_price: regularPrice ? regularPrice.toString() : "",
        sale_price: salePrice ? salePrice.toString() : ""
      };

      // Set stock status based on "In stock?" column
      if (inStock === 1) {
        productData.stock_status = "instock";
      } else if (inStock === 0 || inStock === "") {
        productData.stock_status = "outofstock";
      }

      // Set stock quantity if "Stock" column is not empty
      if (stockQuantity !== "") {
        productData.stock_quantity = stockQuantity;
        productData.manage_stock = true;
      }

      if (productType === 'simple') {
        simpleBatch.push(productData);
        if (simpleBatch.length >= batchSize) {
          sendBatchRequest(simpleBatch, siteUrl + 'wp-json/wc/v3/products/batch', consumerKey, consumerSecret);
          simpleBatch = [];
        }
      } else if (productType === 'variation') {
        var variationId = productId;
        var parentId = findParentProductId(data,i , idCol, typeCol, skuCol, parentCol);
        Logger.log("parent id: "+parentId);

        if (!variationBatches[parentId]) {
          variationBatches[parentId] = [];
        }

        variationBatches[parentId].push({
          id: variationId,
          regular_price: regularPrice ? regularPrice.toString() : "",
          sale_price: salePrice ? salePrice.toString() : "",
          stock_status: productData.stock_status,
          stock_quantity: stockQuantity ? stockQuantity.toString() : "",
          manage_stock: stockQuantity ? true : false
        });

        if (variationBatches[parentId].length >= batchSize) {
          sendBatchRequest(variationBatches[parentId], siteUrl + 'wp-json/wc/v3/products/' + parentId + '/variations/batch', consumerKey, consumerSecret);
          variationBatches[parentId] = [];
        }
      }

      sheet.getRange(i + 1, updateCol + 1).setValue(false);
    }
  }

  if (simpleBatch.length > 0) {
    sendBatchRequest(simpleBatch, siteUrl + 'wp-json/wc/v3/products/batch', consumerKey, consumerSecret);
  }

  for (var parentId in variationBatches) {
    if (variationBatches[parentId].length > 0) {
      sendBatchRequest(variationBatches[parentId], siteUrl + 'wp-json/wc/v3/products/' + parentId + '/variations/batch', consumerKey, consumerSecret);
    }
  }
}

function findParentProductId(data, i, idCol, typeCol, skuCol, parentCol) {
  var parentIdentifier = data[i][parentCol];
  Logger.log("parentIdentifier: "+parentIdentifier);
  for (var j = 1; j < data.length; j++) {
    if (data[j][typeCol] === 'variable') {
      if (parentIdentifier.startsWith("id:") && data[j][idCol] === parseInt(parentIdentifier.split(":")[1])) {
        return data[j][idCol];
      } else if (data[j][skuCol] && data[j][skuCol] === parentIdentifier) {
        return data[j][idCol];
      }
    }
  }

  return null;
}

function sendBatchRequest(batch, url, consumerKey, consumerSecret) {
  var payload = {
    update: batch
  };
  Logger.log(payload);
  var options = {
    method: "POST",
    contentType: "application/json",
    payload: JSON.stringify(payload),
    headers: {
      "Authorization": "Basic " + Utilities.base64Encode(consumerKey + ":" + consumerSecret)
    }
  };

  try {
    var response = UrlFetchApp.fetch(url, options);
    Logger.log("Batch update successful: " + response.getContentText());
  } catch (error) {
    Logger.log("Error in batch update: " + error.message);
  }
}

