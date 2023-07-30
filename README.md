# API-WEBSERVER
A cool REST API to discover the (API) World.

## ONLINE USAGE
- URL: [https://api.cointet.com/locate/](url)

[Link to GeoInfo API in Postman (Restricted Access)](https://automotive-demo.postman.co/workspace/%5Becointet%5D-My-Workspace~ed5d123d-fd73-4f40-80aa-7fb02f7eeec5/api/54cb8f00-e97e-437d-8b71-ca781883098b)

![image](https://raw.githubusercontent.com/ecointet/API-WebServer/main/api-content/screen-api.png)


## LOCAL USAGE

### STEP 1

`git clone https://github.com/ecointet/API-WebServer.git`

`cd API-WebServer`

### STEP 2

`docker build -t ecointet/api-webserver .`

***If successful, run the docker image just created with your Google Map API & OPENAPI (ask for it):***

`docker run -p 8181:8181 -e PORT=8181 -e GKEY="<GOOGLE-KEY>" -e OPENAI="<OPENAI-KEY>" ecointet/api-webserver`

### STEP 3
Open a browser with the url [http://localhost:8181](url)

# HOW TO USE IT

## POSTMAN COLLECTION
Supported End-Point : /locate/<IP>
JSON variable: city, description, photo

[Link to GeoInfo API in Postman (Restricted Access)](https://automotive-demo.postman.co/workspace/%5Becointet%5D-My-Workspace~ed5d123d-fd73-4f40-80aa-7fb02f7eeec5/api/54cb8f00-e97e-437d-8b71-ca781883098b)
