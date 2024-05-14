#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>

// WiFi credentials
const char* ssid = "@MangapitTower";
const char* password = "12Login!12345678";

// Server details
const char* SERVER_NAME = "https://vpallas.000webhostapp.com/writedata.php";

// Project API key
String PROJECT_API_KEY = "vintar_pallas";

void setup() {
  Serial.begin(115200);
  Serial.println("Connecting to WiFi");

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    // Create a sample data string
    String data = "Hello from Arduino!";

    // Send the data to the server
    sendPostRequest(data);
    
    delay(3000); // Send data every 3 seconds
  } else {
    Serial.println("WiFi Disconnected");
  }
}

void sendPostRequest(String data) {
  WiFiClientSecure client;
  HTTPClient http;

  http.begin(client, SERVER_NAME);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  // Prepare POST data
  String postData = "api_key=" + PROJECT_API_KEY + "&data=" + data;

  // Send HTTP POST request
  int httpResponseCode = http.POST(postData);

  Serial.println("HTTP Response code: " + String(httpResponseCode));

  http.end();
}



// #include <ESP8266WiFi.h>
// #include <ESP8266HTTPClient.h>
// #include <WiFiClient.h>

// //-------------------------------------------------------------------
// #include <DHT.h>
// #define DHT11_PIN D4
// #define DHTTYPE DHT11

// DHT dht(DHT11_PIN, DHTTYPE);
// //-------------------------------------------------------------------
// //enter WIFI credentials
// const char* ssid = "@MangapitTower";
// const char* password = "12Login!12345678";
// //-------------------------------------------------------------------
// //enter domain name and path
// //http://www.example.com/sensordata.php
// const char* SERVER_NAME = "https://vpallas.000webhostapp.com/writedata.php";

// //PROJECT_API_KEY is the exact duplicate of, PROJECT_API_KEY in config.php file
// //Both values must be same
// String PROJECT_API_KEY = "vintar_pallas";
// //-------------------------------------------------------------------
// //Send an HTTP POST request every 30 seconds
// unsigned long lastMillis = 0;
// long interval = 3000; // 3 seconds
// //-------------------------------------------------------------------

// void setup() {
//   Serial.begin(115200);
//   Serial.println("esp32 serial initialize");

//   dht.begin();
//   Serial.println("initialize DHT11");

//   WiFi.begin(ssid, password);
//   Serial.println("Connecting");
//   while(WiFi.status() != WL_CONNECTED) {
//     delay(500);
//     Serial.print(".");
//   }
//   Serial.println("");
//   Serial.print("Connected to WiFi network with IP Address: ");
//   Serial.println(WiFi.localIP());

//   Serial.println("Timer set to 30 seconds (timerDelay variable),");
//   Serial.println("it will take 30 seconds before publishing the first reading.");
// }

// void loop() {
//   if (WiFi.status() == WL_CONNECTED) {
//     if (millis() - lastMillis > interval) {
//       //Send an HTTP POST request every interval seconds
//       upload_temperature();
//       lastMillis = millis();
//     }
//   } else {
//     Serial.println("WiFi Disconnected");
//   }

//   delay(1000);
// }

// void upload_temperature() {
//   float t = dht.readTemperature();
//   float h = dht.readHumidity();

//   if (isnan(h) || isnan(t)) {
//     Serial.println(F("Failed to read from DHT sensor!"));
//     return;
//   }

//   float hic = dht.computeHeatIndex(t, h, false);

//   String humidity = String(h, 2);
//   String temperature = String(t, 2);
//   String heat_index = String(hic, 2);

//   Serial.println("Temperature: " + temperature);
//   Serial.println("Humidity: " + humidity);
//   Serial.println("Heat Index: " + heat_index);
//   Serial.println("--------------------------");

//   String temperature_data;
//   temperature_data = "api_key=" + PROJECT_API_KEY;
//   temperature_data += "&location=batac";
//   // temperature_data += "&temperature=" + temperature;
//   // temperature_data += "&humidity=" + humidity;
//   // temperature_data += "&heat_index=" + heat_index;

//   Serial.print("temperature_data: ");
//   Serial.println(temperature_data);

//   WiFiClientSecure client;
//   HTTPClient http;

//   http.begin(client, SERVER_NAME);
//   http.addHeader("Content-Type", "application/x-www-form-urlencoded");
//   int httpResponseCode = http.POST(temperature_data);

//   Serial.print("HTTP Response code: ");
//   Serial.println(httpResponseCode);

//   http.end();
// }
