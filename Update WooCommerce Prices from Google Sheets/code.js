/**
@OnlyCurrentDoc
 */
function updateProductPricesCaller() {
  var consumerKey = '000000000000000000';
  var consumerSecret = '000000000000000000';
  var siteUrl = 'https://your_site_url/';
  updateProductPrices(consumerKey, consumerSecret, siteUrl);
}

function onOpen() {
  var ui = SpreadsheetApp.getUi();
  ui.createMenu('WordPress')
    .addItem('Synchronize Prices', 'updateProductPricesCaller')
    .addToUi();
  refreshUpdateColumn();
}

function refreshUpdateColumn() {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
  var lastColumn = sheet.getLastColumn();
  var headers = sheet.getRange(1, 1, 1, lastColumn).getValues()[0];
  var updateColIndex = headers.indexOf("Update") + 1; // Get the index of the Update column (1-based index)

  // If the Update column exists, delete it
  if (updateColIndex > 0) {
    sheet.deleteColumn(updateColIndex);
  }

  // Add the new Update column with checkboxes
  lastColumn = sheet.getLastColumn(); // Update lastColumn after potential deletion
  sheet.insertColumnAfter(lastColumn);
  sheet.getRange(1, lastColumn + 1).setValue("Update");

  // Add checkboxes for each row in the new "Update" column
  var dataRange = sheet.getRange(2, lastColumn + 1, sheet.getLastRow() - 1, 1);
  dataRange.insertCheckboxes();
}

function onEdit(e) {
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
  var priceColumns = [4, 5, 6, 7]; // Adjust based on the actual column positions
  if (priceColumns.indexOf(col) !== -1) {
    // Get the checkbox cell
    var checkboxCell = sheet.getRange(row, updateCol);

    // Check the checkbox
    checkboxCell.setValue(true);
  }
}

function updateProductPrices(consumerKey, consumerSecret, siteUrl) {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();

  var idCol = 0;
  var typeCol = 1;
  var regularPriceCol = 5;
  var salePriceCol = 4;
  var variationRegularPriceCol = 5;
  var variationSalePriceCol = 4;
  var updateCol = data[0].indexOf("Update"); // Get the actual column index of "Update"

  var simpleBatch = [];
  var variationBatches = {};
  var batchSize = 100; // Maximum number of products to update in one batch

  for (var i = 1; i < data.length; i++) { // Start from 1 to skip the header row
    var productId = data[i][idCol];
    var productType = data[i][typeCol];
    var regularPrice = data[i][regularPriceCol];
    var salePrice = data[i][salePriceCol];
    var variationRegularPrice = data[i][variationRegularPriceCol];
    var variationSalePrice = data[i][variationSalePriceCol];
    var update = data[i][updateCol];

    if (update) {
      if (productType === 'simple') {
        simpleBatch.push({
          id: productId,
          regular_price: regularPrice ? regularPrice.toString() : "",
          sale_price: salePrice ? salePrice.toString() : ""
        });

        if (simpleBatch.length >= batchSize) {
          sendBatchRequest(simpleBatch, siteUrl + 'wp-json/wc/v3/products/batch', consumerKey, consumerSecret);
          simpleBatch = [];
        }
      } else if (productType === 'variation') {
        var variationId = productId;
        var parentId = findParentProductId(data, variationId, idCol, typeCol);

        if (!variationBatches[parentId]) {
          variationBatches[parentId] = [];
        }

        variationBatches[parentId].push({
          id: variationId,
          regular_price: variationRegularPrice ? variationRegularPrice.toString() : "",
          sale_price: variationSalePrice ? variationSalePrice.toString() : ""
        });

        if (variationBatches[parentId].length >= batchSize) {
          sendBatchRequest(variationBatches[parentId], siteUrl + 'wp-json/wc/v3/products/' + parentId + '/variations/batch', consumerKey, consumerSecret);
          variationBatches[parentId] = [];
        }
      }

      sheet.getRange(i + 1, updateCol + 1).setValue(false); // Uncheck the checkbox after updating
    }
  }

  // Send any remaining requests in the batches
  if (simpleBatch.length > 0) {
    sendBatchRequest(simpleBatch, siteUrl + 'wp-json/wc/v3/products/batch', consumerKey, consumerSecret);
  }

  for (var parentId in variationBatches) {
    if (variationBatches[parentId].length > 0) {
      sendBatchRequest(variationBatches[parentId], siteUrl + 'wp-json/wc/v3/products/' + parentId + '/variations/batch', consumerKey, consumerSecret);
    }
  }
}

function findParentProductId(data, variationId, idCol, typeCol) {
  var parentId = variationId - 1;

  while (parentId > 0) {
    for (var i = 1; i < data.length; i++) {
      if (data[i][idCol] == parentId && data[i][typeCol] == 'variable') {
        return parentId;
      }
    }
    parentId--;
  }

  return null; // or throw an error if the parent product ID is not found
}

function sendBatchRequest(batch, url, consumerKey, consumerSecret) {
  var payload = {
    update: batch
  };

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

