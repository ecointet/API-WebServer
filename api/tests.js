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


//Better way to visualize
var template = `
    <table bgcolor="#FFFFFF">
        <tr>
            <th>Country</th>
            <th>Region</th>
            <th>City</th>
            <th>Desccription</th>
            <th>Photo</th>
        </tr>

            <tr>
                <td>{{response.country}} ({{response.countryCode}})</td>
                <td>{{response.regionName}}</td>
                <td>{{response.city}}</td>
                <td>{{response.description}}</td>
                <td><img src='{{response.photo}}' width="100px"/></td>
            </tr>

    </table>
`;

pm.visualizer.set(template, {
    response: jsonData
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