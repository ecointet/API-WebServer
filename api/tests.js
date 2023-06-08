//API: /LOCATE/

var jsonData = pm.response.json();

pm.test("Get Country Code: " + jsonData.countryCode, function () {
    pm.expect(jsonData.countryCode).to.be.a("string");
    pm.collectionVariables.set("countryCode", jsonData.countryCode); 
});

pm.test("Get City: " + jsonData.city, function () {
    pm.expect(jsonData.countryCode).to.be.a("string");
    pm.collectionVariables.set("city", jsonData.city.replace(/\s/g, '') ); 
});

//API: /DETAILS

var jsonData = pm.response.json();

pm.test("Country Flag: " + jsonData.flag, function () {
    pm.expect(jsonData.flag).to.be.a("string");
});

//API: /CHATGPT
var jsonData = pm.response.json();

pm.test("chatGPT: " + jsonData.answer, function () {
    pm.expect(jsonData.answer).to.be.a("string");
});