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
            <th>Photo</th>
            <th>Description</th>
        </tr>

            <tr>
                <td>{{response.country}} ({{response.countryCode}})</td>
                <td>{{response.regionName}}</td>
                <td>{{response.city}}</td>
                <td><img src='{{response.photo}}' width="100px"/></td>
                <td>{{response.description}}</td>
            </tr>

    </table>
`;

pm.visualizer.set(template, {
    response: jsonData
});