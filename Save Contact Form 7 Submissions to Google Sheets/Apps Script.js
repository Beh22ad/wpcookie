/**
@OnlyCurrentDoc
 */
function doPost(e) {
  var sheetName = "Email log";
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheetByName(sheetName);
  
  // Create sheet if it doesn't exist
  if (!sheet) {
    sheet = ss.insertSheet(sheetName);
    sheet.appendRow(["email", "date", "time"]);
  }
  
  // Parse the incoming request
  var data = JSON.parse(e.postData.contents);
  var emailBody = data.email_body;
  var date = Utilities.formatDate(new Date(), Session.getScriptTimeZone(), "yyyy-MM-dd");
  var time = Utilities.formatDate(new Date(), Session.getScriptTimeZone(), "HH:mm:ss");
  
  // Append the data to the sheet
  sheet.appendRow([emailBody, date, time]);
  
  return "ok";
}
