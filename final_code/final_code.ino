

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClientSecure.h>
#include <DHT.h>


// Define DHT sensor pin and type
#define DHT11_PIN D4
#define DHTTYPE DHT11
DHT dht(DHT11_PIN, DHTTYPE);


// WiFi credentials
const char* ssid = "bryan";
const char* password = "12Login!12345678";


// Server details
const char* SERVER_NAME = "https://vpallas.000webhostapp.com/writeData.php";


// Project API key
String PROJECT_API_KEY = "vintar_pallas";


// Time interval between sensor readings and uploads
unsigned long lastMillis = 0;
long interval = 5000; // 5 seconds


void setup() {
  Serial.begin(115200);
 
  // Initialize DHT sensor
  dht.begin();
 
  // Connect to WiFi
  WiFi.begin(ssid, password);
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
 
  // Set up initial message
  Serial.println("Timer set to 5 seconds.");
}


void loop() {
  if(WiFi.status() == WL_CONNECTED) {
    // Check if it's time to upload sensor data
    if(millis() - lastMillis > interval) {
      uploadTemperatureData();
      lastMillis = millis();
    }
  } else {
    Serial.println("WiFi Disconnected");
  }
  delay(1000);
}


void uploadTemperatureData() {
  // Read sensor data
  float t = dht.readTemperature();
  float h = dht.readHumidity();


  // Check if sensor readings are valid
  if (isnan(h) || isnan(t)) {
    Serial.println(F("Failed to read from DHT sensor!"));
    return;
  }


  // Calculate heat index
  float hic = dht.computeHeatIndex(t, h, false);


  // Location value
  String location = "MMSU";


  // Convert sensor readings to strings
  String humidity = String(h, 2);
  String temperature = String(t, 2);
  String heat_index = String(hic, 2);


  // Print sensor readings
  Serial.println("Location: " + location);
  Serial.println("Temperature: " + temperature);
  Serial.println("Humidity: " + humidity);
  Serial.println("Heat Index: " + heat_index);
  Serial.println("--------------------------");


  // Prepare HTTP POST data
  String temperature_data = "api_key=" + PROJECT_API_KEY;
  temperature_data += "&location=" + location;
  temperature_data += "&temperature=" + temperature;
  temperature_data += "&humidity=" + humidity;
  temperature_data += "&heat_index=" + heat_index;


  // Print HTTP POST data
  Serial.print("Temperature Data: ");
  Serial.println(temperature_data);


  // Set up HTTP client
  WiFiClientSecure client;
  HTTPClient http;


  // Ignore SSL certificate verification (not recommended for production)
  client.setInsecure();


  // Make HTTP POST request to the server
  http.begin(client, SERVER_NAME);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpResponseCode = http.POST(temperature_data);


  // Print HTTP response code
  Serial.print("HTTP Response code: ");
  Serial.println(httpResponseCode);


  // Print server response for debugging
  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.print("Server Response: ");
    Serial.println(response);
  } else {
    Serial.print("Error on sending POST: ");
    Serial.println(http.errorToString(httpResponseCode));
  }


  // Close HTTP connection
  http.end();
}
